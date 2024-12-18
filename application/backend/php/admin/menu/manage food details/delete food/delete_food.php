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
	    
    if (
        isset($_POST['buttonId']) &&
        isset($_POST['buttonClass']) &&
        isset($_SESSION['foodManageButtonProcess']) &&
        isset($_SESSION['foodManageButtonId']) &&
        isset($_SESSION['foodManageBtnProcessStep1'])
    ) {
        $buttonId = $_POST['buttonId'];
        $buttonClass = $_POST['buttonClass'];
        $prevSettingButtonProcess = $_SESSION['foodManageButtonProcess'];
        $prevSettingButtonId = $_SESSION['foodManageButtonId'];
        $foodManageBtnProcessStep1 = $_SESSION['foodManageBtnProcessStep1'];
        
        ob_start();
        include '/xampp/htdocs/FoodFrenzy/configuration/php/connect to database/con_server.php';
        $includedContent = ob_get_clean();
    
        $conn_status_data = json_decode($includedContent, true);
        $conn_status = $conn_status_data['connection_status'];
                
        if ($conn_status === "successfully") {
        
            if (
                $buttonId === 'alert_second_btn' &&
                $buttonClass === 'alert_button' &&
                $prevSettingButtonProcess === 'delete_food' &&
                $prevSettingButtonId === 'delete_button' &&
                $foodManageBtnProcessStep1 === 'complete'
            ) {
                $foodDetails = $_SESSION['food_details'];
				$foodNumber = $foodDetails['foodNumber'];
				$foodName = $foodDetails['foodName'];
				
				$availability = 'deleted';
				$deleteFoodItem = "UPDATE foods SET availability = ? WHERE food_number = ?";
				
				if ($stmt = $conn->prepare($deleteFoodItem)) {
                    $stmt->bind_param("ss", $availability, $foodNumber);
                    $stmt->execute();
                    $stmt->close(); 
										
					$searchUpdateUsersOrders = "SELECT u.email, GROUP_CONCAT(o.order_id) AS order_ID
												FROM users u
												INNER JOIN cart c ON u.user_id = c.user_id
												INNER JOIN cart_foods cf ON c.cart_no = cf.cart_no
												INNER JOIN orders o ON c.cart_no = o.cart_no
												WHERE cf.food_number = ? AND o.state = ?
												GROUP BY u.email";

					$orderState = 'pending';
					if ($stmt = $conn->prepare($searchUpdateUsersOrders)) {
						$stmt->bind_param("ss", $foodNumber, $orderState);
						$stmt->execute();
						$result = $stmt->get_result();

						$users = array();
							while ($row = $result->fetch_assoc()) {
								$order_IDs = explode(',', $row['order_ID']);

								$order_info = array();
								foreach ($order_IDs as $order_id) {
									// Get the count of cart food rows for this order ID
									$getCartRowCountQuery = "SELECT COUNT(*) AS cart_count FROM cart_foods WHERE cart_no IN (SELECT cart_no FROM orders WHERE order_id = ?)";
									if ($countStmt = $conn->prepare($getCartRowCountQuery)) {
										$countStmt->bind_param("s", $order_id);
										$countStmt->execute();
										$countResult = $countStmt->get_result();
										$cart_count = $countResult->fetch_assoc()['cart_count'];
										$countStmt->close();
										
										if ($cart_count === 1) {
											try {
												$resultCheckWalletPay = checkWalletPay($conn, $order_id);
												
												if ($resultCheckWalletPay === 'wallet') {
													try {
														$resultOrderReturns = orderReturns($conn, $order_id, $foodNumber, $resultCheckWalletPay);
													} catch (Exception $e) {
														$response['success'] = false;
														$response['alert'] = $e->getMessage(); // Get the error message from the exception
														$response['error_page'] = true;
													}
												}
												try {
													$cart_new_state = 'cancel';
													$resultUpdateOrder =  updateOrder($conn, $order_id, $foodNumber, $cart_new_state);
												} catch (Exception $e) {
													$response['success'] = false;
													$response['alert'] = $e->getMessage(); // Get the error message from the exception
													$response['error_page'] = true;
												}
											} catch (Exception $e) {
												$response['success'] = false;
												$response['alert'] = $e->getMessage(); // Get the error message from the exception
												$response['error_page'] = true;
											}
											try {
												$updateType = 'cancel';
												$resultUpdateOrdersInform = updateOrdersInform ($conn, $order_id, $updateType);
												
												$response['success'] = true;
												$response['details'] = $resultUpdateOrdersInform; 
											} catch (Exception $e) {
												$response['success'] = false;
												$response['alert'] = $e->getMessage(); // Get the error message from the exception
												$response['error_page'] = true;
											}
										} elseif ($cart_count > 1) {
											try {
												$resultCheckWalletPay = checkWalletPay($conn, $order_id);
												
												if ($resultCheckWalletPay === 'wallet') {
													try {
														$resultOrderReturns = orderReturns($conn, $order_id, $foodNumber, $resultCheckWalletPay);
													} catch (Exception $e) {
														$response['success'] = false;
														$response['alert'] = $e->getMessage(); // Get the error message from the exception
														$response['error_page'] = true;
													}
												}
												try {
													$cart_new_state = 'update';
													$resultUpdateOrder =  updateOrder($conn, $order_id, $foodNumber, $cart_new_state);
												} catch (Exception $e) {
													$response['success'] = false;
													$response['alert'] = $e->getMessage(); // Get the error message from the exception
													$response['error_page'] = true;
												}
											} catch (Exception $e) {
												$response['success'] = false;
												$response['alert'] = $e->getMessage(); // Get the error message from the exception
												$response['error_page'] = true;
											}
											try {
												$updateType = 'update';
												$resultUpdateOrdersInform = updateOrdersInform ($conn, $order_id, $updateType);
											} catch (Exception $e) {
												$response['success'] = false;
												$response['alert'] = $e->getMessage(); // Get the error message from the exception
												$response['error_page'] = true;
											}
										}
									} else {
										$response['success'] = false;
										$response['alert'] = 'Error in retrieving cart count for order ID ' . $order_id;
										$response['error_page'] = true;
										break; // Exit the loop
									}
									$order_info[] = array(
										'order_id' => $order_id,
										'cart_count' => $cart_count
									);
								}

								$users[] = array(
									'email' => $row['email'],
									'orders' => $order_info
								);
							}
							$response['users'] = $users;
							$response['success'] = true;
						} else {
							$response['success'] = false;
							$response['alert'] = 'Error in retrieving user orders.';
							$response['error_page'] = true;
						}
					$response['alertContent'] = true;
					$response['alertType'] = 'delete_food';
					$_SESSION['alert'] = $foodName . " informations deleted from the system.!";
                } else {
                    $response['success'] = false;
                    $response['alert'] = 'Error in user details delete statement';
                    $response['error_page'] = true;
                }
            } else {
				$response['success'] = false;
                $response['alert'] = 'Error in button variable process.';
                $response['error_page'] = true;	
			}
        } else {
            $response['success'] = false;
            $response['alert'] = 'Database connection error.';
            $response['error_page'] = true;
        }
    } else {
        $response['success'] = false;
        $response['alert'] = 'Error in process';
        $response['error_page'] = true;
    }
} else {
    $response['success'] = false;
    $response['alert'] = 'Invalid request method.';
    $response['error_page'] = true;
}


