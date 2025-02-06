<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    $response['success'] = true;
	
	if (isset($_POST['buttonId']) && isset($_POST['buttonClass']) && isset($_POST['action'])) {
		
        $buttonId = $_POST['buttonId'];
        $buttonClass = $_POST['buttonClass'];
        $action = $_POST['action'];
		
		if (isset($_SESSION['settingButtonProcess']) && isset($_SESSION['settingButtonId']) && isset($_SESSION['settingBtnProcessStep1'])) {
			
			$prevSettingButtonProcess = $_SESSION['settingButtonProcess'];
			$prevSettingButtonId = $_SESSION['settingButtonId'];
			
			$UserId = $_SESSION['user_id'];
			
			ob_start();
			include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/configuration/php/connect to database/con_server.php';
			$includedContent = ob_get_clean();
    
			$conn_status_data = json_decode($includedContent, true);
			$conn_status = $conn_status_data['connection_status'];
				
			if ($conn_status === "successfully") {
				
				if ($buttonId === 'conf_change_prof_info' && $buttonClass === 'ajax-button' && $action === 'conf_prof_info' && $prevSettingButtonProcess === 'prof_info' && $prevSettingButtonId === 'prof_info') {

					$formData1 = $_POST['formData1'];

					$newFirstName = strtolower($formData1['first_name']);
					$newLastName = strtolower($formData1['last_name']);
					$newPhone = strtolower($formData1['phone']);
					$newAddress = strtolower($formData1['address']);
						
					if (empty($newFirstName)) {
						$response['success'] = false;
						$response['firstNameAlert'] = '*First name is required.';
					} elseif (preg_match('/\d/', $newFirstName)) {
						$response['success'] = false;
						$response['firstNameAlert'] = '*First name should not contain numbers.';
					} else {
						$response['firstNameAlert'] = ''; // Clear the alert message
					}

					if (empty($newLastName)) {
						$response['success'] = false;
						$response['lastNameAlert'] = '*Last name is required.';
					} elseif (preg_match('/\d/', $newLastName)) {
						$response['success'] = false;
						$response['lastNameAlert'] = '*Last name should not contain numbers.';
					} else {
						$response['lastNameAlert'] = ''; // Clear the alert message
					}
						
					if (empty($newPhone)) {
						$response['success'] = false;
						$response['mobileNumberAlert'] = '*Mobile number is required.';
					} elseif (!preg_match('/^\d{10}$/', $newPhone)) {
						$response['success'] = false;
						$response['mobileNumberAlert'] = '*Mobile number should have 10 digits.';
					} else {
						$response['mobileNumberAlert'] = ''; // Clear the alert message
					}

					if (empty($newAddress)) {
						$response['success'] = false;
						$response['addressAlert'] = '*Home address is required.';
					} else {
						$response['addressAlert'] = ''; // Clear the alert message
					}
						
					if ($response['success'] === true) {
							
						$updateUserData = "UPDATE users SET first_name = ?, last_name = ?, mobile_number = ?, address = ? WHERE user_id = ?"; 
						
						if ($stmt = $conn->prepare($updateUserData)) {
							$stmt->bind_param("sssss", $newFirstName, $newLastName, $newPhone, $newAddress, $UserId);
							$stmt->execute();
							$stmt->close(); 
									
							$response['alertContent'] = true;
							$response['alertType'] = 'prof_info';
							$response['alert'] = "Your informations was updated successfully.!";
						} else {
							$response = array(
								'success' => false,
								'alert' => 'Error in user information update statement',
								'error_page' => true
							);
						}
					}
				} elseif ($buttonId === 'conf_change_pword' && $buttonClass === 'ajax-button' && $action === 'conf_password_ch' && $prevSettingButtonProcess === 'password_ch' && $prevSettingButtonId === 'password_ch') {
				
					$formData3 = $_POST['formData3'];

					$currentPassword = $formData3['currentPassword'];
					$newPassword = $formData3['newPassword'];
					$confirmPassword = $formData3['confirmPassword'];
						
					if (empty($currentPassword)) {
						$response['success'] = false;
						$response['currentPasswordAlert'] = '*Current password field is required.';
					} else {
						$response['currentPasswordAlert'] = ''; // Clear the alert message
					}

					if (empty($newPassword)) {
						$response['success'] = false;
						$response['newPasswordAlert'] = '*New password field is required.';
					} else {
						$response['newPasswordAlert'] = ''; // Clear the alert message
					}
						
					if (empty($confirmPassword)) {
						$response['success'] = false;
						$response['confirmPasswordAlert'] = '*New password confirm field is required.';
					} else {
						$response['confirmPasswordAlert'] = ''; // Clear the alert message
					}
						
					if ($response['success'] === true) {
							
						$currPasswordQuery = "SELECT * FROM users WHERE user_id = ?";
							
						if ($stmt = $conn->prepare($currPasswordQuery)) {
							$stmt->bind_param("s", $UserId); 
							$stmt->execute();
							$result = $stmt->get_result();

							if ($result->num_rows === 1) {
								$row = $result->fetch_assoc();
								$hashedPassword = $row['password'];

								if (password_verify($currentPassword, $hashedPassword)) {
										
									if (strlen($newPassword) < 8 || strlen($newPassword) > 16) {
										$response['success'] = false;
										$response['newPasswordAlert'] = '*New password should be 8 to 16 characters long.';
									} elseif (!preg_match('/[a-z]/', $newPassword) || !preg_match('/[A-Z]/', $newPassword)) {
										$response['success'] = false;
										$response['newPasswordAlert'] = '*New password must include both uppercase and lowercase letters.';
									} elseif ($newPassword !== $confirmPassword) {
										$response['success'] = false;
										$response['confirmPasswordAlert'] = '*The new password and retype password are not the same.';
									} else {
										$hashedNewPassword = password_hash($newPassword, PASSWORD_ARGON2I);					
										$updateUserPassword = "UPDATE users SET password = ? WHERE user_id = ?"; 
						
										if ($stmt = $conn->prepare($updateUserPassword)) {
											$stmt->bind_param("ss", $hashedNewPassword, $UserId);
											$stmt->execute();
											$stmt->close(); 
								
											$response['alertContent'] = true;
											$response['alertType'] = 'password_ch';
											$response['alert'] = "Your password was updated successfully.!";
										} else {
											$response = array(
												'success' => false,
												'alert' => 'Error in user password update statement',
												'error_page' => true
											);
										}
									}
								} else {
									$response['success'] = false;
									$response['currentPasswordAlert'] = '*Password is not correct.';
								}
							} else {
								$response = array(
									'success' => false,
									'alert' => 'This user not registered in the system',
									'error_page' => true
								);
							}
						} else {
							$response = array(
								'success' => false,
								'alert' => 'Error in user password retrieve statement',
								'error_page' => true
							);
						}
					}
				} else {
					$funcArray = firstStepButtonClick($buttonId, $buttonClass, $action);
			
					if(!isset($funcArray['else_alert'])) {
						if (($buttonId === 'delete_button' && $buttonClass === 'ajax-button' && $action === 'delete_acc') || ($buttonId === 'log_out_button' && $buttonClass === 'ajax-button' && $action === 'logout_acc')) {							
							$response['buttons'] = $funcArray['buttons'];
							$response['alertContent'] = $funcArray['alertContent'];
							$response['alert'] = $funcArray['alert'];
						} else {
							$response['buttons'] = $funcArray['buttons'];
						}
					} else {
						$response = array(
							'success' => false,
							'alert' => 'Error in button variable process',
							'error_page' => true
						);
					}
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
			$funcArray = firstStepButtonClick($buttonId, $buttonClass, $action);
			
			if(!isset($funcArray['else_alert'])) {
				if (($buttonId === 'delete_button') || ($buttonId === 'log_out_button')) {
					$response['buttons'] = $funcArray['buttons'];
					$response['alertContent'] = $funcArray['alertContent'];
					$response['alert'] = $funcArray['alert'];
				} else {
					$response['buttons'] = $funcArray['buttons'];
				}
			} else {
				$response = array(
					'success' => false,
					'alert' => 'Error in button variable process',
					'error_page' => true
				);
			}
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
        'alert' => 'Invalid request method.',
        'error_page' => true
    );
}


function firstStepButtonClick($buttonId, $buttonClass, $action) {
    $funcArray = array(); 

    if ($buttonId === 'prof_info' && $buttonClass === 'ajax-button' && $action === 'prof_info') {
        $settingButtonProcess = 'prof_info';
        $settingButtonId = 'prof_info';
        $funcArray['buttons'] = 'prof_info_btn';
	} elseif ($buttonId === 'email_ch' && $buttonClass === 'ajax-button' && $action === 'email_ch') {	
		$settingButtonProcess = 'email_ch';
        $settingButtonId = 'email_ch';
        $funcArray['buttons'] = 'email_ch_btn';
		$_SESSION['step1_completed'] = true;
		$_SESSION['process'] = 'setting_email_change';
		$_SESSION['sub_process'] = 'main flow';
    } elseif ($buttonId === 'password_ch' && $buttonClass === 'ajax-button' && $action === 'password_ch') {
        $settingButtonProcess = 'password_ch';
        $settingButtonId = 'password_ch';
        $funcArray['buttons'] = 'password_ch_btn';
    } elseif ($buttonId === 'log_out_button' && $buttonClass === 'ajax-button' && $action === 'logout_acc') {
        $settingButtonProcess = 'logout_acc';
        $settingButtonId = 'log_out_button';
        $funcArray['alertContent'] = true;
		$funcArray['alert'] = 'Would you like to log out of the system right now?';
        $funcArray['buttons'] = 'logout_acc_btn';
    } elseif ($buttonId === 'delete_button' && $buttonClass === 'ajax-button' && $action === 'delete_acc') {
        $settingButtonProcess = 'delete_acc';
        $settingButtonId = 'delete_button';
        $funcArray['alertContent'] = true;
		$funcArray['alert'] = 'Do you want to delete your account data?';
        $funcArray['buttons'] = 'delete_acc_btn';
    } else {
		$funcArray['else_alert'] = true;	
    }

    $_SESSION['settingButtonProcess'] = $settingButtonProcess;
    $_SESSION['settingButtonId'] = $settingButtonId;
    $_SESSION['settingBtnProcessStep1'] = 'complete';

    return $funcArray; 
}


header('Content-Type: application/json');
echo json_encode($response);
?>
