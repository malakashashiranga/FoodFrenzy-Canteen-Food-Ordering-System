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
		
		if ($_SESSION['currentPage'] === 'reg_home_page' || $_SESSION['currentPage'] === 'reg_food_page') {
			
			$foodNumber = $_POST['foodNumber'];
			$userID = $_SESSION['user_id'];
			$availability = 'available';
		
			$checkFoodDetails = "SELECT * FROM foods WHERE availability = ? AND food_number = ?";
			if ($stmt = $conn->prepare($checkFoodDetails)) {
				$stmt->bind_param("ss", $availability, $foodNumber);
				$stmt->execute();
				$result = $stmt->get_result();

				if ($result->num_rows > 0) {
					$row = $result->fetch_assoc(); 
					$foodName = $row['food_name'];
					
					$cartStatus = 'active';
					
					$checkCartDetails = "SELECT * FROM cart WHERE user_id = ? AND status = ?";
					if ($stmt = $conn->prepare($checkCartDetails)) {
						$stmt->bind_param("ss", $userID, $cartStatus);
						$stmt->execute();
						$result = $stmt->get_result();
						
						if ($result->num_rows > 0) {
							$row = $result->fetch_assoc(); 
							$cartNumber = $row['cart_no'];
						} else {
							
							$createCart = "INSERT INTO cart(user_id, start_date, start_time, status) VALUES (?, CURDATE(), CURTIME(), ?)";
							if ($stmt = $conn->prepare($createCart)) {
								$stmt->bind_param("ss", $userID, $cartStatus);
								$stmt->execute();
								
								$checkCartDetails = "SELECT * FROM cart WHERE user_id = ? AND status = ?";
								if ($stmt = $conn->prepare($checkCartDetails)) {
									$stmt->bind_param("ss", $userID, $cartStatus);
									$stmt->execute();
									$result = $stmt->get_result();
						
									if ($result->num_rows > 0) {
										
										$row = $result->fetch_assoc(); 
										$cartNumber = $row['cart_no'];
									}
								} else {
									$response = array(
										'success' => false,
										'alert' => 'Error in checkCartDetails query.',
										'error_page' => true
									);
								}
							} else {
								$response = array(
									'success' => false,
									'alert' => 'Error in createCart query.',
									'error_page' => true
								);
							}
						}
						
						$checkCartFoodDetails = "SELECT * FROM cart_foods WHERE cart_no = ? AND food_number = ?";
						if ($stmt = $conn->prepare($checkCartFoodDetails)) {
							$stmt->bind_param("ss", $cartNumber, $foodNumber);
							$stmt->execute();
							$result = $stmt->get_result();
						
							if ($result->num_rows > 0) {
								
								$updateQuantityFoodCart = "UPDATE cart_foods SET quantity = quantity + 1 WHERE food_number = ?";
								if ($stmt = $conn->prepare($updateQuantityFoodCart)) {
									$stmt->bind_param("s", $foodNumber); 
									$stmt->execute();
								} else {
									$response = array(
										'success' => false,
										'alert' => 'Error in updateQuantityFoodCart query.',
										'error_page' => true
									);
								}
								
							} else {
								$quantity = 1;
								$createCartFood = "INSERT INTO cart_foods(cart_no, food_number, quantity) VALUES (?, ?, ?)";
								if ($stmt = $conn->prepare($createCartFood)) {
									$stmt->bind_param("sss", $cartNumber, $foodNumber, $quantity);
									$stmt->execute();
									
								} else {
									$response = array(
										'success' => false,
										'alert' => 'Error in createCartFood query.',
										'error_page' => true
									);
								}
							}
							
							$response['addCartAlert'] = capitalizeAfterSpaceOrSpecialChar($foodName). ' added to the cart';
							
						} else {
							$response = array(
								'success' => false,
								'alert' => 'Error in checkCartFoodDetails query.',
								'error_page' => true
							);
						}
						
					} else {
						$response = array(
							'success' => false,
							'alert' => 'Error in checkCartDetails query.',
							'error_page' => true
						);
					}					
				} else {
					$response['addCartAlert'] = 'Apologies, there are no food items available in the system based on your selection.!';
				}
			} else {
				$response = array(
					'success' => false,
					'alert' => 'Error in checkFoodDetails query.',
					'error_page' => true
				);
			}
		} elseif ($_SESSION['currentPage'] === 'un_reg_home_page' || $_SESSION['currentPage'] === 'un_reg_food_page') {
			$response['regCart'] = true;
		} else {
			$response = array(
				'success' => false,
				'alert' => 'This is not the right page operation.',
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