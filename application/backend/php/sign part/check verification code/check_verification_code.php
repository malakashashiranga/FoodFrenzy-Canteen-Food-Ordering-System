<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    
    $user_otp = $_POST['user_otp'];
    $e_mail = $_SESSION['e_mail'];

    $response['success'] = true;
	
    ob_start();
    include '/xampp/htdocs/FoodFrenzy/configuration/php/connect to database/con_server.php';
	$includedContent = ob_get_clean();
    
	$conn_status_data = json_decode($includedContent, true);
    $conn_status = $conn_status_data['connection_status'];	
	
	if ($conn_status === "successfully") {
		// Check if the OTP is expired
		$checkExpiryQuery = "SELECT otp FROM verify_codes 
						WHERE status = 'pending' AND email = '$e_mail'  
						AND ((date = CURDATE() AND time < DATE_SUB(NOW(), INTERVAL 3 MINUTE)) OR date < CURDATE())";
		$result = $conn->query($checkExpiryQuery);

	
		$checkExistQuery = "SELECT otp FROM verify_codes 
							WHERE status = 'pending' AND email = '$e_mail' ";
		$result2 = $conn->query($checkExistQuery);

		$incorrectOTP = true; // Assume OTP is incorrect by default

		if (!ctype_digit($user_otp) || strlen($user_otp) !== 8) { 
			$response = array(
				'success' => false,
				'alert' => 'Verification number must be an 8-digit number.',
				'showAlertContent' => true,
			);
		} elseif ($result->num_rows > 0) {
			// OTP is expired
			$response = array(
				'success' => false,
				'alert' => 'Verification code has expired. Click resend code.!',
				'showAlertContent' => true,
			);
		} elseif ($result2->num_rows > 0) {
			// OTP exists in the database, check if it matches
			while ($row = $result2->fetch_assoc()) {
				if ($row['otp'] === $user_otp) {
					$incorrectOTP = false;
					$response = array(
						'success' => true,
						'correctOTP' => true,
					);
					break;
				}
			}
			if ($incorrectOTP) {
				// OTP is incorrect
				$response = array(
					'success' => false,
					'alert' => 'Verification code is wrong.!',
					'showAlertContent' => true,
				);
			}
		} else {
			// No matching OTP found
			$response = array(
				'success' => false,
				'alert' => 'Verification code has expired. Click resend code.!',
				'showAlertContent' => true,
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
        'alert' => 'Invalid request method.',
        'error_page' => true
    );
}
if ($response['success'] === true) { 
    $_SESSION['user_otp'] = $user_otp;
}


header('Content-Type: application/json');
echo json_encode($response);
?>
