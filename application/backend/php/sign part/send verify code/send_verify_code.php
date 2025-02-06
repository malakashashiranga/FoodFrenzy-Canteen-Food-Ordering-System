<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

require '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/vendors/php/Mail Sends/vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail_sender = new PHPMailer(true);

require_once('D:/my projects/config/config.php');

$gmailSendername = GMAIL_USERNAME;
$gmailPassword = GMAIL_PASSWORD;

try {
    $mail_sender->isSMTP();
    $mail_sender->Host = 'smtp.gmail.com';
    $mail_sender->SMTPAuth = true;
    $mail_sender->Username = $gmailSendername;
    $mail_sender->Password = $gmailPassword;
    $mail_sender->SMTPSecure = 'tls';
    $mail_sender->Port = 587;

    // Set the 'from' address and name
    $mail_sender->setFrom($gmailSendername, 'FoodFrenzy');

    $recipientEmail = $_SESSION['e_mail'];
	
	$firstName = ucwords(strtolower($_SESSION['f_name']));
	$lastName = ucwords(strtolower($_SESSION['l_name']));
	
    $recipientName = $firstName . ' ' . $lastName;
    
    if (!empty($recipientEmail) && !empty($recipientName)) {
        $mail_sender->addAddress($recipientEmail, $recipientName);
    } else {
        $errorMessage = "Process connectivity failed: " . $connection_status;
		$pageLink = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html"; 
		$goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/sign part/sign in/sign_in.php"; 

		header("Location: $pageLink?message=" . urlencode($errorMessage) . "&goBackURL=" . urlencode($goBackURL));
		exit;
    }

    $otpCode = $_SESSION['otp'];

    $mail_sender->Subject = 'Your OTP Code';
	$mail_sender->isHTML(true); // Set email format to HTML

	$mail_sender->Body = "Dear " . $firstName . ",<br><br> Your " . $_SESSION['process'] . " verification code is: " . $otpCode . "<br>Keep note that, this verification code is valid for a single use and expires after 3 minutes.<br><br>Thank you for using our service.<br><br>(This is an auto-generated email. Please do not reply.)";

	// Optionally, you can also provide a plain text version of the email content
	$mail_sender->AltBody = "Dear " . $firstName . ",\n\nYour " . $_SESSION['process'] . " verification code is: " . $otpCode . "\nKeep note that, this verification code is valid for a single use and expires after 3 minutes.\n\nThank you for using our service.\n\n(This is an auto-generated email. Please do not reply.)";

    // Send the email
    $mail_sender->send();
	
	
	if ($_SESSION['sub_process'] === "main flow") {
		$_SESSION['step2_completed'] = true;
		header ("Location: /FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/sign part/otp insert part/get_verify_code.php");
		exit;
	} else {
		$_SESSION['sub_process'] = "main flow";
		$response = array();
		$response['success'] = false;
		
		$response = array(
			'success' => true,
			'alert' => 'Verification code has been resent.!',
			'showAlertContent' => true
		);
	}
	
	
	
} catch (Exception $e) {
	$errorMessage = "Error came from verification code sending: " . $connection_status;
	$pageLink = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html"; 
	$goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/sign part/sign in/sign_in.php"; 

	header("Location: $pageLink?message=" . urlencode($errorMessage) . "&goBackURL=" . urlencode($goBackURL));
	exit;
}

header('Content-Type: application/json');
echo json_encode($response);
?>
