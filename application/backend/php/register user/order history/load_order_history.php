<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    $response['success'] = true;

    $userID = $_SESSION['user_id'];

    ob_start();
    include '/xampp/htdocs/FoodFrenzy/configuration/php/connect to database/con_server.php';
    $includedContent = ob_get_clean();

    $conn_status_data = json_decode($includedContent, true);
    $conn_status = $conn_status_data['connection_status'];

    if ($conn_status === "successfully") {

        $retrieveCartHistoryDetails = "SELECT o.order_id, cf.quantity, f.discount_price, f.discount_price * cf.quantity AS total_price, o.state, o.ordered_date, o.ordered_time, o.pay_method
                                FROM orders o
                                INNER JOIN cart c ON o.cart_no = c.cart_no
                                INNER JOIN cart_foods cf ON c.cart_no = cf.cart_no
                                INNER JOIN foods f ON cf.food_number = f.food_number
                                WHERE c.user_id = ?
								ORDER BY o.ordered_date DESC, o.ordered_time DESC";

        if ($stmt = $conn->prepare($retrieveCartHistoryDetails)) {
            $stmt->bind_param("s", $userID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $cartHistory = array();
                while ($row = $result->fetch_assoc()) {
                    $orderID = $row['order_id'];
					$state = ucfirst($row['state']);
					
					if ($state === 'Checked') {
						$state = 'Finished';
					}
					
                    if (!isset($cartHistory[$orderID])) {
                        $cartHistory[$orderID] = array(
                            'orderDate' => $row['ordered_date'],
                            'orderedTime' => $row['ordered_time'],
                            'state' => $state,
                            'netPrice' => 0  // Initialize net price for each order
                        );
                    }
                    $cartHistory[$orderID]['netPrice'] += $row['total_price'];
                }
                $response['cartHistory'] = $cartHistory;
            } else {
                $response['noCartHistory'] = true;
				$response['alert'] = 'No order history!';
            }
            $stmt->close();
        } else {
            $response = array(
                'success' => false,
                'alert' => 'Error in retrieveCartHistoryDetails query!',
                'error_page' => true
            );
        }
    } else {
        $response = array(
            'success' => false,
            'alert' => 'Database connection error.',
            'error_console' => true
        );
    }

} else {
    $response = array(
        'success' => false,
        'alert' => 'Invalid request method.',
        'error_page' => true
    );
}


header('Content-Type: application/json');
echo json_encode($response);
?>
