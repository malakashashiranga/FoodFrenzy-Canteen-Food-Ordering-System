<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    
    $_SESSION['userEmail'] = $_POST['email'];
    $_SESSION['userPassword'] = $_POST['passwordIn'];
	
    $response['success'] = true;
	
    if (empty($_SESSION['userEmail'])) {
        $response['success'] = false;
        $response['emailAlert'] = '*User email is required';
    } elseif (!filter_var($_SESSION['userEmail'], FILTER_VALIDATE_EMAIL)) {
        $response['success'] = false;
        $response['emailAlert'] = '*It seems an invalid email format';
    } else {
        $response['emailAlert'] = ''; // Clear the alert message
    }
	
    if (empty($_SESSION['userPassword'])) {
        $response['success'] = false;
        $response['passwordAlert'] = '*User password is required';
    } else {
        $response['passwordAlert'] = ''; 
    }
	
    if (!empty($_SESSION['userEmail']) && !empty($_SESSION['userPassword']) && filter_var($_SESSION['userEmail'], FILTER_VALIDATE_EMAIL)) {
        
		ob_start();
        include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/configuration/php/connect to database/con_server.php';
        $includedContent = ob_get_clean();
    
        $conn_status_data = json_decode($includedContent, true);
        $conn_status = $conn_status_data['connection_status'];

        if ($conn_status === "successfully") {
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $_SESSION['userEmail']);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if ($result->num_rows === 0) {
                $response = array(
                    'success' => false,
                    'alert' => 'This e-mail is not registered in the system!',
                    'showAlertContent' => true,
                    'emailAlert' => '',
                    'passwordAlert' => ''
                );
            } else {
                $row = $result->fetch_assoc();
                $hashedPassword = $row['password']; 
				$resultUserId = $row['user_id'];
                if (password_verify($_SESSION['userPassword'], $hashedPassword)) {
					$response = array(
						'success' => true, 
						'valid' => true,
					);
				} else {
					$response = array(
						'success' => false,
						'alert' => 'Password invalid!',
						'showAlertContent' => true,
						'emailAlert' => '',
						'passwordAlert' => ''
					);
				}
            }
			
			$checkDeleteAccount = "SELECT * FROM active_sessions WHERE user_id = ? 
									AND activeness = 'deactivated'";
									
			$stmt = $conn->prepare($checkDeleteAccount);
            $stmt->bind_param("s", $resultUserId);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if ($result->num_rows > 0) {
                
				$activenessValue = 'active';
				$updateLastActive = "UPDATE active_sessions SET activeness = ? WHERE user_id = ?";
            
				if ($stmt = $conn->prepare($updateLastActive)) {
					$stmt->bind_param("ss", $activenessValue, $resultUserId);
					$stmt->execute();
					$stmt->close(); 
				} else {
					$response = array(
						'success' => false,
						'alert' => 'Failed to update last active in login part.',
						'error_page' => true
					);
				}
			}
        } else {
            $response = array(
                'success' => false,
                'alert' => 'Database connection error.',
                'error_page' => true
            );
        }	
        $conn->close();
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
