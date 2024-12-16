<?php 
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

$UserId = strtoupper($_SESSION['user_id']);

ob_start();
include '/xampp/htdocs/FoodFrenzy/configuration/php/connect to database/con_server.php';
$includedContent = ob_get_clean();
    
$conn_status_data = json_decode($includedContent, true);
$conn_status = $conn_status_data['connection_status'];
				
if ($conn_status === "successfully") {

	$getDataQuery = "SELECT first_name, last_name, email FROM users WHERE user_id = ?";
	if ($stmt = $conn->prepare($getDataQuery)) {
		$stmt->bind_param("s", $UserId);
		$stmt->execute();
		$stmt->bind_result($firstName, $lastName, $recipientEmail);
		$stmt->fetch();
		$stmt->close();

	} else {
		$errorMessage = "Error came from user data retrive in mail sending: " . $connection_status;
		$pageLink = "/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html"; 
		$goBackURL = "/FoodFrenzy/application/frontend/php/pages/register user/settings/settings.php"; 

		header("Location: $pageLink?message=" . urlencode($errorMessage) . "&goBackURL=" . urlencode($goBackURL));
		exit;
	}	

} else {
    $errorMessage = "Error came from database connection in mail sending: " . $connection_status;
	$pageLink = "/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html"; 
	$goBackURL = "/FoodFrenzy/application/frontend/php/pages/register user/settings/settings.php"; 

	header("Location: $pageLink?message=" . urlencode($errorMessage) . "&goBackURL=" . urlencode($goBackURL));
	exit;            
}
$conn->close();


require '/xampp/htdocs/FoodFrenzy/vendors/php/mail sender/vendor/autoload.php';  

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail_sender = new PHPMailer(true);

require_once('D:\my projects\config\config.php');

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
	
	$firstName = ucwords(strtolower($firstName)); 
    $lastName = ucwords(strtolower($lastName)); 
	
    $recipientName = $firstName . ' ' . $lastName;
    $mail_sender->addAddress($recipientEmail, $recipientName);

    $mail_sender->Subject = 'FoodFrenzy Account Deletion';
	$mail_sender->isHTML(true); // Set email format to HTML

	$mail_sender->Body = "Dear {$firstName} ({$UserId}),<br><br>We hope you're enjoying our service!<br>Your account is temporarily deactivated, but if you don't log in within the next 30 days, your account will be permanently deleted by the administrator.<br/>Thank you for using our service!<br><br>Best regards,<br>Your Service Team.";
	$mail_sender->AltBody = "Dear {$firstName} ({$UserId}),\n\nWe hope you're enjoying our service!\nYour account is temporarily deactivated, but if you don't log in within the next 30 days, your account will be permanently deleted by the administrator.\nThank you for using our service!\n\nBest regards,\nYour Service Team.";

    // Send the email
    $mail_sender->send();
    
	$_SESSION['alert'] = "Account deleted successfully. Check your e-mail for further details.!";
	
} catch (Exception $e) {
	$errorMessage = "Error came from delete message sending: " . $connection_status;
	$pageLink = "/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html"; 
	$goBackURL = "/FoodFrenzy/application/frontend/php/pages/register user/settings/settings.php"; 

	header("Location: $pageLink?message=" . urlencode($errorMessage) . "&goBackURL=" . urlencode($goBackURL));
	exit;
}

unset($_SESSION['user_id']);
unset($_SESSION['settingButtonProcess']);
unset($_SESSION['settingButtonId']);
unset($_SESSION['settingBtnProcessStep1']);
unset($_SESSION['active_session']);

header('Location: /FoodFrenzy/application/frontend/php/pages/sign part/sign in/sign_in.php');
exit;

?>
