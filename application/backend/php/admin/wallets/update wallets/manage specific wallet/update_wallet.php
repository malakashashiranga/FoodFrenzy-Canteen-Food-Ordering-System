<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
	$response['success'] = true;
    
	$transactionType = $_POST['transaction_type'];
	$cashPayment = $_POST['cash_payment'];
	
	
	if (!($transactionType === 'deposits' || $transactionType === 'withdraw' || !($transactionType === 'undefined'))) {
		$response['success'] = false;
		$response['transactionAlert'] = '*Transaction field is required.';
	} else {
		$response['transactionAlert'] = '';
	}
	
	if (empty($cashPayment)) {
		$response['success'] = false;
		$response['cashPaymentAlert'] = '*Cash payment field is required.';
	} elseif (!is_numeric($cashPayment)) {
		$response['success'] = false;
		$response['cashPaymentAlert'] = '*Invalid numeric value. Please enter a valid numeric value (e.g., 100.40).';
	} else {
		$response['cashPaymentAlert'] = '';
	}
	
	if ($response['success'] === true) {
				
		$walletUserId = $_SESSION['wallet_details']['wallet_user_id']; 
		$walletUserId = strtolower($walletUserId);
        $currentBalance = floatval($_SESSION['wallet_details']['current_balance']);

        if (($transactionType === 'withdraw') && ($cashPayment > $currentBalance || $currentBalance === 0)) {
            $response['alertContent'] = true;
			$response['alert'] = 'This process can\'t be processed because your account doesn\'t have enough money.!';
        } else {
			
			if ($transactionType === 'withdraw') {
				$newBalance = $currentBalance - $cashPayment;
			} elseif ($transactionType === 'deposits') {
				$newBalance = $currentBalance + $cashPayment;
			}

			ob_start();
			include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/configuration/php/connect to database/con_server.php';
			$includedContent = ob_get_clean();

			$conn_status_data = json_decode($includedContent, true);
			$conn_status = $conn_status_data['connection_status'];

			if ($conn_status === "successfully") {
				
				$updateWalletRecordsTable ="INSERT INTO wallet_records (wallet_id, payment, payment_method, past_balance, balance, date, time)
                                         SELECT uw.wallet_id, ?, ?, ?, ?, CURDATE(), CURTIME()
                                         FROM user_wallets uw
                                         WHERE uw.user_id = ?";
											
					if ($stmt = $conn->prepare($updateWalletRecordsTable)){
					$stmt->bind_param("dsdds",$cashPayment, $transactionType, $currentBalance, $newBalance, $walletUserId);
					$stmt->execute();
										
					} else {
						$response = array(
							'success' => false,
							'alert' => 'Error in the updateWalletRecordsTable query.',
							'error_page' => true
						);
					}
					
				$updateWalletTable = "UPDATE user_wallets SET current_balance = ? WHERE user_id = ?";
				if ($stmt = $conn->prepare($updateWalletTable)){
					$stmt->bind_param("ds", $newBalance, $walletUserId);
					$stmt->execute();
				} else {
					$response = array(
						'success' => false,
						'alert' => 'Error in the updateWalletTable query.',
						'error_page' => true
					);
				}
				$_SESSION['wallet_details']['current_balance'] = $newBalance;
				$response['alertContent'] = true;
				$response['alert'] = 'User wallet money ' .($transactionType). ' is successfully done.!';
				
			} else {
				$response = array(
					'success' => false,
					'alert' => 'Database connection error.',
					'error_page' => true
				);
			}
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