function checkWalletPay($conn, $order_id) {
    $checkOrderPayMethod = "SELECT pay_method FROM orders WHERE order_id = ?";
    
    if ($stmt = $conn->prepare($checkOrderPayMethod)) {
        $stmt->bind_param("s", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $pay_method = $result->fetch_assoc()['pay_method'];
            return $pay_method; 
        } else {
            throw new Exception("No pay_method found for order ID: $order_id");
        }
    } else {
        throw new Exception("Error in checkOrderPayMethod statement: " . $conn->error);
    }
}


function orderReturns($conn, $order_id, $foodNumber, $resultCheckWalletPay)
{
    $retrieveUserFoodWalletDetails = "SELECT u.user_id, uw.current_balance, uw.wallet_id, SUM(f.discount_price * cf.quantity) AS food_total_price
                                    FROM orders o
                                    INNER JOIN cart c ON o.cart_no = c.cart_no
                                    INNER JOIN users u ON c.user_id = u.user_id
                                    INNER JOIN user_wallets uw ON u.user_id = uw.user_id
                                    INNER JOIN cart_foods cf ON c.cart_no = cf.cart_no
                                    INNER JOIN foods f ON cf.food_number = f.food_number
                                    WHERE o.order_id = ? AND cf.food_number = ?";
    
    if ($stmt = $conn->prepare($retrieveUserFoodWalletDetails)) {
        $stmt->bind_param("ss", $order_id, $foodNumber);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        
            $userId = $row['user_id'];
            $walletCurrentBalance = $row['current_balance'];
            $walletId = $row['wallet_id'];
            $food_total_price = $row['food_total_price'];
            
            $orderReturnMethod = 'expired_order_returns';
            $newBalance = $walletCurrentBalance + $food_total_price;
            
            $updateWalletRecordsTable = "INSERT INTO wallet_records (wallet_id, payment, payment_method, past_balance, balance, date, time)
                                        VALUES (?, ?, ?, ?, ?, CURDATE(), CURTIME())";
                                            
            if ($stmt = $conn->prepare($updateWalletRecordsTable)) {
                $stmt->bind_param("ddsdd", $walletId, $food_total_price, $orderReturnMethod, $walletCurrentBalance, $newBalance);
                $stmt->execute();
                                                
                $updateWalletTable = "UPDATE user_wallets SET current_balance = ? WHERE user_id = ?";
                if ($stmt = $conn->prepare($updateWalletTable)) {
                    $stmt->bind_param("ds", $newBalance, $userId);
                    $stmt->execute();
                } else {
                    throw new Exception("Error in updateWalletTable prepare statement!");
                }
            } else {
                throw new Exception("Error in updateWalletRecordsTable prepare statement!");
            }
            
            return $food_total_price; 
        } else {
            throw new Exception("No food_total_price found for order ID and food Number: $order_id, $foodNumber");
        }
    } else {
        throw new Exception("Error in retrieveUserFoodWalletDetails statement: " . $conn->error);
    }   
}



