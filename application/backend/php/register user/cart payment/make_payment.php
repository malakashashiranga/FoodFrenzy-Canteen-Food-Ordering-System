<?php
require '/xampp/htdocs/FoodFrenzy/vendors/php/PDF_creator/vendor/autoload.php'; 
require '/xampp/htdocs/FoodFrenzy/vendors/php/mail sender/vendor/autoload.php';  

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
	$response['success'] = true;
	$cashConfirm = false;
	$walletUpdate = false;
    
	$payType = isset($_POST['payType']) ? $_POST['payType'] : '';
	
	if ($payType === 'wallet' || $payType === 'cash') {
		
		$userID = $_SESSION['user_id'];
		$activeValue = 'active';
		$availability = 'available';
        
		if ($response['success'] === true) {
			
			ob_start();
			include '/xampp/htdocs/FoodFrenzy/configuration/php/connect to database/con_server.php';
			$includedContent = ob_get_clean();

			$conn_status_data = json_decode($includedContent, true);
			$conn_status = $conn_status_data['connection_status'];

			if ($conn_status === "successfully") {
			
				$checkActiveCart = "SELECT cart_no FROM cart WHERE user_id = ? AND status = ?";
				if ($stmt = $conn->prepare($checkActiveCart)) {
					$stmt->bind_param("ss", $userID, $activeValue);
					$stmt->execute();
					$result = $stmt->get_result();

					if ($result->num_rows > 0) {
						$row = $result->fetch_assoc();
						$cartNo = $row['cart_no'];
							
						if ($payType === 'wallet') {
							
							$retrieveBalanceAndTotal = "SELECT uw.current_balance, SUM(f.discount_price * cf.quantity) AS total_price
													FROM user_wallets uw
													INNER JOIN cart c ON uw.user_id = c.user_id
													INNER JOIN cart_foods cf ON c.cart_no = cf.cart_no
													INNER JOIN foods f ON cf.food_number = f.food_number
													WHERE uw.user_id = ? AND c.status = ? AND f.availability = ?
													GROUP BY uw.current_balance
													HAVING uw.current_balance >= total_price";

							if ($stmtBalance = $conn->prepare($retrieveBalanceAndTotal)) {
								$stmtBalance->bind_param("sss", $userID, $activeValue, $availability);
								$stmtBalance->execute();
								$resultBalance = $stmtBalance->get_result();
						
								if ($resultBalance->num_rows > 0) {
									$rowBalance = $resultBalance->fetch_assoc();
									$currentBalance = $rowBalance['current_balance'];
									$totalPrice = $rowBalance['total_price'];
									
									$newCurrentBalance = $currentBalance - $totalPrice;
									$transactionType = 'withdraw';
									
									$updateWalletRecordsTable ="INSERT INTO wallet_records (wallet_id, payment, payment_method, past_balance, balance, date, time)
															SELECT uw.wallet_id, ?, ?, ?, ?, CURDATE(), CURTIME()
															FROM user_wallets uw
															WHERE uw.user_id = ?";
											
									if ($stmt = $conn->prepare($updateWalletRecordsTable)){
										$stmt->bind_param("dsdds",$totalPrice, $transactionType, $currentBalance, $newCurrentBalance, $userID);
										$stmt->execute();
										
										$updateWalletTable = "UPDATE user_wallets SET current_balance = ? WHERE user_id = ?";
										if ($stmt = $conn->prepare($updateWalletTable)){
											$stmt->bind_param("ds", $newCurrentBalance, $userID);
											$stmt->execute();
											
											$walletUpdate = true;
											
										} else {
											$response = array(
												'success' => false,
												'alert' => 'Error in the updateWalletTable query.',
												'error_page' => true
											);
										}
									} else {
										$response = array(
											'success' => false,
											'alert' => 'Error in the updateWalletRecordsTable query.',
											'error_page' => true
										);
									}
								} else {
									$response = array(
										'success' => false,
										'alert' => '*Insufficient funds in your wallet.',
										'walletPayAlert' => true
									);
								}
							} else {
								$response = array(
									'success' => false,
									'alert' => 'Error in retrieveBalanceAndTotal query.!',
									'error_page' => true
								);
							}
								
						} elseif ($payType === 'cash') {
							$cashConfirm = true;
						}
						if (($cashConfirm === true || $walletUpdate === true) && $response['success'] === true) {
							
							$cartStatus = 'checkout';
							$updateCartStatus = "UPDATE cart SET status = ? WHERE user_id = ? AND cart_no = ?";

							if ($stmtUpdate = $conn->prepare($updateCartStatus)) {
								$stmtUpdate->bind_param("ssi", $cartStatus, $userID, $cartNo);
								$stmtUpdate->execute();

								if ($stmtUpdate->affected_rows > 0) {
									$orderID = '#' . generateRandomOrderID($conn);
									$orderState = 'pending';
									
									$insertOrder = "INSERT INTO orders (order_id, cart_no, ordered_date, ordered_time, pay_method, state) VALUES (?, ?, CURDATE(), CURTIME(), ?, ?)";
									if ($stmtInsert = $conn->prepare($insertOrder)) {
										$stmtInsert->bind_param("siss", $orderID, $cartNo, $payType, $orderState);
										$stmtInsert->execute();

										if ($stmtInsert->affected_rows > 0) {
											
											$_SESSION['cartNo'] = $cartNo;
											$_SESSION['payType'] = $payType;
											$_SESSION['orderID'] = $orderID;
											
											$pdfDetailsResult = pdfDetailsRetrive($conn, $userID, $cartNo, $orderID, $payType);
											if ($pdfDetailsResult['errorValue']) {
												$response = array(
													'success' => false,
													'alert' => $pdfDetailsResult['errorMessage'],
													'error_page' => true
												);
											} else {
												$pdfDetails = $pdfDetailsResult['pdfDetails'];
												$totalPrice = $pdfDetailsResult['totalPrice'];
												$payType = $pdfDetailsResult['payType'];
											}


										 	$pdfSendResult = mailSender($pdfDetails, $orderID, $userID, $totalPrice, $payType);
											if ($pdfSendResult['errorValue']) {
												$response = array(
													'success' => false,
													'alert' => $pdfSendResult['errorMessage'],
													'error_page' => true
												);
											} elseif ($pdfSendResult['successValue']) {
												$response['successMessage'] = $pdfSendResult['successMessage'];
											}
										}
									$stmtInsert->close();
									} else {
										$response = array(
											'success' => false,
											'alert' => 'Error in preparing the order insert statement.',
											'error_page' => true
										);
									}	
								} 
								$stmtUpdate->close();
							} else {
								$response = array(
									'success' => false,
									'alert' => 'Error in updateCartStatus query.!',
									'error_page' => true
								);
							}	
						}		
					}
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
					'error_page' => true
				);
			}
		}
	} else {
		$response = array(
			'success' => false,
			'alert' => '* Payment method is not selected *',
			'payError' => true
		);
	}
} else {
     $response = array(
        'success' => false,
        'alert' => 'Invalid request method.',
        'error_page' => true
    );
}


