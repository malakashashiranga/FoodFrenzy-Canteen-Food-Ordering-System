<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['user_id'])) {
        $userID = strtolower($_GET['user_id']);
		
		ob_start();
        include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/configuration/php/connect to database/con_server.php';
        $includedContent = ob_get_clean();
        
        $conn_status_data = json_decode($includedContent, true);
        $conn_status = $conn_status_data['connection_status'];    
    
        if ($conn_status === "successfully") {			
			
			$retriveWalletUserdetails = "SELECT u.photo_path, u.first_name, u.last_name, uw.current_balance
								FROM users u
								INNER JOIN user_wallets uw ON u.user_id = uw.user_id
								WHERE u.user_id = ?";
						
			if ($stmt = $conn->prepare($retriveWalletUserdetails)) {
				$stmt->bind_param("s", $userID); 
				$stmt->execute();
				$result = $stmt->get_result();
				
				if ($result->num_rows > 0) {
					while ($row = $result->fetch_assoc()) {
						
						$_SESSION['wallet_details'] = array(
							'photoPath' => $row['photo_path'],
							'wallet_user_id' => strtoupper($userID),
							'userName' => ucfirst($row['first_name']) . ' ' . ucfirst($row['last_name']),
							'current_balance' => $row['current_balance']
						);
						header('Location: /FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/wallets/update wallets/manage specific wallet/manage_specific_wallet.php');
					}
				} else {
					$errorMessage = "There is no user with this user ID.";
					redirectToErrorPage($errorMessage); 
				}
			} else {
				$errorMessage = "Error in retriveWalletUserdetails query.";
				redirectToErrorPage($errorMessage);    	
			}
		} else {
			$errorMessage = "Database connection error.";
			redirectToErrorPage($errorMessage); 
		}
    } else {
		$errorMessage = "user ID not provided in the URL.";
		redirectToErrorPage($errorMessage);    
    }
} else {
    $errorMessage = "Error in request method.";
    redirectToErrorPage($errorMessage);    
}


function redirectToErrorPage($errorMessage) {
    $pageLink = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html"; 
    $goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/wallets/update wallets/wallet list/list_of_wallets.php"; 

    header("Location: $pageLink?message=" . urlencode($errorMessage) . "&goBackURL=" . urlencode($goBackURL));
    exit;
}

?>
