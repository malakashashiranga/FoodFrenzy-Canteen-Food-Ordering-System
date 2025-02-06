<?php
require '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/vendors/php/Mail Sends/vendor/autoload.php';  

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    $response['success'] = true;

    $orderID = $_POST['order_id'];

    ob_start();
    include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/configuration/php/connect to database/con_server.php';
    $includedContent = ob_get_clean();

    $conn_status_data = json_decode($includedContent, true);
    $conn_status = $conn_status_data['connection_status'];

    if ($conn_status === "successfully") {

        $state = 'pending';
        $checkOrderId = "SELECT o.order_id, o.pay_method, o.ordered_date, o.ordered_time, u.email, u.first_name
						FROM orders o
						INNER JOIN cart c ON o.cart_no = c.cart_no
						INNER JOIN users u ON c.user_id = u.user_id
						WHERE o.order_id = ? AND o.state = ?";
        if ($stmt = $conn->prepare($checkOrderId)) {
            $stmt->bind_param("ss", $orderID, $state);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				$payMethod = $row['pay_method'];
				$orderSendDate = $row['ordered_date'];
				$orderSendTime = $row['ordered_time'];
				$recipientEmail = $row['email'];
				$userFirstName = ucfirst($row['first_name']);

                $checkOrderExpired = "SELECT order_id FROM orders WHERE order_id = ? AND (ordered_date < DATE_SUB(CURRENT_DATE(), INTERVAL 7 DAY) OR (ordered_date = DATE_SUB(CURRENT_DATE(), INTERVAL 7 DAY) AND ordered_time <= CURRENT_TIME()))";
                if ($stmt2 = $conn->prepare($checkOrderExpired)) {
                    $stmt2->bind_param("s", $orderID);
                    $stmt2->execute();
                    $result2 = $stmt2->get_result();

                    if ($result2->num_rows > 0) {
                        $updateState = "expired";
                        $updateStateQuery = "UPDATE orders SET state = ? WHERE order_id = ?";
                        if ($stmt3 = $conn->prepare($updateStateQuery)) {
                            $stmt3->bind_param("ss", $updateState, $orderID);
                            $stmt3->execute();
                            if ($stmt3->affected_rows > 0) {
								
								if ($payMethod === 'wallet') {
									$retriveWalletDetails = "SELECT u.user_id, uw.current_balance, uw.wallet_id, SUM(f.discount_price * cf.quantity) AS total_price
														FROM orders o
														INNER JOIN cart c ON o.cart_no = c.cart_no
														INNER JOIN users u ON c.user_id = u.user_id
														INNER JOIN user_wallets uw ON u.user_id = uw.user_id
														INNER JOIN cart_foods cf ON c.cart_no = cf.cart_no
														INNER JOIN foods f ON cf.food_number = f.food_number
														WHERE o.order_id = ?";
														
									if ($stmt = $conn->prepare($retriveWalletDetails)) {
										$stmt->bind_param("s", $orderID); 
										$stmt->execute();
										$result = $stmt->get_result();
										
										if ($result->num_rows > 0) {
											
											$row = $result->fetch_assoc();
											$userId = $row['user_id'];
											$walletCurrentBalance = $row['current_balance'];
											$walletId = $row['wallet_id'];
											$orderTotalPrice = $row['total_price'];
											
											$orderReturnMethod = 'expired_order_returns';
											
											$newBalance = $walletCurrentBalance + $orderTotalPrice;
											
											$updateWalletRecordsTable ="INSERT INTO wallet_records (wallet_id, payment, payment_method, past_balance, balance, date, time)
																		VALUES (?, ?, ?, ?, ?,  CURDATE(), CURTIME())";
											
											if ($stmt = $conn->prepare($updateWalletRecordsTable)){
												$stmt->bind_param("ddsdd",$walletId, $orderTotalPrice, $orderReturnMethod, $walletCurrentBalance, $newBalance);
												$stmt->execute();
												
												$updateWalletTable = "UPDATE user_wallets SET current_balance = ? WHERE user_id = ?";
												if ($stmt = $conn->prepare($updateWalletTable)){
													$stmt->bind_param("ds", $newBalance, $userId);
													$stmt->execute();
												} else {
													$response = array(
														'success' => false,
														'alert' => 'Error in updateWalletTable prepare statement.!',
														'error_page' => true
													);
												}
											
											} else {
												$response = array(
													'success' => false,
													'alert' => 'Error in updateWalletRecordsTable prepare statement.!',
													'error_page' => true
												);
											}
										}
									}
								}
								
								$status = 'expired';
								$sendEmailResult = sendEmail($orderID, $orderSendDate, $orderSendTime, $recipientEmail, $userFirstName, $status);
								
								if ($sendEmailResult['errorValue']) {
									$response = array(
										'success' => false,
										'alert' => $sendEmailResult['errorMessage'],
										'error_page' => true
									);
								} elseif ($sendEmailResult['successValue']) {
									$response['orderExpired'] = true;
									$response['alert'] = "Sorry, The order has been expired.!";
								}
                            } else {
                                $response = array(
                                    'success' => false,
                                    'alert' => 'Error updating order state as expired.!',
                                    'error_page' => true
                                );
                            }
                        } else {
                            $response = array(
                                'success' => false,
                                'alert' => 'Error in updateStateQuery prepare statement.!',
                                'error_page' => true
                            );
                        }
                    } else {
                        $updateState = "checked";
                        $updateStateQuery = "UPDATE orders SET state = ? WHERE order_id = ?";
						
                        if ($stmt4 = $conn->prepare($updateStateQuery)) {
                            $stmt4->bind_param("ss", $updateState, $orderID);
                            $stmt4->execute();
                            if ($stmt4->affected_rows > 0) {
								
								$insertFinishedOrder = "INSERT INTO finished_orders (order_id, finished_date, finished_time) VALUES (?, CURDATE(), CURTIME())";
								if ($stmt5 = $conn->prepare($insertFinishedOrder)) {
									$stmt5->bind_param("s",$orderID);
									$stmt5->execute();
									if ($stmt5->affected_rows > 0) {
										
										$status = 'confirmed';
										$sendEmailResult = sendEmail($orderID, $orderSendDate, $orderSendTime, $recipientEmail, $userFirstName, $status);
										
										if ($sendEmailResult['errorValue']) {
											$response = array(
												'success' => false,
												'alert' => $sendEmailResult['errorMessage'],
												'error_page' => true
											);
										} elseif ($sendEmailResult['successValue']) {
											$response['orderExpired'] = false;
											$response['alert'] = "The order has been confirmed.!";
										}
									}
								} else {
									$response = array(
										'success' => false,
										'alert' => 'Error inserting finished order.!',
										'error_page' => true
									);
								}
                            } else {
                                $response = array(
                                    'success' => false,
                                    'alert' => 'Error updating order state as checked.!',
                                    'error_page' => true
                                );
                            }
                        } else {
                            $response = array(
                                'success' => false,
                                'alert' => 'Error in updateStateQuery prepare statement.!',
                                'error_page' => true
                            );
                        }
                    }
                } else {
                    $response = array(
                        'success' => false,
                        'alert' => 'Error in checkOrderExpired prepare statement.!',
                        'error_page' => true
                    );
                }
            } else {
                $response = array(
                    'success' => false,
                    'alert' => 'Order ID is not correct.!',
                    'error_page' => true
                );
            }
        } else {
            $response = array(
                'success' => false,
                'alert' => 'Error in checkOrderId prepare statement.!',
                'error_page' => true
            );
        }
    } else {
        $response = array(
            'success' => false,
            'alert' => 'Database connection error.!',
            'error_page' => true
        );
    }
} else {
    $response = array(
        'success' => false,
        'alert' => 'Invalid request method.!',
        'error_page' => true
    );
}