function generateRandomOrderID($conn) {
    $randomNumber = mt_rand(100000000, 999999999);

    $checkDuplicate = "SELECT COUNT(*) as count FROM orders WHERE order_id = ?";
    if ($stmtCheck = $conn->prepare($checkDuplicate)) {
        $orderID = $randomNumber;
        $stmtCheck->bind_param("s", $orderID);
        $stmtCheck->execute();
        $result = $stmtCheck->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            if ($row['count'] > 0) {
                return generateRandomOrderID($conn); // Call recursively if duplicate
            } else {
                return $orderID;
            }
        }
        $stmtCheck->close();
    }
    return $randomNumber;
}


function pdfDetailsRetrive($conn, $userID, $cartNo, $orderID, $payType) {
    $pdfDetails = array();
    
    $errorValue = false;
    $errorMessage = "";
	$totalPrice = 0;
    
	$availability = 'available';
    $retrivePriceDetails = "SELECT SUM(f.discount_price * cf1.quantity) AS total_price
                            FROM cart_foods cf1
                            INNER JOIN foods f ON cf1.food_number = f.food_number
                            INNER JOIN cart c ON c.cart_no = cf1.cart_no
                            INNER JOIN orders o ON c.cart_no = o.cart_no
                            WHERE  c.cart_no = ? AND o.order_id = ? AND f.availability = ?";
    
    if ($stmtBalance = $conn->prepare($retrivePriceDetails)) {
        $stmtBalance->bind_param("sss", $cartNo, $orderID, $availability);
        $stmtBalance->execute();
        $resultBalance = $stmtBalance->get_result();
        
        if ($resultBalance) {
            if ($resultBalance->num_rows > 0) {
                $rowBalance = $resultBalance->fetch_assoc();
                $totalPrice = $rowBalance['total_price'];
            }
        } else {
            $errorValue = true;
            $errorMessage = "Error in retrivePriceDetails query.";
        }
    } else {
        $errorValue = true;
        $errorMessage = "Error preparing retrivePriceDetails query.";
    }
    
    $retrivePdfDetails = "SELECT u.first_name, u.last_name, u.email, GROUP_CONCAT(f.food_name) AS food_names, GROUP_CONCAT(cf.quantity) AS quantities
                          FROM cart AS c
                          INNER JOIN users AS u ON c.user_id = u.user_id
                          INNER JOIN cart_foods AS cf ON c.cart_no = cf.cart_no
                          INNER JOIN foods AS f ON cf.food_number = f.food_number
                          WHERE c.user_id = ? AND c.cart_no = ?
                          GROUP BY c.user_id, c.cart_no, u.first_name, u.last_name";
    
    if ($stmt = $conn->prepare($retrivePdfDetails)) {
        $stmt->bind_param("ss", $userID, $cartNo);
        $stmt->execute();
        $stmt->bind_result($firstName, $lastName, $email, $foodNames, $quantities);
        
        while ($stmt->fetch()) {
            $foodNamesArray = explode(',', $foodNames);
            $quantitiesArray = explode(',', $quantities);
            
            $foods = array();
            for ($i = 0; $i < count($foodNamesArray); $i++) {
                $foods[] = array(
                    'food_name' => $foodNamesArray[$i],
                    'quantity' => $quantitiesArray[$i]
                );
            }
            
            $pdfDetails[] = array(
                'user_name' => ucfirst($firstName) . ' ' . ucfirst($lastName),
                'email' => $email,
                'foods' => $foods
            );
        }
        
        if ($payType === 'wallet') {
            $payType = 'Paid using wallet';
        } elseif ($payType === 'cash') {
            $payType = 'Will be paid using cash';
        }
    } else {
        $errorValue = true;
        $errorMessage = "Error preparing retrivePdfDetails query.";
    }
    
    return array(
        'pdfDetails' => $pdfDetails,
        'totalPrice' => $totalPrice,
        'payType' => $payType,
        'errorValue' => $errorValue,
        'errorMessage' => $errorMessage
    );
}


