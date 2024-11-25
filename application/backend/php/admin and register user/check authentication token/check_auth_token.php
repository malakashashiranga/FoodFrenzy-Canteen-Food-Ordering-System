<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if (isset($_COOKIE['auth_token'])) {
    $authTokenFromCookie = $_COOKIE['auth_token'];
	$activenessValue = 'active';

    ob_start(); 
    include '/xampp/htdocs/FoodFrenzy/configuration/php/connect to database/con_server.php';
    $includedContent = ob_get_clean();

    $conn_status_data = json_decode($includedContent, true);
    $conn_status = $conn_status_data['connection_status'];

    if ($conn_status === "successfully") {
		
		$validity = 'active';
		$tokenExistQuery = "SELECT auth_token, user_id FROM users_last_login WHERE auth_token = ? AND token_validity = ?";
		if ($stmt = $conn->prepare($tokenExistQuery)) {
			$stmt->bind_param("ss", $authTokenFromCookie, $validity);
			$stmt->execute();
			$result = $stmt->get_result();

			if ($result->num_rows === 0) {
				header('Location: /FoodFrenzy/application/frontend/php/pages/sign part/sign in/sign_in.php');
				exit;
			} else {
				$row = $result->fetch_assoc(); 
				$UserId = $row['user_id'];
				$_SESSION['user_id'] = $UserId;
			}

			$stmt->close();
		} else {
			$errorMessage = "Database tokenExistQuery error: " . $conn->error; 
			redirectToErrorPage($errorMessage);
		}
		
		$checkDeleteAccount = "SELECT * FROM active_sessions WHERE activeness = 'user_deleted_acc' AND user_id = ?";
		if ($stmt = $conn->prepare($checkDeleteAccount)) {
			$stmt->bind_param("s", $UserId);
			$stmt->execute();
			$result = $stmt->get_result();

			if ($result->num_rows > 0) {
				header('Location: /FoodFrenzy/application/frontend/php/pages/sign part/sign in/sign_in.php');
				exit;
			}

			$stmt->close();
		} else {
			$errorMessage = "Database checkDeleteAccount error: " . $conn->error; 
			redirectToErrorPage($errorMessage);
		}

		$tokenCheckQuery = "SELECT auth_token, user_id FROM users_last_login WHERE auth_token = ? AND (token_expiration_date = CURDATE() AND token_expiration_time > CURTIME() OR token_expiration_date > CURDATE())";
		
		if ($stmt = $conn->prepare($tokenCheckQuery)) {
            $stmt->bind_param("s", $authTokenFromCookie);
            $stmt->execute();
            $result = $stmt->get_result();
			$row = $result->fetch_assoc();
            $resultUserId = $row['user_id'];
            $resultAuthToken = $row['auth_token'];
			$stmt->close();
            
            if ($result->num_rows > 0) {
				
				$activeSessionId = null;
				if (!isset($_SESSION['active_session'])) { 
					$randomSessionID = createSessionId();

					$_SESSION['active_session'] = $randomSessionID;
					$activeSessionId = $_SESSION['active_session'];
					
					$checkSessionExist = "SELECT last_active_session_id FROM active_sessions WHERE last_active_session_id = ?";
					
					$stmt = $conn->prepare($checkSessionExist);
					$stmt->bind_param("s", $activeSessionId);
					$stmt->execute();
					$result = $stmt->get_result();
	
					while ($stmt->num_rows > 0) {
						$randomSessionID = createSessionId();
						$_SESSION['active_session'] = $randomSessionID;
						$activeSessionId = $_SESSION['active_session'];
	   
						$stmt->bind_param("s", $activeSessionId);
						$stmt->execute();
						$result = $stmt->get_result();
					}
					
					$stmt->close();
				
					$checkUserExist = "SELECT user_id FROM active_sessions WHERE user_id = ?";
					
					if ($stmt = $conn->prepare($checkUserExist)) {
						$stmt->bind_param("s", $resultUserId);
						$stmt->execute();
						$result = $stmt->get_result();
						$stmt->close();
						
						if ($result->num_rows === 0) {
							
							$insertLastActiveSessionId = "INSERT INTO active_sessions(user_id, last_active_session_id, created_date, created_time, activeness) VALUES (?, ?, CURDATE(), CURTIME(), ?)"; 
  
							if ($stmt = $conn->prepare($insertLastActiveSessionId)) {
								$stmt->bind_param("sss", $resultUserId, $activeSessionId, $activenessValue);
								$stmt->execute();
								$stmt->close(); 
								
							} else {
								$errorMessage = "Database insertLastActiveSessionId error: " . $conn->error; 
								redirectToErrorPage($errorMessage);
							}
						} else {
							
							$updateLastActiveSessionId = "UPDATE active_sessions SET last_active_session_id = ?, created_date = CURDATE(), created_time = CURTIME(), activeness = ? WHERE user_id = ?";
						
							if ($stmt = $conn->prepare($updateLastActiveSessionId)) {
								$stmt->bind_param("sss", $activeSessionId, $activenessValue, $resultUserId);
								$stmt->execute();
								$stmt->close(); 
							} else {
								$errorMessage = "Database updateLastActiveSessionId error: " . $conn->error; 
								redirectToErrorPage($errorMessage);
							}
						}
					} else {
						$errorMessage = "Database checkUserExist error: " . $conn->error; 
						redirectToErrorPage($errorMessage);
					}
				}
            } 
		} else {
            $errorMessage = "Database tokenCheckQuery error: " . $conn->error; 
            redirectToErrorPage($errorMessage);
        }
		
		$tokenExpireCheckQuery = "SELECT auth_token, user_id FROM users_last_login WHERE auth_token = ? AND (token_expiration_date = CURDATE() AND token_expiration_time < CURTIME() OR token_expiration_date < CURDATE())";
        
        if ($stmt = $conn->prepare($tokenExpireCheckQuery)) {
            $stmt->bind_param("s", $authTokenFromCookie);
            $stmt->execute();
            $stmt->bind_result($resultTokenresultToken, $resultUserId);
            
            if ($stmt->fetch() && (!isset($_SESSION['active_session']))) {
                header('Location: /FoodFrenzy/application/frontend/php/pages/sign part/sign in/sign_in.php');
                exit;
            }
        } else {        
            $errorMessage = "Database tokenExpireCheckQuery error: " . $conn->error; 
            redirectToErrorPage($errorMessage);        
        }
	} else {    
        $errorMessage = "Database connection failed: " . $connection_status;
        redirectToErrorPage($errorMessage);        
    }
	$conn->close();

} else {
    header('Location: /FoodFrenzy/application/frontend/php/pages/sign part/sign in/sign_in.php');
    exit;
}

function redirectToErrorPage($errorMessage) {
    $pageLink = "/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html"; 
    $goBackURL = "/FoodFrenzy/application/frontend/php/pages/sign part/sign in/sign_in.php"; 

    header("Location: $pageLink?message=" . urlencode($errorMessage) . "&goBackURL=" . urlencode($goBackURL));
    exit;
}


function createSessionId() {
	$randomSessionID = bin2hex(random_bytes(256));
	return $randomSessionID;
}

?>