function updateOrder($conn, $order_id, $foodNumber, $cart_new_state) {
	if ($cart_new_state === 'cancel') {
		$updateOrderQuery = "UPDATE orders SET state = ? WHERE order_id = ?";
	} elseif ($cart_new_state === 'update') {
		$updateOrderQuery = "DELETE FROM cart_foods
                     WHERE cart_no IN (SELECT cart_no
                                       FROM orders
                                       WHERE order_id = ?)
                     AND food_number = ?";
	}
	
	
	if ($stmt = $conn->prepare($updateOrderQuery)) {
		
		if ($cart_new_state === 'cancel') {
			$updateState = 'expired';
			$stmt->bind_param("ss", $updateState, $order_id);
		} elseif ($cart_new_state === 'update') { 
			$stmt->bind_param("ss", $order_id, $foodNumber);
		}
        $stmt->execute();
        
	} else {
        throw new Exception("Error in updateOrderQuery prepare statement!");
    }
}


function updateOrdersInform ($conn, $order_id, $updateType) {
	
	if ($updateType === 'update') {
		$pdfDetails = array();
		
		$retrivePriceDetails = "SELECT SUM(f.discount_price * cf1.quantity) AS total_price
								FROM cart_foods cf1
								INNER JOIN foods f ON cf1.food_number = f.food_number
								INNER JOIN cart c ON c.cart_no = cf1.cart_no
								INNER JOIN orders o ON c.cart_no = o.cart_no
								WHERE o.order_id = ?";
		
		if ($stmtBalance = $conn->prepare($retrivePriceDetails)) {
			$stmtBalance->bind_param("s", $order_id); // Assuming orderID is used here
			$stmtBalance->execute();
			$resultBalance = $stmtBalance->get_result();
		
			if ($resultBalance) {
				if ($resultBalance->num_rows > 0) {
					$rowBalance = $resultBalance->fetch_assoc();
					$totalPrice = $rowBalance['total_price'];
				}
			} else {
				throw new Exception("Error in resultBalance variable set!");
			}
		} else {
			throw new Exception("Error in retrivePriceDetails prepare statement!");
		}
		
		$retrivePdfDetails = "SELECT u.user_id, u.first_name, u.last_name, u.email, GROUP_CONCAT(f.food_name) AS food_names, GROUP_CONCAT(cf.quantity) AS quantities, o.pay_method
							  FROM cart AS c
							  INNER JOIN orders AS o ON c.cart_no = o.cart_no
							  INNER JOIN users AS u ON c.user_id = u.user_id
							  INNER JOIN cart_foods AS cf ON c.cart_no = cf.cart_no
							  INNER JOIN foods AS f ON cf.food_number = f.food_number
							  WHERE o.order_id = ?
							  GROUP BY c.user_id, c.cart_no, u.first_name, u.last_name";
		
		if ($stmt = $conn->prepare($retrivePdfDetails)) {
			$stmt->bind_param("s", $order_id);
			$stmt->execute();
			$stmt->bind_result($userID, $firstName, $lastName, $email, $foodNames, $quantities, $payType);
			
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
			throw new Exception("Error in retrivePdfDetails prepare statement!");
		}
		
		
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
		$pdf->SetTitle('Order Number '. $order_id. ' Details');

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
		$pdf->Cell(0, 10, 'Order Number '. $order_id. ' Details', 0, 1, 'C');

		$pdf->Ln(10);

		$cellX = $pdf->GetX(); // Get current X position
		$cellY = $pdf->GetY(); // Get current Y position
		
		$cellWidth = $pdf->GetPageWidth() * 0.3;

		$pdf->SetFont('times', '', 14);

		$pdf->SetXY($cellX, $cellY + 0);
		$pdf->Cell($cellWidth, 20, 'Order ID                 :     '   .$order_id, 0, 1, 'L'); // Added 1 as the last parameter to force a line break after the cell

		$pdf->SetXY($cellX, $pdf->GetY());
		$pdf->Cell($cellWidth, 20, 'User ID                   :     '   .$userID, 0, 1, 'L'); // Added 1 as the last parameter to force a line break after the cell

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
	
	} elseif ($updateType === 'cancel') {
		$userDetails = array();
		$retriveEmailDetails = "SELECT u.first_name, u.last_name, u.email
							  FROM users u
							  INNER JOIN cart c ON u.user_id = c.user_id
							  INNER JOIN orders o ON c.cart_no = o.cart_no
							  WHERE o.order_id = ?";
							  
		if ($stmt = $conn->prepare($retriveEmailDetails)) {
			$stmt->bind_param("s", $order_id);
			$stmt->execute();
			$stmt->bind_result($firstName, $lastName, $email);
		
			$recipientName = ucfirst($firstName) . ' ' . ucfirst($lastName);
			
			if ($stmt->fetch()) {
                    $recipientName = ucfirst($firstName) . ' ' . ucfirst($lastName);
                    $userDetails['name'] = $recipientName;
                    $userDetails['email'] = $email;
                } else {
                    throw new Exception("No user found for order ID: $order_id");
                }
		
		} else {
			throw new Exception("Error in retriveEmailDetails prepare statement!");
		}
	}
	

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
		$mail_sender->Subject = 'Your Updated Order ' . $order_id . ' Details';
		$mail_sender->Body = 'Dear ' . $recipientName . ',This is your updated Order ID (' . $order_id . ') details<br/>';
		
		if ($updateType === 'update') {
			$pdfContent = $pdf->Output('order ' . $order_id . '.pdf', 'S');
			$mail_sender->addStringAttachment($pdfContent, 'order ' . $order_id . '.pdf');
			$mail_sender->Body .= 'All details and further information are stored in the attached PDF.<br/>';
		} elseif ($updateType === 'cancel') {
			$mail_sender->Body = 'Your order mentioned above contains items that are no longer available in our system. Therefore, we have decided to expire your order.<br/>';
		}

		
		$mail_sender->Body .= 'Thank you for using our service.<br/><br/>(This email is auto-generated. Please do not reply to this message.)';

		$mail_sender->send();
		
	} catch (Exception $e) {
		throw new Exception("Error in preparing email statement: " . $mail_sender->ErrorInfo);
	}
	return $userDetails;
}


header('Content-Type: application/json');
echo json_encode($response);
?>
