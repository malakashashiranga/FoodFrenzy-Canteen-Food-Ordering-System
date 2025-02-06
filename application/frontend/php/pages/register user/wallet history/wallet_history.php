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
		
	<link rel="stylesheet" href="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/css/pages/register/wallet history/wallet_history.css">
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
		<div class="cart_title"><span id="first_title">Wallet</span> <span id="second_title">History</span></div>
			
		<div class="content">
			<table id="walletHistoryTable" class ="userDetailsTable">
				<thead>
					<tr>
						<th>Date</th>
						<th>Past Balance</th>
						<th>Transaction type</th>
						<th>Transaction balance</th>
						<th>New Balance</th>
					</tr>
				</thead>
				<tbody id="walletHistoryBody">
					
				</tbody>
			</table>
			<p class="blue_letters" id="current_wallet">View Current Wallet</p>
			<p id ="noItemAlert"></p>
		</div>
	</div>
	
	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/javascript/pages/register user/wallet history/wallet_history.js"></script>
	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/AJAX/register user/wallet/wallet history/load_wallet_history_ajax.js"></script>
	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/AJAX/register user/check last active/last_active_ajax.js"></script>
		
</body>
</html>

