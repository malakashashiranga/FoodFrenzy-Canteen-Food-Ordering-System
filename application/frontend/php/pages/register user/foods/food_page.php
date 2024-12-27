<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/check authentication token/check_auth_token.php';
?>

<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/user type check/customers/register_user_check.php';
?>

<?php
	$_SESSION['currentPage'] = 'reg_food_page';
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="UTF-8">
    
    <link rel="stylesheet" href="/FoodFrenzy/application/frontend/css/pages/register/foods/food_page.css">
	<link rel="stylesheet" href="/FoodFrenzy/application/frontend/css/same/register and unregister/home/home_food.css">
</head>
<body>
	<div class="fullScreen">
		<div class="f_st_part"> 
			<?php
				include '/xampp/htdocs/FoodFrenzy/application/frontend/php/same/register/navigation/navigation_bar.php';
			?>
			<img src="/FoodFrenzy/storage/photos/system/pizza_table.jpg" alt="wallpaper" id="wallpaper"> 
			<p class="greeting">Your favorite foods items with<br/>dis-countable prices from<br/>FoodFrenzy</p>
		</div>
	
		<div class="s_nd_part" id="s_nd_part">
			<?php
				include '/xampp/htdocs/FoodFrenzy/application/frontend/php/same/register and unregister user/food page food part/food_part.php';
			?>
		</div>
	
		<div class="t_rd_part">
			<?php
				include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/register user/site map/site_map.html';
			?>
		</div>
	
		<div class="f_rd_part">
			<?php
				include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/register and unregister user/copyright/copyright.html';
			?>
		</div>
	</div>
	
	<?php
		include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/register and unregister user/specific foods/show_specific_food.html';
	?>

	<?php
		include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/register user/add foods alert/add_foods_alert.html';
	?>

	<script src="/FoodFrenzy/application/AJAX/register user/foods page/load foods/load_foods_ajax.js"></script>
	<script src="/FoodFrenzy/application/AJAX/register user/foods page/foods add to cart/food_add_to_cart_ajax.js"></script>
	<script src="/FoodFrenzy/application/AJAX/register user/check last active/last_active_ajax.js"></script>

</body>
</html>
