<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
	$response['success'] = true;
    
	$foodName = strtolower($_POST['food_name']);
	$discountPrice = $_POST['discount_price'];
	$nonDiscountPrice = $_POST['non_discount_price'];
	$foodDetails = strtolower($_POST['food_details']);
	$foodCategory = $_POST['food_category'];
	$availability = $_POST['availability'];

	$allowedTypes = array('image/jpeg', 'image/jpg', 'image/png', 'image/ico');

	if (!in_array($_FILES['imageUpload']['type'], $allowedTypes)) {
		$response['success'] = false;
		$response['foodPictureAlert'] = '*Invalid file type. Allowed types are: ' . implode(', ', $allowedTypes);
	} else {
		$response['foodPictureAlert'] = '';
	}
	
	
	if (empty($foodName)) {
		$response['success'] = false;
		$response['foodNameAlert'] = '*Food name is required.';
	} elseif (preg_match('/\d/', $foodName)) {
		$response['success'] = false;
		$response['foodNameAlert'] = '*Food name should not contain numbers.';
	} else {
		$response['foodNameAlert'] = ''; 
	}
	
	
	if (empty($discountPrice)) {
		$response['success'] = false;
		$response['discountPriceAlert'] = '*Dis-countable price is required.';
	} elseif (!is_numeric($discountPrice)) {
		$response['success'] = false;
		$response['discountPriceAlert'] = '*Invalid numeric value. Please enter a valid numeric value (e.g., 100.40).';
	} else {
		$response['discountPriceAlert'] = '';
	}
	
	
	
	if (empty($nonDiscountPrice)) {
		$response['success'] = false;
		$response['nonDiscountPriceAlert'] = '*Dis-countable price is required.';
	} elseif (!is_numeric($nonDiscountPrice)) {
		$response['success'] = false;
		$response['nonDiscountPriceAlert'] = '*Invalid numeric value. Please enter a valid numeric value (e.g., 100.40).';
	} else {
		$response['nonDiscountPriceAlert'] = '';
	}
	

	
	if (empty($foodDetails)) {
		$response['success'] = false;
		$response['foodDetailsAlert'] = '*Food details is required.';
	} else {
		$response['foodDetailsAlert'] = ''; 
	}
	
	
	if ($foodCategory === 'undefined') {
		$response['success'] = false;
		$response['categoryAlert'] = '*Food category is required.';
	} else {
		$response['categoryAlert'] = ''; 
	}
	
	
	if ($availability === 'undefined') {
		$response['success'] = false;
		$response['availabilityAlert'] = '*Food availability is required.';
	} else {
		$response['availabilityAlert'] = ''; 
	}
	
	
	
	if ($response['success'] === true) {
		
		ob_start();
		include '/xampp/htdocs/FoodFrenzy/configuration/php/connect to database/con_server.php';
		$includedContent = ob_get_clean();

		$conn_status_data = json_decode($includedContent, true);
		$conn_status = $conn_status_data['connection_status'];

		if ($conn_status === "successfully") {
			
			$deleteAvailability = 'deleted';
			$deleteFoodExistCheck = "SELECT food_name FROM foods WHERE food_name = ? AND availability = ?";
			if ($stmt = $conn->prepare($deleteFoodExistCheck)) {
				$stmt->bind_param("ss", $foodName, $deleteAvailability);
				$stmt->execute();
				$result = $stmt->get_result();
				$stmt->close();
				
				if ($result->num_rows === 0) {
					$foodExistCheck = "SELECT food_name FROM foods WHERE food_name = ?";
			
					if ($stmt = $conn->prepare($foodExistCheck)) {
						$stmt->bind_param("s", $foodName);
						$stmt->execute();
						$result = $stmt->get_result();
						$stmt->close();
					
						if ($result->num_rows === 0) {
							
							$upload_dir = "/xampp/htdocs/FoodFrenzy/storage/photos/foods/" .$foodName. "/";
							
							if (!file_exists($upload_dir)) {
								mkdir($upload_dir, 0777, true); 
							}
							
							$foodImage = $_FILES['imageUpload'];

							$new_file_name = $foodName . ".jpg";
							$new_file_path = $upload_dir . $new_file_name;

							if (file_exists($new_file_path)) {
								unlink($new_file_path);
							}
							
							move_uploaded_file($foodImage['tmp_name'], $new_file_path);
							
							$insertFoodDetails = "INSERT INTO foods (food_name, discount_price, non_discount_price, category, availability, photo_path, details)
													VALUES (?, ?, ?, ?, ?, ?, ?)";
							
							if ($stmt = $conn->prepare($insertFoodDetails)) {
								$stmt->bind_param("sssssss", $foodName, $discountPrice, $nonDiscountPrice, $foodCategory, $availability, $new_file_name, $foodDetails);
								$stmt->execute();
								$stmt->close();
								
								$response['alertContent'] = true;
								$response['alert'] = "Food addition is successfully.!";
							} else {
								$response = array(
									'success' => false,
									'alert' => 'Error in the insertFoodDetails query.',
									'error_page' => true
								);
							}
						} else {
							$response['alertContent'] = true;
							$response['alert'] = "This food is already added to the system.!";
						}
					} else {
						$response = array(
							'success' => false,
							'alert' => 'Error in the foodExistCheck query.',
							'error_page' => true
						);
					}
				} else {
					$upload_dir = "/xampp/htdocs/FoodFrenzy/storage/photos/foods/" .$foodName. "/";
							
					if (!file_exists($upload_dir)) {
						mkdir($upload_dir, 0777, true); 
					}
							
					$foodImage = $_FILES['imageUpload'];

					$new_file_name = $foodName . ".jpg";
					$new_file_path = $upload_dir . $new_file_name;

					if (file_exists($new_file_path)) {
						unlink($new_file_path);
					}
							
					move_uploaded_file($foodImage['tmp_name'], $new_file_path);
					
					$updateDeleteFood = "UPDATE foods SET discount_price = ?, non_discount_price = ?, category = ?, availability = ?, photo_path = ?, details = ? WHERE food_name = ?";
					if ($stmt = $conn->prepare($updateDeleteFood)) {
						$stmt->bind_param("ddsssss", $discountPrice, $nonDiscountPrice, $foodCategory, $availability, $new_file_name, $foodDetails, $foodName);
						$stmt->execute();
						$result = $stmt->get_result();
						$stmt->close();
						
						$response['alertContent'] = true;
						$response['alert'] = "Food addition is successfully.!";
					}
				}
			} else {
				$response = array(
					'success' => false,
					'alert' => 'Error in the deleteFoodExistCheck query.',
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
