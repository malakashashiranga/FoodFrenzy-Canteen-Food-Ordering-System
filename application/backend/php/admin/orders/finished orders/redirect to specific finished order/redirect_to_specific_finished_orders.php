<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['orderID'])) {
        $orderID = $_GET['orderID'];
				
		ob_start();
        include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/configuration/php/connect to database/con_server.php';
        $includedContent = ob_get_clean();
        
        $conn_status_data = json_decode($includedContent, true);
        $conn_status = $conn_status_data['connection_status'];    
    
        if ($conn_status === "successfully") {			
			
			$retriveFinishedOrders = "SELECT u.photo_path, u.user_id, u.first_name, u.last_name, cf.food_number, fo.food_name, cf.quantity, u.email, u.mobile_number, o.ordered_date, o.ordered_time, o.pay_method , fin.finished_date, fin.finished_time, SUM(fo.discount_price * cf.quantity) AS total_price
									FROM orders o
									INNER JOIN cart c ON o.cart_no = c.cart_no
									INNER JOIN users u ON c.user_id = u.user_id
									INNER JOIN cart_foods cf ON c.cart_no = cf.cart_no
									INNER JOIN foods fo ON cf.food_number = fo.food_number
									INNER JOIN finished_orders fin ON o.order_id = fin.order_id
									WHERE o.order_id = ?";
									
			$retrieveFoodDetails = "SELECT f.food_name, cf.quantity
									FROM orders o
									INNER JOIN cart c ON o.cart_no = c.cart_no
									INNER JOIN cart_foods cf ON c.cart_no = cf.cart_no
									INNER JOIN foods f ON cf.food_number = f.food_number
									WHERE o.order_id = ?";
						
			if ($stmt = $conn->prepare($retriveFinishedOrders)) {
				$stmt->bind_param("s", $orderID); 
				$stmt->execute();
				$result = $stmt->get_result();
				
				if ($result->num_rows > 0) {
					// Fetch and store the main order details
					$row = $result->fetch_assoc();

					// Store user and order details in session
					$_SESSION['specific_order_details'] = array(
						'orderID' => $orderID,
						'userPhotoPath' => $row['photo_path'],
						'userID' => strtoupper($row['user_id']),
						'userName' => ucfirst($row['first_name']) . ' ' . ucfirst($row['last_name']),
						'email' => $row['email'],
						'mobileNumber' => $row['mobile_number'],
						'orderedDate' => $row['ordered_date'],
						'orderedTime' => $row['ordered_time'],
						'finishedDate' => $row['finished_date'],
						'finishedTime' => $row['finished_time'],
						'payMethod' => ucfirst($row['pay_method']),
						'totalPrice' => 'Rs. '.$row['total_price'],
						'specificOrderDetailsFoods' => array() // Initialize an empty array for specificOrderDetailsFoods
					);
					
					if ($stmt = $conn->prepare($retrieveFoodDetails)) {
						$stmt->bind_param("s", $orderID); 
						$stmt->execute();
						$foodResult = $stmt->get_result();
						
						$specificOrderDetailsFoods = array();
						while ($food_row = $foodResult->fetch_assoc()) {
							$specificOrderDetailsFoods[] = array(
								'foodName' => ucfirst($food_row['food_name']),
								'quantity' => $food_row['quantity']
							);
						}
						
						// Add specificOrderDetailsFoods to specific_order_details
						$_SESSION['specific_order_details']['specificOrderDetailsFoods'] = $specificOrderDetailsFoods;
					} else {
						$errorMessage = "Error in retrieveFoodDetails query.";
						redirectToErrorPage($errorMessage);    	
					}

					header('Location: /FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/orders/finished orders/specifci finished order/specific_finished_order.php');
				} else {
					$errorMessage = "No rows returned from database.";
					redirectToErrorPage($errorMessage);
				}

			} else {
				$errorMessage = "Error in retriveFinishedOrders query.";
				redirectToErrorPage($errorMessage);    	
			}
		} else {
			$errorMessage = "Database connection error.";
			redirectToErrorPage($errorMessage); 
		}
    } else {
		$errorMessage = "Order ID not provided in the URL.";
		redirectToErrorPage($errorMessage);    
    }
} else {
    $errorMessage = "Error in request method.";
    redirectToErrorPage($errorMessage);    
}


function redirectToErrorPage($errorMessage) {
    $pageLink = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html"; 
    $goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/orders/finished orders/order list/finished_orders.php"; 

    header("Location: $pageLink?message=" . urlencode($errorMessage) . "&goBackURL=" . urlencode($goBackURL));
    exit;
}

?>
