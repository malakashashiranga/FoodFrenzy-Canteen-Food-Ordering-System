<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    $response['success'] = true;
    $UserId = $_SESSION['user_id'];

    $buttonId = $_POST['buttonId'];
    $buttonClass = $_POST['buttonClass'];
    $action = $_POST['action'];
	
	$upload_dir = "/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/storage/photos/users/".$UserId. "/";

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true); 
    }
	
	ob_start();
	include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/configuration/php/connect to database/con_server.php';
	$includedContent = ob_get_clean();
    
	$conn_status_data = json_decode($includedContent, true);
	$conn_status = $conn_status_data['connection_status'];
				
	if ($conn_status === "successfully") {

		if ($buttonId === 'pic_confirm_button' && $buttonClass === 'pic-ajax-button' && $action === 'pro_pic_confirm') {
			
			if (isset($_FILES['imageFile']) && $_FILES['imageFile']['error'] === UPLOAD_ERR_OK) {
		
				$allowedTypes = array('image/jpeg', 'image/jpg', 'image/png', 'image/ico');
				if (in_array($_FILES['imageFile']['type'], $allowedTypes)) {
					$imageFile = $_FILES['imageFile'];

					$new_file_name = $UserId . ".jpg";
					$new_file_path = $upload_dir . $new_file_name;

					if (file_exists($new_file_path)) {
						unlink($new_file_path);
					}
					
					move_uploaded_file($imageFile['tmp_name'], $new_file_path);
					$response['buttons'] = 'pro_pic_confirm_btn';

					$updatePhotoLocation = "UPDATE users SET photo_path = ? WHERE user_id = ?";

					if ($stmt = $conn->prepare($updatePhotoLocation)) {
						$stmt->bind_param("ss", $new_file_name, $UserId);
						$stmt->execute();
						$stmt->close(); 

						$response['alertContent'] = true;
						$response['alertType'] = 'prof_pic_changed';
						$response['alert'] = "Your profile picture updated successfully.!";
					} else {
						$response = array(
							'success' => false,
							'alert' => 'Error in photo path update statement',
							'error_page' => true
						);
					}
				} else {
					$response['success'] = false;
					$response['alertContent'] = true;
					$response['alertType'] = 'prof_pic_changed';
					$response['alert'] = "Invalid file type. Only jpeg, jpg, png, and ico files are allowed.!";
				}
			} else {
				$response = array(
					'success' => false,
					'alert' => 'File upload error.',
					'error_page' => true,
				);
			}
	
		} elseif ($buttonId === 'img_remv' && $buttonClass === 'pic-ajax-button' && $action === 'pro_pic_remove') {
				
			$photoToRemove = $upload_dir . $UserId . ".jpg";
    
			if (file_exists($photoToRemove)) {
				unlink($photoToRemove);
			}

			$updatePhotoLocation = "UPDATE users SET photo_path = ? WHERE user_id = ?";
			if ($stmt = $conn->prepare($updatePhotoLocation)) {
				$emptyPath = ''; 
				$stmt->bind_param("ss", $emptyPath, $UserId);
				$stmt->execute();
				$stmt->close();

				$response['alertContent'] = true;
				$response['alertType'] = 'prof_pic_removed';
				$response['alert'] = "Your profile picture has been removed successfully.!";
			} else {
				$response = array(
					'success' => false,
					'alert' => 'Error in photo delete statement',
					'error_page' => true
				);
			}
		} else {
			$response = array(
				'success' => false,
				'alert' => 'Error in process',
				'error_page' => true,
			);
		}
	} else {
		$response = array(
			'success' => false,
			'alert' => 'Detabase connection error.',
			'error_page' => true
		);
	}
	$conn->close();
} else {
    $response = array(
        'success' => false,
        'alert' => 'Invalid request method.',
        'error_page' => true,
    );
}


header('Content-Type: application/json');
echo json_encode($response);
?>
