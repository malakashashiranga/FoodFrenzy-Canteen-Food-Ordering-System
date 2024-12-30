<?php
require '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/vendors/php/mail sender/vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail_sender = new PHPMailer(true);

session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    $response['success'] = true;
    
    $replyMessage = capitalizeAfterDot(ucfirst($_POST['reply']));
    
    if (empty($replyMessage)) {
        $response['success'] = false;
        $response['replyMessageAlert'] = '*Reply message field is required.';
    } else {
        $response['replyMessageAlert'] = '';
    }
    
    if ($response['success'] === true) {
        
        ob_start();
        include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/configuration/php/connect to database/con_server.php';
        $includedContent = ob_get_clean();

        $conn_status_data = json_decode($includedContent, true);
        $conn_status = $conn_status_data['connection_status'];

        if ($conn_status === "successfully") {
            
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

                $mail_sender->setFrom($gmailSendername, 'FoodFrenzy');

                if (isset($_SESSION['pending_contact_form_details']['senderEmail'])) {
                    $recipientEmail = $_SESSION['pending_contact_form_details']['senderEmail'];
                } else {
                    throw new Exception("Sender email not found in session");
                }

                $recipientName = $_SESSION['pending_contact_form_details']['name'];
                $receivedDateTime = $_SESSION['pending_contact_form_details']['dateTime'];
                
                $mail_sender->addAddress($recipientEmail, $recipientName);
                $mail_sender->Subject = 'Reply to the contact form';
                $mail_sender->isHTML(true); 

                $mail_sender->Body = "Dear " . $recipientName . ",<br><br>We received the contact form you sent on " . $receivedDateTime . ". The reply is: \"" . $replyMessage . "\"<br><br>Thank you for using our service.";
                $mail_sender->AltBody = "Dear " . $recipientName . ",\n\nWe received the contact form you sent on " . $receivedDateTime . ". The reply is: \"" . $replyMessage . "\"\n\nThank you for using our service.";

                $mail_sender->send();
				
				
				$formNumber = $_SESSION['pending_contact_form_details']['formNumber'];
				
				$updateContactFormTable = "UPDATE contact_forms SET reply = ?, replyed_date = CURDATE(), replyed_time = CURTIME() WHERE number = ?";
				if ($stmt = $conn->prepare($updateContactFormTable)){
					$stmt->bind_param("si", $replyMessage, $formNumber);
					$stmt->execute();
					
					$response['alertContent'] = true;
					$response['alert'] = 'Reply email has been successfully sent.!';
				} else {
					$response = array(
						'success' => false,
						'alert' => 'Error in the updateWalletTable query.',
						'error_page' => true
					);
				}
                
                unset($_SESSION['pending_contact_form_details']);
                
            } catch (Exception $e) {
                $response = array(
                    'success' => false,
                    'alert' => 'An error occurred in email sending: ' . $e->getMessage(),
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
    }
} else {
    $response = array(
        'success' => false,
        'alert' => 'Invalid request method.',
        'error_page' => true
    );
}


function capitalizeAfterDot($str) {
    return preg_replace_callback('/\.\s*([a-z])/', function($match) {
        return '. ' . strtoupper($match[1]);
    }, $str);
}


header('Content-Type: application/json');
echo json_encode($response);
?>
