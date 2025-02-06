<?php
	include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin and register user/check authentication token/check_auth_token.php';
?>

<?php
	include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin and register user/user type check/customers/register_user_check.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="UTF-8">
					
	<link rel="stylesheet" href="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/css/pages/register/order history/order_history.css">
	<link rel="stylesheet" href="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/css/common/register/cart, wallet, wallet history, order history/style.css">
</head>
<body>
	<div class="menu"> 
		<?php
			include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/common/register/navigation/navigation_bar.php';
		?>
	</div>
	
	<img src="/FoodFrenzy-Canteen-Food-Ordering-System/storage/photos/system/food_table.jpg" alt="img" id="wallpaper"> 		
	<div class="middle_part">
		<div class="cart_title"><span id="first_title">Order</span> <span id="second_title">History</span></div>
		<div class="content">
			<table id="orderHistoryTable" class ="userDetailsTable" style= "transform: translate(40px, 0);">
				<thead>
					<tr>
						<th>Order ID</th>
						<th>Ordered Date</th>
						<th>Ordered Time</th>
						<th>Net Price</th>
						<th>State</th>
					</tr>
				</thead>
				<tbody id="orderHistoryTableBody">
						
				</tbody>
			</table>
			<p id ="noItemAlert"></p>
		</div>
		<p class="blue_letters" id= "cart_page_link">View Cart Details</p>
	</div>
	
	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/javascript/pages/register user/order history/order_history.js"></script>
	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/AJAX/register user/order history/load_order_history_ajax.js"></script>
	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/AJAX/register user/check last active/last_active_ajax.js"></script>
		
</body>
</html>
