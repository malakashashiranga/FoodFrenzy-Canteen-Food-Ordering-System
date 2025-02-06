<?php
	include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin and register user/check authentication token/check_auth_token.php';
?>

<?php
	include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin and register user/user type check/admin/admin_checking.php';
?>

<?php
	if (isset($_SESSION['specific_order_details'])) {
		$finishedOrderDetails = $_SESSION['specific_order_details'];
	} else {
	   header('Location: /FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/orders/finished orders/order list/finished_orders.php'); 
	}
?>


<html>
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/css/common/admin/content/content.css">
	<link rel="stylesheet" type="text/css" href="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/css/pages/admin/orders/finished orders/specific finished order/specific_finished_order.css">
</head>
<body>  

	<?php
		include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin/side bar/side_bar.html';
	?>

	<div class="cont_container"> 
		<p id="page_title">Specific Finished Order Details</p>
		<div class="mid_container">
			<div id= "specific_finished_order_form">
				<div class="image_part" id="">
					<img id="user_image" name="user_image" class="container_image" src="<?php echo isset($finishedOrderDetails['userPhotoPath']) ? '/FoodFrenzy-Canteen-Food-Ordering-System/storage/photos/users/'.urlencode(strtolower($finishedOrderDetails['userID'])). '/' . urlencode($finishedOrderDetails['userPhotoPath']) : '/FoodFrenzy-Canteen-Food-Ordering-System/storage/photos/users/default user/default_user.svg'; ?>" alt="userImage">
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Order ID</span>
					<span id="col1" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($finishedOrderDetails['orderID']) ? $finishedOrderDetails['orderID'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">User ID</span>
					<span id="col2" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($finishedOrderDetails['userID']) ? $finishedOrderDetails['userID'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Order details</span>
					<span id="col3" class= "doub_colon">:</span>
					<span class = "plainTextDetails">
					<?php
						if (isset($finishedOrderDetails['specificOrderDetailsFoods'])) {
							foreach ($finishedOrderDetails['specificOrderDetailsFoods'] as $food) {
								echo "{$food['foodName']} - {$food['quantity']}<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							}
						}
					?>
					</span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Email</span>
					<span id="col4" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($finishedOrderDetails['email']) ? $finishedOrderDetails['email'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Mobile number</span>
					<span id="col5" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($finishedOrderDetails['mobileNumber']) ? $finishedOrderDetails['mobileNumber'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Ordered date</span>
					<span id="col6" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($finishedOrderDetails['orderedDate']) ? $finishedOrderDetails['orderedDate'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Ordered time</span>
					<span id="col7" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($finishedOrderDetails['orderedTime']) ? $finishedOrderDetails['orderedTime'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Total price</span>
					<span id="col8" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($finishedOrderDetails['totalPrice']) ? $finishedOrderDetails['totalPrice'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">payed method</span>
					<span id="col9" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($finishedOrderDetails['payMethod']) ? $finishedOrderDetails['payMethod'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Checked date</span>
					<span id="col10" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($finishedOrderDetails['finishedDate']) ? $finishedOrderDetails['finishedDate'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Checked time</span>
					<span id="col11" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($finishedOrderDetails['finishedTime']) ? $finishedOrderDetails['finishedTime'] : ''; ?></span>
				</div>
			</div>	
				
		</div>
	</div>

	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/javascript/pages/admin/orders/finished orders/specific finished order/specific_finished_order.js"></script>
	
</body>
</html>

