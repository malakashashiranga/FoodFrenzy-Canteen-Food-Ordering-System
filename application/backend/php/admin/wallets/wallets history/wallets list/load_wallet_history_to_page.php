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
        include '/xampp/htdocs/FoodFrenzy/configuration/php/connect to database/con_server.php';
        $includedContent = ob_get_clean();

        $conn_status_data = json_decode($includedContent, true);
        $conn_status = $conn_status_data['connection_status'];

        if ($conn_status === "successfully") {
			
			if ($searchTerm === '') {
				$retrieveWalletHistory = "SELECT u.user_id, wr.payment_method, wr.date, wr.past_balance, wr.payment, wr.number
										  FROM wallet_records wr
										  INNER JOIN user_wallets uw ON wr.wallet_id = uw.wallet_id
										  INNER JOIN users u ON uw.user_id = u.user_id
										  ORDER BY wr.date DESC, wr.time DESC";
			} else {
				$retrieveWalletHistory = "SELECT u.user_id, wr.payment_method, wr.date, wr.past_balance, wr.payment, wr.number
										  FROM wallet_records wr
										  INNER JOIN user_wallets uw ON wr.wallet_id = uw.wallet_id
										  INNER JOIN users u ON uw.user_id = u.user_id
										  WHERE u.user_id LIKE ?
										  ORDER BY wr.date DESC, wr.time DESC";
			}
			
			if ($stmt = $conn->prepare($retrieveWalletHistory)) {
			
				if ($searchTerm !== '') {
					$searchNewTerm = '%' . $searchTerm . '%';
					$stmt->bind_param("s", $searchNewTerm);
				}
				
				$stmt->execute();
				$result = $stmt->get_result();
				
				if ($result->num_rows > 0) {
					$walletHistory = array(); // Initialize the array
					while ($row = $result->fetch_assoc()) {
												
						$transactionDate = $row['date'];
						if ($transactionDate === null) {
							$transactionDate = "-";
						}
						
						$transactionType = $row['payment_method'];
						if ($transactionType === null) {
							$transactionType = "-";
						}
						
						$walletHistory = array(
							'recordNumber' => $row['number'],
							'userID' => strtoupper($row['user_id']),
							'paymentMethod' => capitalizeAfterSpaceOrSpecialChar($transactionType),
							'transactionDate' => $transactionDate,
							'pastBalance' => 'Rs. '.$row['past_balance'],
							'transactionAmount' => 'Rs. '.$row['payment']
						);

						$walletHistoryDetails[] = $walletHistory; 
					}
					$response['walletHistoryDetails'] = $walletHistoryDetails; 
				} else {
					if ($searchTerm === '') {
						$response['noFoods'] = true;
						$response['content'] = "No wallet history yet!";
					} else {
						$response['noFoods'] = true;
						$response['content'] = "No user ID found with the id '$searchTerm' in wallet history";
					}
				}
				$stmt->close();
			} else {
                $response = array(
                    'success' => false,
                    'alert' => 'Error in retriveWalletHistory query.',
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
			'alert' => 'Error in selection type.',
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

function capitalizeAfterSpaceOrSpecialChar($word) {
    $nameParts = preg_split("/[\s_]+/", $word);
    $capitalizedParts = array_map('ucwords', $nameParts);
    $capitalizedName = implode(' ', $capitalizedParts);

    $capitalizedName = ucfirst($capitalizedName);

    return $capitalizedName;
}

header('Content-Type: application/json');
echo json_encode($response);
?>
