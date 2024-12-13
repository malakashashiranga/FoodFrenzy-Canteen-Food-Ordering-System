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

        if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
			
			$activeValue = 'active';
            $checkActiveCart = "SELECT cart_no FROM cart WHERE user_id = ? AND status = ?";
            if ($stmt = $conn->prepare($checkActiveCart)) {
                $stmt->bind_param("ss", $userID, $activeValue);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $cartNo = $row['cart_no'];
					
					$availability = 'available';
                    $retrieveFoodDetails = "SELECT f.food_name, cf.quantity, f.photo_path, f.discount_price, f.food_number 
                                                FROM cart_foods cf
                                                INNER JOIN foods f ON cf.food_number = f.food_number
                                                WHERE cf.cart_no = ? AND f.availability = ?";
                    if ($stmtFood = $conn->prepare($retrieveFoodDetails)) {
                        $stmtFood->bind_param("ss", $cartNo, $availability);
                        $stmtFood->execute();
                        $resultFood = $stmtFood->get_result();
						
						$totalPrice = 0;

                        if ($resultFood->num_rows > 0) {
                            $foodDetails = array();
                            while ($rowFood = $resultFood->fetch_assoc()) {
                                $foodDetail = array(
									'food_number' => $rowFood['food_number'],
                                    'food_name' => ucfirst($rowFood['food_name']),
                                    'quantity' => $rowFood['quantity'],
									'price' => "Rs. ".$rowFood['discount_price'] * $rowFood['quantity'],
                                    'photo_path' => "/FoodFrenzy/storage/photos/foods/" .$rowFood['food_name']. "/" .$rowFood['photo_path']
                                );

                                $foodDetails[] = $foodDetail;

								$totalPrice += $rowFood['discount_price'] * $rowFood['quantity'];
							}
							
							if ($totalPrice > 0) {
								$response['checkOutButton'] = true;
								$response['totalPrice'] = "Total price : Rs. ".$totalPrice."/=";
							} 
		
							$response['foodDetails'] = $foodDetails;
						} else {
							$response['noFoods'] = true;
							$response['alert'] = "No foods added to the cart yet !!!";
						}

                    } else {
						$response = array(
							'success' => false,
							'alert' => 'Error in retrieveFoodDetails query.',
							'error_page' => true
						);
                    }
                } else {
					$response['noFoods'] = true;
					$response['alert'] = "No foods added to the cart yet !!!";
                }
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
                'alert' => 'User ID not provide for page operation.',
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
