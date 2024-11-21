<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
	$response['success'] = true;
	$userID = $_SESSION['user_id'];
		
    ob_start();
	include '/xampp/htdocs/FoodFrenzy/configuration/php/connect to database/con_server.php';
	$includedContent = ob_get_clean();

	$conn_status_data = json_decode($includedContent, true);
	$conn_status = $conn_status_data['connection_status'];

	if ($conn_status === "successfully") {
			
		$checkActiveCart = "SELECT current_balance FROM user_wallets WHERE user_id = ?";
		if ($stmt = $conn->prepare($checkActiveCart)) {
			$stmt->bind_param("s", $userID);
			$stmt->execute();
			$result = $stmt->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				$currenBalance = $row['current_balance'];
							
				$response['currenBalance'] = 'Rs .'.$currenBalance;
				$response['userID'] = $userID;
							
			} else {
				$response = array(
					'success' => false,
					'alert' => 'There is no wallet for this user.!',
					'error_page' => true
				);
			}
			$stmt->close();
		} else {
			$response = array(
				'success' => false,
				'alert' => 'Error in checkActiveCart query.!',
				'error_page' => true
			);
		}
		
	} else {
		$response = array(
			'success' => false,
			'alert' => 'Database connection error.',
			'error_console' => true
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
