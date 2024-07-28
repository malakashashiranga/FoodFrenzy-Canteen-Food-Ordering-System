<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/check authentication token/check_auth_token.php';
?>

<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/user type check/customers/register_user_check.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="UTF-8">
		
	<link rel="stylesheet" href="/FoodFrenzy/application/frontend/css/pages/register/wallet/wallet.css">
	<link rel="stylesheet" href="/FoodFrenzy/application/frontend/css/same/register/cart, wallet, wallet history, order history/style.css">
</head>
<body>
	<div class="menu"> 
		<?php
			include '/xampp/htdocs/FoodFrenzy/application/frontend/php/same/register/navigation/navigation_bar.php';
		?>
	</div>
	
	<img src="/FoodFrenzy/storage/photos/system/food_table.jpg" alt="img" id="wallpaper"> 		
	<div class="middle_part">
		<div class="cart_title"><span id="first_title">My</span> <span id="second_title">Wallet</span></div>
		<table>
			<tr>
				<th>User ID</th>
				<th>Current balance</th>
			</tr>
			<tr>
				<td id="user_id"></td>
				<td id="wallet_bal"></td>
			</tr>
		</table>
		<p class="blue_letters" id="wallet_history">View Wallet History</p>
	</div>
	
	<script src="/FoodFrenzy/application/frontend/javascript/pages/register user/wallet/wallet.js"></script>
	<script src="/FoodFrenzy/application/frontend/javascript/ajax/register user/wallet/wallet details/show_wallet_details_ajax.js"></script>
	<script src="/FoodFrenzy/application/frontend/javascript/ajax/register user/check last active/last_active_ajax.js"></script>

</body>
</html>

