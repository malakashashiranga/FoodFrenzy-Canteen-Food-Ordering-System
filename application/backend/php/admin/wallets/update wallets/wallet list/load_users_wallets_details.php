<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    $response['success'] = true;
	
	if (isset($_POST['searchTerm'])) {
		$searchTerm = $_POST['searchTerm'];

		ob_start();
		include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/configuration/php/connect to database/con_server.php';
		$includedContent = ob_get_clean();

		$conn_status_data = json_decode($includedContent, true);
		$conn_status = $conn_status_data['connection_status'];

		if ($conn_status === "successfully") {

			$userType = 'customer';
			
			if ($searchTerm !== ' ') {
				$retriveWalletdetails = "SELECT u.user_id, u.first_name, u.last_name, uw.current_balance, wr.payment_method, IFNULL(wr.date, '-') AS date
										FROM users u
										INNER JOIN user_wallets uw ON u.user_id = uw.user_id
										INNER JOIN (
											SELECT wallet_id, MAX(date) AS max_date, MAX(time) AS max_time
											FROM wallet_records
											GROUP BY wallet_id
										) max_wr ON uw.wallet_id = max_wr.wallet_id
										LEFT JOIN wallet_records wr ON max_wr.wallet_id = wr.wallet_id AND max_wr.max_date = wr.date AND max_wr.max_time = wr.time
										WHERE u.user_type = ? AND u.user_id LIKE ?
										ORDER BY wr.date DESC, wr.time DESC";				
			} else {
				$retriveWalletdetails = "SELECT u.user_id, u.first_name, u.last_name, uw.current_balance, wr.payment_method, IFNULL(wr.date, '-') AS date
										FROM users u
										INNER JOIN user_wallets uw ON u.user_id = uw.user_id
										INNER JOIN (
											SELECT wallet_id, MAX(date) AS max_date, MAX(time) AS max_time
											FROM wallet_records
											GROUP BY wallet_id
										) max_wr ON uw.wallet_id = max_wr.wallet_id
										LEFT JOIN wallet_records wr ON max_wr.wallet_id = wr.wallet_id AND max_wr.max_date = wr.date AND max_wr.max_time = wr.time
										WHERE u.user_type = ?
										ORDER BY wr.date DESC, wr.time DESC";
			}


			if ($stmtCheck = $conn->prepare($retriveWalletdetails)) {
				if ($searchTerm !== ' ') {
					$searchTermPattern = "%$searchTerm%";
					$stmtCheck->bind_param("ss", $userType, $searchTermPattern);
				} else {
					$stmtCheck->bind_param("s", $userType);
				}
				$stmtCheck->execute();
				$resultCheck = $stmtCheck->get_result();

				$totalWalletBalance = 0; // Initialize total balance variable

				if ($resultCheck->num_rows > 0) {
					$walletDetails = array();
					while ($walletDetailsRow = $resultCheck->fetch_assoc()) {
						$transactionMethod = '';
						if ($walletDetailsRow['payment_method'] === 'deposits') {
							$transactionMethod = 'Deposit';
						} elseif ($walletDetailsRow['payment_method'] === 'expired_order_returns') {
							$transactionMethod = 'Order return';
						} elseif ($walletDetailsRow['payment_method'] === 'withdraw') {
							$transactionMethod = 'Withdraw';
						} else {
							$transactionMethod = '-';
						}
						
						$transactionDate = is_null($walletDetailsRow['date']) ? '-' : $walletDetailsRow['date'];

						// Calculate total wallet balance
						$totalWalletBalance += $walletDetailsRow['current_balance'];

						$walletDetails[] = array(
							'user_id' => strtoupper($walletDetailsRow['user_id']),
							'user_name' => ucfirst($walletDetailsRow['first_name']) . ' ' . ucfirst($walletDetailsRow['last_name']),
							'transaction_date' => $transactionDate,
							'transaction_method' => $transactionMethod,
							'current_balance' => 'Rs ' . $walletDetailsRow['current_balance']
						);
					}
					$response['walletDetails'] = $walletDetails;
					$response['totalWalletBalance'] = 'Rs ' .$totalWalletBalance. '.00';
				} else {
					if ($searchTerm !== ' ') {
						$response['noWallets'] = true;
						$response['content'] = "Users not registered to the system yet in this term '$searchTerm'";
					} else {
						$response['noWallets'] = true;
						$response['content'] = "Users not registered to the system yet!";
					}
				}
				$stmtCheck->close();
			} else {
				$response = array(
					'success' => false,
					'alert' => 'Error preparing the query.',
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
		$conn->close();
	} else {
		$response = array(
			'success' => false,
			'alert' => 'Search term is not set.',
			'error_page' => true
		);
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