function sendEmail($orderID, $orderSendDate, $orderSendTime, $recipientEmail, $userFirstName, $status) {
	
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

		$mail_sender->setFrom($gmailSendername, 'FoodFrenzy');
	
		$mail_sender->addAddress($recipientEmail, $userFirstName);
		$mail_sender->isHTML(true);
		
		$mail_sender->Subject = 'Your Order ' . $orderID . ' Details';
		
		if ($status === 'expired') {
			$mail_sender->Body = "Dear " . $userFirstName . ",<br><br>Your order placed on " . $orderSendDate . " / " . $orderSendTime . " has expired because it was not collected within 7 days. If you paid for the order using your wallet, the amount will be automatically refunded to your wallet.<br><br>Thank you for using our service.";
			$mail_sender->AltBody = "Dear " . $userFirstName . ",\n\nYour order placed on " . $orderSendDate . " / " . $orderSendTime . " has expired because it was not collected within 7 days. If you paid for the order using your wallet, the amount will be automatically refunded to your wallet.\n\nThank you for using our service.";
	 	} elseif ($status === 'confirmed') {
			$mail_sender->Body = "Dear " . $userFirstName . ",<br><br>Your order placed on " . $orderSendDate . " / " . $orderSendTime . " has been successfully confirmed and collected.<br><br>Thank you for using our service.";
			$mail_sender->AltBody = "Dear " . $userFirstName . ",\n\nYour order placed on " . $orderSendDate . " / " . $orderSendTime . " has been successfully confirmed and collected.\n\nThank you for using our service.";
		}

		$mail_sender->send();
		
		$successValue = true;
		$successMessage = "email send";
		
	
	} catch (Exception $e) {
		$errorValue = true;
        $errorMessage = "Email could not be sent. Mailer Error: {$mail_sender->ErrorInfo}";
	}
	
	$successValue = isset($successValue) ? $successValue : false;
	$successMessage = isset($successMessage) ? $successMessage : '';
	$errorValue = isset($errorValue) ? $errorValue : false;
	$errorMessage = isset($errorMessage) ? $errorMessage : '';
	
	return array(
        'successValue' => $successValue,
		'successMessage' => $successMessage,
        'errorValue' => $errorValue,
        'errorMessage' => $errorMessage
    );
	
}


header('Content-Type: application/json');
echo json_encode($response);
?>
