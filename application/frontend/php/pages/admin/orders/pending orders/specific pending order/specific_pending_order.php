<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/check authentication token/check_auth_token.php';
?>

<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/user type check/admin/admin_checking.php';
?>

<?php
	if (isset($_SESSION['specific_order_details'])) {
		$orderDetails = $_SESSION['specific_order_details'];
	} else {
	   header('Location: /FoodFrenzy/application/frontend/php/pages/admin/orders/pending orders/order list/pending_orders.php'); 
	}
?>


<html>
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/FoodFrenzy/application/frontend/css/same/admin/content/content.css">
	<link rel="stylesheet" type="text/css" href="/FoodFrenzy/application/frontend/css/pages/admin/orders/pending orders/specific pending order/specific_pending_order.css">
</head>
<body>  
	<div class="full_screen">
		<?php
			include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/admin/side bar/side_bar.html';
		?>

		<div class="cont_container"> 
			<p id="page_title">Manage Specific Order Details</p>
			
			<div class="mid_container">
				<form action="" method="post" class="" id="specific_order_form" enctype="multipart/form-data">
		
					<div class="image_part" id="">
						<img id="user_image" name="user_image" class="container_image" src="<?php echo isset($orderDetails['userPhotoPath']) ? '/FoodFrenzy/storage/photos/users/'.urlencode(strtolower($orderDetails['userID'])). '/' . urlencode($orderDetails['userPhotoPath']) : '/FoodFrenzy/storage/photos/users/default user/default_user.svg'; ?>" alt="userImage">
					</div>
				
					<div class="bar_segments">
						<label for="order_id" class="form_labels">Order ID</label>
						<span id="col1">:</span>
						<input type="text" id="order_id" name="order_id" class="fill_bars" autocomplete="off" value="<?php echo isset($orderDetails['orderID']) ? $orderDetails['orderID'] : ''; ?>"><br/>
					</div>
				
					<div class="bar_segments">
						<label for="user_id" class="form_labels">User ID</label>
						<span id="col2">:</span>
						<input type="text" id="user_id" name="user_id" class="fill_bars" autocomplete="off" value="<?php echo isset($orderDetails['userID']) ? $orderDetails['userID'] : ''; ?>"><br/>
					</div>
				
					<div class="bar_segments">
						<label for="order_foods" class="form_labels">Order details</label>
						<span id="col3">:</span>
						<textarea id="order_foods" name="order_foods" class="fill_bars" rows="4" autocomplete="off">
							<?php
							if (isset($orderDetails['specificOrderDetailsFoods'])) {
								$foodDetails = '';
								foreach ($orderDetails['specificOrderDetailsFoods'] as $food) {
									$foodDetails .= "({$food['foodName']} - {$food['quantity']})\n\n";
								}
								// Remove the trailing comma and space
								echo rtrim($foodDetails, ', ');
							}
							?>
						</textarea>
					</div>

				
					<div class="bar_segments">
						<label for="email" class="form_labels">Email</label>
						<span id="col4">:</span>
						<input type="text" id="email" name="email" class="fill_bars" autocomplete="off" value="<?php echo isset($orderDetails['email']) ? $orderDetails['email'] : ''; ?>"><br/>
					</div>
				
					<div class="bar_segments">
						<label for="mob_number" class="form_labels">Mobile number</label>
						<span id="col5">:</span>
						<input type="text" id="mob_number" name="mob_number" class="fill_bars" autocomplete="off" value="<?php echo isset($orderDetails['mobileNumber']) ? $orderDetails['mobileNumber'] : ''; ?>"><br/>
					</div>
				
					<div class="bar_segments">
						<label for="ordered_date" class="form_labels">Ordered date</label>
						<span id="col6">:</span>
						<input type="text" id="ordered_date" name="ordered_date" class="fill_bars" autocomplete="off" value="<?php echo isset($orderDetails['orderedDate']) ? $orderDetails['orderedDate'] : ''; ?>"><br/>
					</div>
				
					<div class="bar_segments">
						<label for="ordered_time" class="form_labels">Ordered time</label>
						<span id="col7">:</span>
						<input type="text" id="ordered_time" name="ordered_time" class="fill_bars" autocomplete="off" value="<?php echo isset($orderDetails['orderedTime']) ? $orderDetails['orderedTime'] : ''; ?>"><br/>
					</div>
				
					<div class="bar_segments">
						<label for="total_price" class="form_labels">Total price</label>
						<span id="col8">:</span>
						<input type="text" id="total_price" name="total_price" class="fill_bars" autocomplete="off" value="<?php echo isset($orderDetails['totalPrice']) ? $orderDetails['totalPrice'] : ''; ?>"><br/>
					</div>
				
					<div class="bar_segments">
						<label for="payed_as" class="form_labels">Payed as</label>
						<span id="col9">:</span>
						<input type="text" id="payed_as" name="payed_as" class="fill_bars" autocomplete="off" value="<?php echo isset($orderDetails['payMethod']) ? $orderDetails['payMethod'] : ''; ?>"><br/>
					</div>
							
					<div class="button-container">
						<button type="button" class="ajax-button" id="confirm_button">Confirm</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<?php
		include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/admin/alert bar/alert.html';
	?>
	
	<?php
		include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/loading spinner/loading_spinner.html';
	?>
	
	<script src="/FoodFrenzy/application/frontend/javascript/pages/admin/orders/pending orders/specific pending order/specific_pending_order.js"></script>
	<script src="/FoodFrenzy/application/frontend/javascript/ajax/admin/orders/pending orders/specific pending order/confirm_specific_order_ajax.js"></script>

</body>
</html>