function mailSender($pdfDetails, $orderID, $userID, $totalPrice, $payType){
	
	$errorValue = false;
    $errorMessage = "";
	$successValue = false;
	$successMessage = "";
	
	$foodDetails = array();
	foreach ($pdfDetails as $userDetails) {
		$recipientName = $userDetails['user_name'];
		$foods = $userDetails['foods'];
		$email = $userDetails['email'];
	
	
		$foodDetailsString = '';
		foreach ($foods as $food) {
			$foodName = ucfirst($food['food_name']);
			$quantity = $food['quantity'];
			$foodDetailsString .= "$foodName - $quantity\n";
		}
	
	
		$foodDetails[] = array(
			'name' => $foodName,
			'details' => $foodDetailsString
		);
	}
	
	date_default_timezone_set('Asia/Colombo');
	$currentDate = date("Y-m-d");
	$currentTime = date("H:i:s");

	$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

	$pdf->SetCreator('auto generated');
	$pdf->SetAuthor('FoodFrenzy');
	$pdf->SetTitle('Order Number '. $orderID. ' Details');

	$pdf->AddPage();

	$imagePath = '/xampp/htdocs/FoodFrenzy/storage/photos/system/logo.png';

	$imageWidth = 200; // Define the width of the image
	$imageHeight = 200; // Define the height of the image

	// Set opacity (from 0 for fully transparent to 1 for fully opaque)
	$opacity = 0.2; // Adjust this value as needed

	// Save current alpha value
	$pdf->setAlpha($opacity);

	$pdf->Image(
		$imagePath,
		($pdf->GetPageWidth() - $imageWidth) / 2,
		($pdf->GetPageHeight() - $imageHeight) / 2,
		$imageWidth,
		$imageHeight,
		'', // Link
		'', // Target
		'', // Alt text
		false, // Border
		300, // Image DPI
		'', // Align
		false, // Fit to box
		false, // Hidden
		0 // Preserve transparency
	);

	// Restore previous alpha value (optional)
	$pdf->setAlpha(1);
	$pdf->SetFont('times', 'B', 18);
	$pdf->Cell(0, 10, 'Order Number '. $orderID. ' Details', 0, 1, 'C');

	$pdf->Ln(10);

	$cellX = $pdf->GetX(); // Get current X position
	$cellY = $pdf->GetY(); // Get current Y position
	
	$cellWidth = $pdf->GetPageWidth() * 0.5;

	$pdf->SetFont('times', '', 14);

	$pdf->SetXY($cellX, $cellY + 0);
	$pdf->Cell($cellWidth, 20, 'Order ID                 :     '   .$orderID, 0, 1, 'L'); // Added 1 as the last parameter to force a line break after the cell

	$pdf->SetXY($cellX, $pdf->GetY());
	$pdf->Cell($cellWidth, 20, 'User ID                   :     '   .strtoupper($userID), 0, 1, 'L'); // Added 1 as the last parameter to force a line break after the cell

	$pdf->SetXY($cellX, $pdf->GetY());
	$pdf->Cell($cellWidth, 20, 'User Name             :     '   .$recipientName, 0, 1, 'L'); // Added 1 as the last parameter to force a line break after the cell

	// Set the initial Y position for the block of food details
	$yPosition = $pdf->GetY(); // Adjust this value as needed
	$lineHeight = 6; // Adjust this value based on your font size

	// Output the label for the block of food details
	$pdf->SetXY($cellX, $yPosition);
	$pdf->Cell(80, 20, 'Food Details          : ', 0, 0, 'L');

	// Move the Y position down
	$yPosition += 5; // Adjust this value based on the space you want between the label and the details

	$foodDetailsArray = explode("\n", $foodDetailsString);
	foreach ($foodDetailsArray as $line) {
		// Set the X position for the food details text
		$pdf->SetXY($cellX + 50, $yPosition);

		// Output the food details text
		$pdf->MultiCell($cellWidth, 20, $line, 0, 'L');

		// Increment the Y position for the next line
		$yPosition += $lineHeight;
	}

	$pdf->SetXY($cellX, $pdf->GetY());
	$pdf->Cell($cellWidth, 20, 'Price                      :     '   .$totalPrice, 0, 1, 'L'); // Added 1 as the last parameter to force a line break after the cell

	$pdf->SetXY($cellX, $pdf->GetY());
	$pdf->Cell($cellWidth, 20, 'Payment method   :     '   .$payType, 0, 1, 'L'); // Added 1 as the last parameter to force a line break after the cell

	$pdf->SetXY($cellX, $pdf->GetY());
	$pdf->Cell($cellWidth, 20, "Order Date            : $currentDate", 0, 1, 'L'); // Added 1 as the last parameter to force a line break after the cell

	$pdf->SetXY($cellX, $pdf->GetY());
	$pdf->Cell($cellWidth, 20, "Order Time            : $currentTime", 0, 1, 'L'); // Added 1 as the last parameter to force a line break after the cell
	
	
	$pdf->SetXY(10, $pdf->GetY()); 
	$pdf->SetFont('times', '', 12);
	$cellWidth = $pdf->GetPageWidth() * 0.9; // Adjust the multiplier as needed
	$pdf->MultiCell($cellWidth, 20, "* This PDF document should be securely stored, and the order ID should not be shared with anyone. When you arrive to collect your order, please present the order ID to the cashier to receive your items. Please note that sometimes unavailable items and items we no longer host may be removed from our system, resulting in their absence from your order. In such cases, we will send you a new updated order PDF under the same order ID. Therefore, some items may not be included in your updated order. If you are not satisfied with the updated order, you have the option to discard it. All orders are automatically discarded if not collected within 1 weeks of placing the order. If you paid using your wallet, the wallet balance will be automatically refunded. *", 0, 'L');


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
	
		$mail_sender->addAddress($email, $recipientName);
		$mail_sender->isHTML(true);
		$mail_sender->Subject = 'Your Order ' . $orderID . ' Details';
		$mail_sender->Body = 'Dear ' . $recipientName . ',This is your Order ID (' . $orderID . ') details<br/>';

		$pdfContent = $pdf->Output('order ' . $orderID . '.pdf', 'S');
		$mail_sender->addStringAttachment($pdfContent, 'order ' . $orderID . '.pdf');

		$mail_sender->Body .= 'All details and further information are stored in the attached PDF.<br/>';
		$mail_sender->Body .= 'Thank you for using our service.';
		$mail_sender->Body .= '<br/><br/>(This email is auto-generated. Please do not reply to this message.)';

		$mail_sender->send();
		
		$successValue = true;
		$successMessage = "An order receipt has been sent to your email. Please follow the instructions provided therein for further steps.!";
		
	
	} catch (Exception $e) {
		$errorValue = true;
        $errorMessage = "Email could not be sent. Mailer Error: {$mail_sender->ErrorInfo}";
	}
	
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
