<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['userID'])) {
        $userID = $_GET['userID'];
				
		ob_start();
        include '/xampp/htdocs/FoodFrenzy/configuration/php/connect to database/con_server.php';
        $includedContent = ob_get_clean();
        
        $conn_status_data = json_decode($includedContent, true);
        $conn_status = $conn_status_data['connection_status'];    
    
        if ($conn_status === "successfully") {			
			
			$retriveUserDetails = "SELECT u.photo_path, u.user_id, u.first_name, u.last_name, u.email, u.mobile_number, u.registered_date, u.registered_time, ul.last_sign_date, ul.last_sign_time, acs.last_response_date, acs.last_response_time, acs.activeness
									FROM users u
									INNER JOIN users_last_login ul ON u.user_id = ul.user_id
									INNER JOIN active_sessions acs ON u.user_id = acs.user_id
									WHERE u.user_id = ?";

			if ($stmt = $conn->prepare($retriveUserDetails)) {
				$stmt->bind_param("s", $userID); 
				$stmt->execute();
				$result = $stmt->get_result();
				
				if ($result->num_rows > 0) {
					
					$row = $result->fetch_assoc();

					$_SESSION['specific_user_details'] = array(
						'userPhotoPath' => $row['photo_path'],
						'userID' => strtoupper($row['user_id']),
						'userName' => ucfirst($row['first_name']) . ' ' . ucfirst($row['last_name']),
						'email' => $row['email'],
						'mobileNumber' => $row['mobile_number'],
						'registeredDateTime' => $row['registered_date']. ' / ' . $row['registered_time'],
						'lastSignDateTime' => $row['last_sign_date']. ' / ' . $row['last_sign_time'],
						'lastOnlineTime' => $row['last_response_date']. ' / ' . $row['last_response_time'],
						'accountStatus' => ucfirst($row['activeness'])
					);

					header('Location: /FoodFrenzy/application/frontend/php/pages/admin/users/user details/specific user details/specific_user_details.php');
				} else {
					$errorMessage = "No rows returned from database.";
					redirectToErrorPage($errorMessage);
				}

			} else {
				$errorMessage = "Error in retriveUserDetails query.";
				redirectToErrorPage($errorMessage);    	
			}
		} else {
			$errorMessage = "Database connection error.";
			redirectToErrorPage($errorMessage); 
		}
    } else {
		$errorMessage = "userID not provided in the URL.";
		redirectToErrorPage($errorMessage);    
    }
} else {
    $errorMessage = "Error in request method.";
    redirectToErrorPage($errorMessage);    
}


function redirectToErrorPage($errorMessage) {
    $pageLink = "/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html"; 
    $goBackURL = "/FoodFrenzy/application/frontend/php/pages/admin/users/user details/user list/users_details.php"; 

    header("Location: $pageLink?message=" . urlencode($errorMessage) . "&goBackURL=" . urlencode($goBackURL));
    exit;
}

?>
