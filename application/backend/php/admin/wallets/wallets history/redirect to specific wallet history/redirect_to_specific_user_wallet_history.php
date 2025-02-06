<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['recordNumber'])) {
        $recordNumber = $_GET['recordNumber'];
				
		ob_start();
        include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/configuration/php/connect to database/con_server.php';
        $includedContent = ob_get_clean();
        
        $conn_status_data = json_decode($includedContent, true);
        $conn_status = $conn_status_data['connection_status'];    
    
        if ($conn_status === "successfully") {			
			
			$retriveSpecificWalletHistory = "SELECT u.photo_path, u.user_id, u.first_name, u.last_name, wr.payment_method, wr.past_balance, wr.payment, wr.balance, wr.date, wr.time
									FROM wallet_records wr
									INNER JOIN user_wallets uw ON wr.wallet_id = wr.wallet_id
									INNER JOIN users u ON uw.user_id = u.user_id
									WHERE wr.number = ?";
						
			if ($stmt = $conn->prepare($retriveSpecificWalletHistory)) {
				$stmt->bind_param("s", $recordNumber); 
				$stmt->execute();
				$result = $stmt->get_result();
				
				if ($result->num_rows > 0) {
					$row = $result->fetch_assoc();

					// Store user and order details in session
					$_SESSION['specific_wallet_history_details'] = array(
						'userPhotoPath' => $row['photo_path'],
						'userID' => strtoupper($row['user_id']),
						'userName' => ucfirst($row['first_name']) . ' ' . ucfirst($row['last_name']),
						'transactionMethod' => capitalizeAfterSpaceOrSpecialChar($row['payment_method']),
						'pastBalance' => 'Rs. '.$row['past_balance'],
						'transactionAmount' => 'Rs. '.$row['payment'],
						'newBalance' => 'Rs. '.$row['balance'],
						'transactionDate' => $row['date'],
						'transactionTime' => $row['time']
					);
					
					header('Location: /FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/wallets/wallets history/specific wallet history/specific_wallet_history.php');
					
				} else {
					$errorMessage = "No rows returned from database.";
					redirectToErrorPage($errorMessage);
				}

			} else {
				$errorMessage = "Error in retriveSpecificWalletHistory query.";
				redirectToErrorPage($errorMessage);    	
			}
		} else {
			$errorMessage = "Database connection error.";
			redirectToErrorPage($errorMessage); 
		}
    } else {
		$errorMessage = "Order ID not provided in the URL.";
		redirectToErrorPage($errorMessage);    
    }
} else {
    $errorMessage = "Error in request method.";
    redirectToErrorPage($errorMessage);    
}


function redirectToErrorPage($errorMessage) {
    $pageLink = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html"; 
    $goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/wallets/wallets history/wallet list/list_of_wallet_history.php"; 

    header("Location: $pageLink?message=" . urlencode($errorMessage) . "&goBackURL=" . urlencode($goBackURL));
    exit;
}

function capitalizeAfterSpaceOrSpecialChar($word) {
    $nameParts = preg_split("/[\s_]+/", $word);
    $capitalizedParts = array_map('ucwords', $nameParts);
    $capitalizedName = implode(' ', $capitalizedParts);

    $capitalizedName = ucfirst($capitalizedName);

    return $capitalizedName;
}

?>
