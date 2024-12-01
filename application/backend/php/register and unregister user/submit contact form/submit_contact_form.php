<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    $response['success'] = true;

    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phone_number'];
    $message = $_POST['message'];
	
    if (empty($fullName)) {
        $response['success'] = false;
        $response['firstNameAlert'] = '*Full name is required.';
    } elseif (preg_match('/\d/', $fullName)) {
        $response['success'] = false;
        $response['firstNameAlert'] = '*Full name should not contain numbers.';
    } else {
        $response['firstNameAlert'] = ''; 
    }

    if (empty($email)) {
		$response['success'] = false;
		$response['eMailAlert'] = '*Email is required';
	} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$response['success'] = false;
		$response['eMailAlert'] = '*It seems an invalid email format';
	} else {
		$response['eMailAlert'] = ''; 
    }

    if (empty($phoneNumber)) {
        $response['success'] = false;
        $response['mobileNumberAlert'] = '*Mobile number is required.';
    } elseif (!preg_match('/^\d{10}$/', $phoneNumber)) {
        $response['success'] = false;
        $response['mobileNumberAlert'] = '*Mobile number should have 10 digits.';
    } else {
        $response['mobileNumberAlert'] = ''; 
    }

    if (empty($message)) {
        $response['success'] = false;
        $response['messageAlert'] = '*Home address is required.';
    } else {
        $response['messageAlert'] = ''; // Clear the alert message
    }
	
	if ($response['success'] === true) {
		ob_start();
		include '/xampp/htdocs/FoodFrenzy/configuration/php/connect to database/con_server.php';
		$includedContent = ob_get_clean();

		$conn_status_data = json_decode($includedContent, true);
		$conn_status = $conn_status_data['connection_status'];

		if ($conn_status === "successfully") {
		
			$userChecking = "SELECT * FROM users WHERE email = ?";
			
			if ($stmtCheck = $conn->prepare($userChecking)) {
				$stmtCheck->bind_param("s", $email);
				$stmtCheck->execute();
				
				$result = $stmtCheck->get_result();
    
				if ($result->num_rows > 0) { 
					$row = $result->fetch_assoc();
					$userID = $row['user_id'];
				} else {
					$userID = NULL;
				}
				$stmtCheck->close(); 
			} else {
				$response = array(
					'success' => false,
					'alert' => 'Error in userChecking query.!',
					'error_page' => true
				);
			}

			$insertNewContactForm = "INSERT INTO contact_forms (user_id, name, sender_email, sender_phone_number, message, recieved_date, recieved_time) VALUES (?, ?, ?, ?, ?, CURDATE(), CURTIME())";
			if ($stmtCheck = $conn->prepare($insertNewContactForm)) {
				$stmtCheck->bind_param("sssss", $userID, $fullName, $email, $phoneNumber, $message);
				$stmtCheck->execute();

				if ($stmtCheck->affected_rows > 0) {
					$response['alertContent'] = true;
					$response['alert'] = 'The message has been successfully submitted. We will reply to you via email soon.!';
				} else {
					$response = array(
						'success' => false,
						'alert' => 'Error in contact form submitting.!',
						'error_page' => true
					);
				}
				$stmtCheck->close();
			
			} else {
				$response = array(
					'success' => false,
					'alert' => 'Error in insertNewContactForm query.!',
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
