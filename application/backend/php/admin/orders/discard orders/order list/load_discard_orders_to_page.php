<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    $response['success'] = true;

    if (isset($_POST['searchTerm'])) {
		$searchTerm = $_POST['searchTerm'];

        ob_start();
        include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/configuration/php/connect to database/con_server.php';
        $includedContent = ob_get_clean();

        $conn_status_data = json_decode($includedContent, true);
        $conn_status = $conn_status_data['connection_status'];

        if ($conn_status === "successfully") {
			
			$state = "expired";
			if ($searchTerm === '') {
				$selectDiscardOrders = "SELECT o.order_id, u.user_id, u.email, o.ordered_date, o.ordered_time
										FROM orders o
										INNER JOIN cart c ON o.cart_no = c.cart_no
										INNER JOIN users u ON c.user_id = u.user_id
										WHERE state = ?";
			} else {						
				$selectDiscardOrders = "SELECT o.order_id, u.user_id, u.email, o.ordered_date, o.ordered_time
										FROM orders o
										INNER JOIN cart c ON o.cart_no = c.cart_no
										INNER JOIN users u ON c.user_id = u.user_id
										WHERE o.order_id LIKE ? AND state = ?";
			}
			
			if ($stmt = $conn->prepare($selectDiscardOrders)) {
			
				if ($searchTerm !== '') {
					$searchNewTerm = '%' . $searchTerm . '%';
					$stmt->bind_param("ss", $searchNewTerm, $state);
				} else {
					$stmt->bind_param("s", $state);
				}
				
				$stmt->execute();
				$result = $stmt->get_result();
				
				if ($result->num_rows > 0) {
					$ordersDetails = array(); // Initialize the array
					while ($row = $result->fetch_assoc()) {
						$ordersDetail = array(
							'userID' => strtoupper($row['user_id']),
							'orderID' => $row['order_id'],
							'email' => $row['email'],
							'orderedDate' => $row['ordered_date'],
							'orderedTime' => $row['ordered_time']
						);

						$ordersDetails[] = $ordersDetail; 
					}
					$response['ordersDetails'] = $ordersDetails; 
				} else {
					if ($searchTerm === '') {
						$response['noFoods'] = true;
						$response['content'] = "No discard orders yet!";
					} else {
						$response['noFoods'] = true;
						$response['content'] = "No discard order found with the id '$searchTerm'";
					}
				}
				$stmt->close();
			} else {
                $response = array(
                    'success' => false,
                    'alert' => 'Error in selectDiscardOrders query.',
                    'error_page' => true
                );
            }
        } else {
            $response = array(
                'success' => false,
                'alert' => 'Database connection error.',
                'error_page' => true
            );
        }
        $conn->close();
    } else {
		$response = array(
			'success' => false,
			'alert' => 'Error in selection type.',
			'error_page' => true
		);
	}
} else {
    $response = array(
        'success' => false,
        'alert' => 'Invalid request method.',
        'error_page' => true
    );
}


function capitalizeAfterSpaceOrSpecialChar($word) {
    $nameParts = preg_split("/[\s_]+/", $word);
    $capitalizedParts = array_map('ucwords', $nameParts);
    $capitalizedName = implode(' ', $capitalizedParts);

    $capitalizedName = ucfirst($capitalizedName);

    return $capitalizedName;
}

header('Content-Type: application/json');
echo json_encode($response);
?>
