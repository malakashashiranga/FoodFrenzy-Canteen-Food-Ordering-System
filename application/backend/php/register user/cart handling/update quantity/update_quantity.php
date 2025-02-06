<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    $response['success'] = true;

    ob_start();
    include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/configuration/php/connect to database/con_server.php';
    $includedContent = ob_get_clean();

    $conn_status_data = json_decode($includedContent, true);
    $conn_status = $conn_status_data['connection_status'];

    if ($conn_status === "successfully") {

        if (
            isset($_SESSION['user_id']) &&
            !empty($_SESSION['user_id']) &&
            isset($_POST['foodId']) && 
            isset($_POST['change'])
        ) {
            $userID = $_SESSION['user_id'];
            $foodId = $_POST['foodId'];
            $change = (int)$_POST['change']; 

            $activeValue = 'active';
            $checkActiveCart = "SELECT cart_no FROM cart WHERE user_id = ? AND status = ?";
            if ($stmt = $conn->prepare($checkActiveCart)) {
                $stmt->bind_param("ss", $userID, $activeValue);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $cartNo = $row['cart_no'];

                    $updateQuantity = "UPDATE cart_foods cf
                    INNER JOIN foods f ON cf.food_number = f.food_number 
                    SET cf.quantity = GREATEST(0, cf.quantity + ?) 
                    WHERE cf.cart_no = ? AND cf.food_number = ?";

					if ($stmtUpdate = $conn->prepare($updateQuantity)) {
						$stmtUpdate->bind_param("iii", $change, $cartNo, $foodId);
						$stmtUpdate->execute();

						if ($stmtUpdate->affected_rows > 0) {
						// Check if quantity is zero, then delete the record
							$checkQuantity = "SELECT cf.quantity, f.food_name FROM cart_foods cf
                                                INNER JOIN foods f ON cf.food_number = f.food_number 
												WHERE cf.cart_no = ? AND cf.food_number = ?";
							if ($stmtCheck = $conn->prepare($checkQuantity)) {
								$stmtCheck->bind_param("ii", $cartNo, $foodId);
								$stmtCheck->execute();
								$stmtCheck->store_result();
				
								if ($stmtCheck->num_rows > 0) {
									$stmtCheck->bind_result($updatedQuantity, $foodName);
									$stmtCheck->fetch();

									if ($updatedQuantity == 0) {
										// Delete the record when the quantity becomes zero
										$deleteRecord = "DELETE FROM cart_foods WHERE cart_no = ? AND food_number = ?";
										if ($stmtDelete = $conn->prepare($deleteRecord)) {
											$stmtDelete->bind_param("ii", $cartNo, $foodId);
											$stmtDelete->execute();
											$stmtDelete->close();
											
											$response['removeAlert'] = true;
										}
									}
									if ($change === -1) {
										$response['command'] = "added";
									} else {
										$response['command'] = "substituted";
									}
									$response['foodName'] = $foodName;
								}
								$stmtCheck->close();
							}
						} else {
							$response = array(
								'success' => false,
								'alert' => 'Failed to update quantity in the cart.',
								'error_page' => true
							);
						}
						$stmtUpdate->close();
					} else {
						$response = array(
							'success' => false,
							'alert' => 'Error in prepare statement for updateQuantity query.',
							'error_page' => true
						);
					}
                } else {
                    $response = array(
                        'success' => false,
                        'alert' => 'No active cart found for the user.',
                        'error_page' => true
                    );
                }
                $stmt->close();
            } else {
                $response = array(
                    'success' => false,
                    'alert' => 'Error in checkActiveCart query.',
                    'error_page' => true
                );
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
