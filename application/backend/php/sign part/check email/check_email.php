<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();

    $e_mail = $_POST['e_mail'];

    $response['success'] = true;
	
	ob_start();
    include '/xampp/htdocs/FoodFrenzy/configuration/php/connect to database/con_server.php';
	$includedContent = ob_get_clean();
    
	$conn_status_data = json_decode($includedContent, true);
    $conn_status = $conn_status_data['connection_status'];	
	
	if ($conn_status === "successfully") {
		
		if (empty($e_mail)) {
			$response['success'] = false;
			$response['eMailAlert'] = '*Email is required';
		} elseif (!filter_var($e_mail, FILTER_VALIDATE_EMAIL)) {
			$response['success'] = false;
			$response['eMailAlert'] = '*It seems an invalid email format';
		} else {
			if ($_SESSION['process'] !== 'setting_email_change') {
				// Use a prepared statement to check if the email exists
				$sql = "SELECT * FROM users WHERE email = ?";
				$stmt = $conn->prepare($sql);
				$stmt->bind_param("s", $e_mail);
				$stmt->execute();
				$result = $stmt->get_result();

				if ($_SESSION['process'] === 'sign up') {
					// For the sign-up process, check if the email is already registered.
					if ($result->num_rows > 0) {
						$response = array(
							'success' => false,
							'alert' => 'This e-mail already registered in the system.!',
							'showAlertContent' => true,
							'eMailAlert' => ''
						);
					}
				} elseif ($_SESSION['process'] === 'forget password') {
					// For the forget password process, check if the email is not registered.
					if ($result->num_rows === 0) {
						$response = array(
							'success' => false,
							'alert' => 'This e-mail not registered in the system.!',
							'showAlertContent' => true,
							'eMailAlert' => ''
						);
					}
				} else {
					$response = array(
						'success' => false,
						'alert' => 'Process invalidation.',
						'error_page' => true
					);
				}
				$stmt->close();
			} else {
				$UserId = $_SESSION['user_id'];

				$emailCheck = "SELECT * FROM users WHERE email = ? AND user_id != ?";
						
				if ($stmt = $conn->prepare($emailCheck)) {
					$stmt->bind_param("ss", $e_mail, $UserId);
					$stmt->execute();
					$result = $stmt->get_result();

					if ($result->num_rows > 0) {
						$response = array(
							'success' => false,
							'alert' => '*This e-mail is already registered in the system.!',
							'showAlertContent' => true,
							'eMailAlert' => ''
						);
					}
				} else {
					$response = array(
						'success' => false,
						'alert' => 'Error in email finder statement',
						'error_page' => true
					);
				}
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
} else {
    $response = array(
        'success' => false,
        'alert' => 'Invalid request method.',
        'error_page' => true
    );
}

if ($response['success'] === true) {
    $_SESSION['e_mail'] = $e_mail;
}


header('Content-Type: application/json');
echo json_encode($response);
?>
