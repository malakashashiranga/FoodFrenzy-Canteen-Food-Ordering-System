<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/check authentication token/check_auth_token.php';
?>

<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/user type check/admin/admin_checking.php';
?>

<?php
	if (isset($_SESSION['specific_order_details'])) {
		$discardOrderDetails = $_SESSION['specific_order_details'];
	} else {
	   header('Location: /FoodFrenzy/application/frontend/php/pages/admin/orders/discard orders/order list/discard_orders.php'); 
	}
?>


<html>
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/FoodFrenzy/application/frontend/css/same/admin/content/content.css">
	<link rel="stylesheet" type="text/css" href="/FoodFrenzy/application/frontend/css/pages/admin/orders/discard orders/specific discard order/specific_discard_order.css">
</head>
<body>  

	<?php
		include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/admin/side bar/side_bar.html';
	?>

	<div class="cont_container"> 
		<p id="page_title">Specific Discard Order Details</p>
		<div class="mid_container">
			<div id= "specific_discard_order_form">
				<div class="image_part" id="">
					<img id="user_image" name="user_image" class="container_image" src="<?php echo isset($discardOrderDetails['userPhotoPath']) ? '/FoodFrenzy/storage/photos/users/'.urlencode(strtolower($discardOrderDetails['userID'])). '/' . urlencode($discardOrderDetails['userPhotoPath']) : '/FoodFrenzy/storage/photos/users/default user/default_user.svg'; ?>" alt="userImage">
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Order ID</span>
					<span id="col1" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($discardOrderDetails['orderID']) ? $discardOrderDetails['orderID'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">User ID</span>
					<span id="col2" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($discardOrderDetails['userID']) ? $discardOrderDetails['userID'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Order details</span>
					<span id="col3" class= "doub_colon">:</span>
					<span class = "plainTextDetails">
					<?php
						if (isset($discardOrderDetails['specificOrderDetailsFoods'])) {
							foreach ($discardOrderDetails['specificOrderDetailsFoods'] as $food) {
								echo "{$food['foodName']} - {$food['quantity']}<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							}
						}
					?>
					</span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Email</span>
					<span id="col4" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($discardOrderDetails['email']) ? $discardOrderDetails['email'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Mobile number</span>
					<span id="col5" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($discardOrderDetails['mobileNumber']) ? $discardOrderDetails['mobileNumber'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Ordered date</span>
					<span id="col6" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($discardOrderDetails['orderedDate']) ? $discardOrderDetails['orderedDate'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Ordered time</span>
					<span id="col7" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($discardOrderDetails['orderedTime']) ? $discardOrderDetails['orderedTime'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Total price</span>
					<span id="col8" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($discardOrderDetails['totalPrice']) ? $discardOrderDetails['totalPrice'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">payed method</span>
					<span id="col9" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($discardOrderDetails['payMethod']) ? $discardOrderDetails['payMethod'] : ''; ?></span>
				</div>
				
				
			</div>	
				
		</div>
	</div>

	<script src="/FoodFrenzy/application/frontend/javascript/pages/admin/orders/discard orders/specific discard order/specific_discard_order.js"></script>

</body>
</html>

