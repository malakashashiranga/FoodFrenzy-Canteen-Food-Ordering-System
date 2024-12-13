<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    $response['success'] = true;

    ob_start();
    include '/xampp/htdocs/FoodFrenzy/configuration/php/connect to database/con_server.php';
    $includedContent = ob_get_clean();

    $conn_status_data = json_decode($includedContent, true);
    $conn_status = $conn_status_data['connection_status'];

    if ($conn_status === "successfully") {

        if (
            isset($_SESSION['user_id']) &&
            !empty($_SESSION['user_id']) &&
            isset($_POST['foodId']) && 
            isset($_POST['operationType'])
        ){
			$userID = $_SESSION['user_id'];
            $foodId = $_POST['foodId'];
            $operationType = $_POST['operationType']; 
			
			if ($operationType === "removeFood") {
				
				$activeValue = 'active';
				$checkActiveCart = "SELECT cart_no FROM cart WHERE user_id = ? AND status = ?";
				if ($stmt = $conn->prepare($checkActiveCart)) {
					$stmt->bind_param("ss", $userID, $activeValue);
					$stmt->execute();
					$result = $stmt->get_result();

					if ($result->num_rows > 0) {
						$row = $result->fetch_assoc();
						$cartNo = $row['cart_no'];
				
						$deleteRecord = "DELETE cf FROM cart_foods cf
							INNER JOIN cart c ON cf.cart_no = c.cart_no
							WHERE c.user_id = ? AND cf.food_number = ? AND cf.cart_no = ?";

						if ($stmtDelete = $conn->prepare($deleteRecord)) {
							$stmtDelete->bind_param("iii", $userID, $foodId, $cartNo);
							$stmtDelete->execute();
							$stmtDelete->close();
							
							$response['removeAlert'] = true;
						}
					}
				}
			}
        } else {
            $response = array(
                'success' => false,
                'alert' => 'Invalid input parameters for page operation.',
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
        'alert' => 'Invalid request method.',
        'error_page' => true
    );
}


header('Content-Type: application/json');
echo json_encode($response);
?>
