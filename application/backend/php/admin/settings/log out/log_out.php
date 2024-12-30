<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    $response['success'] = true;
    
    if (
        isset($_POST['buttonId']) &&
        isset($_POST['buttonClass']) &&
        isset($_SESSION['settingButtonProcess']) &&
        isset($_SESSION['settingButtonId']) &&
        isset($_SESSION['settingBtnProcessStep1'])
    ) {
        $buttonId = $_POST['buttonId'];
        $buttonClass = $_POST['buttonClass'];
        $prevSettingButtonProcess = $_SESSION['settingButtonProcess'];
        $prevSettingButtonId = $_SESSION['settingButtonId'];
        $settingBtnProcessStep1 = $_SESSION['settingBtnProcessStep1'];
        $UserId = $_SESSION['user_id'];
        
        ob_start();
        include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/configuration/php/connect to database/con_server.php';
        $includedContent = ob_get_clean();
    
        $conn_status_data = json_decode($includedContent, true);
        $conn_status = $conn_status_data['connection_status'];
                
        if ($conn_status === "successfully") {
        
            if (
                $buttonId === 'alert_second_btn' &&
                $buttonClass === 'alert_button' &&
                $prevSettingButtonProcess === 'logout_acc' &&
                $prevSettingButtonId === 'log_out_button' &&
                $settingBtnProcessStep1 === 'complete'
            ) {
				$tokenValidity = 'unvalid';
                $updateTokenValidity = "UPDATE users_last_login SET token_validity = ? WHERE user_id = ?";
    
                if ($stmt = $conn->prepare($updateTokenValidity)) {
                    $stmt->bind_param("ss", $tokenValidity, $UserId);
                    $stmt->execute();
                    $stmt->close(); 
					
					
					$declinedActive = 'declined';
					$declinedActiveness = "UPDATE active_sessions SET activeness = ? WHERE user_id = ?";
					
					if ($stmt = $conn->prepare($declinedActiveness)) {
						$stmt->bind_param("ss", $declinedActive, $UserId);
						$stmt->execute();
						$stmt->close(); 
					
						$response['alertContent'] = true;
						$response['alertType'] = 'logout_acc';
						
						} else {
							$response['success'] = false;
							$response['alert'] = 'Error in declinedActiveness statement';
							$response['error_page'] = true;
						}
                } else {
                    $response['success'] = false;
                    $response['alert'] = 'Error in user logout statement';
                    $response['error_page'] = true;
                }
            }
            $conn->close();
        } else {
            $response['success'] = false;
            $response['alert'] = 'Database connection error.';
            $response['error_page'] = true;
        }
    } else {
        $response['success'] = false;
        $response['alert'] = 'Error in process';
        $response['error_page'] = true;
    }
} else {
    $response['success'] = false;
    $response['alert'] = 'Invalid request method.';
    $response['error_page'] = true;
}

header('Content-Type: application/json');
echo json_encode($response);
?>
