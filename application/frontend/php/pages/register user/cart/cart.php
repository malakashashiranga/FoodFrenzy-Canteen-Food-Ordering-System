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
	
	<link rel="stylesheet" href="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/css/pages/register/cart/cart.css">
	<link rel="stylesheet" href="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/css/common/register/cart, wallet, wallet history, order history/style.css">
</head>
<body>
	<div class="full_screen">
		<div class="menu"> 
			<?php
				include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/common/register/navigation/navigation_bar.php';
			?>
		</div>
		
		<img src="/FoodFrenzy-Canteen-Food-Ordering-System/storage/photos/system/food_table.jpg" alt="img" id="wallpaper"> 		

		<div class="middle_part">
			<div class="cart_title"><span id="first_title">My</span> <span id="second_title">Cart</span></div>
			<div class="content">
				<table id="cartTable" class ="userDetailsTable">
					<thead>
						<tr>
							<th>Image</th>
							<th>Name</th>
							<th>Price</th>
							<th>Quantity</th>
							<th></th>
						</tr>
					</thead>
					<tbody id="cartTableBody">
						
					</tbody>
				</table>
				
				<div class="checkOutField">
					<p id = "totalPrice"></p>
					<button class = "checkOut" id = "checkOutButton">CheckOut</button>
				</div>
				<p id ="noItemAlert"></p>
			</div>
			
			<p class="blue_letters" id = "order_history_page_link">View Order History</p>
		</div>
	</div>
		
	<div class="checkOutFieldAlert">
		<img src="/FoodFrenzy-Canteen-Food-Ordering-System/storage/svg/system/cross.svg" id="food_alert_cross">
		<p id="checkOutFieldAlertTitle">Payment Type</p>
		<p id="PayAlert" style="color: red; font-size: 18px; margin: 0px 0px 10px 0px;"></p>
		
		<form id ="checkOutFieldForm">
			<input type="radio" name="payType" value="wallet" id="walletRadio" class="payTypeRadio">
			<label for="walletRadio" class="payTypelabel">Wallet&nbsp;&nbsp;<span id="walletPayAlert" style="color: red; font-size: 18px;"></span></label><br/>
			<input type="radio" name="payType" value="cash" id="cashRadio" class="payTypeRadio">
			<label for="cashRadio" class="payTypelabel">Cash (pay when ordering)</label><br/>
			<button class = "checkOut" id = "payButton">Pay</button>
		</form>
	</div>
		
		
	<?php
		include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/register and unregister user/alert bar/alert.html';
	?>
		
	<?php
		include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/loading spinner/loading_spinner.html';
	?>

	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/javascript/pages/register user/cart/cart.js"></script>
	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/AJAX/register user/cart page/handle cart/handle_cart_details_ajax.js"></script>
	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/AJAX/register user/cart page/make payment/make_payment_ajax.js"></script>
	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/AJAX/register user/check last active/last_active_ajax.js"></script>

</body>
</html>
