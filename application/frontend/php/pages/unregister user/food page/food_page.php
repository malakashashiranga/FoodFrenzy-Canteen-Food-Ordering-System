<?php
	session_set_cookie_params(0, '/', '', true, true);
	session_name('active_check');
	session_start();

	$_SESSION['currentPage'] = 'un_reg_food_page';
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>FoodFrenzy</title>
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="UTF-8">
    
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	
    <link rel="stylesheet" href="/FoodFrenzy/application/frontend/css/pages/unregister/foods/food_page.css">
	<link rel="stylesheet" href="/FoodFrenzy/application/frontend/css/same/register and unregister/home/home_food.css">
</head>
<body>
	<div class="fullScreen">
		<div class="f_st_part"> 
			<?php
				include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/unregister user/navigation/navigation_bar.html';
			?>
			
			<img src="/FoodFrenzy/storage/photos/system/pizza_table.jpg" alt="img" id="wallpaper"> 
			<p class="greeting">Sign up now for reliable service,<br/>discounts,perks and more,<br/>only on our web site</p>
			<div id="sign_up_button">Sign up</div>
		</div>
		
		<div class="s_nd_part" id="s_nd_part">
			<?php
				include '/xampp/htdocs/FoodFrenzy/application/frontend/php/same/register and unregister user/food page food part/food_part.php';
			?>
		</div>
		
		<div class="t_rd_part">
			<?php
				include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/unregister user/site map/site_map.html';
			?>
		</div>
		
		<div class="f_th_part">
			<?php
				include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/register and unregister user/copyright/copyright.html';
			?>
		</div>
		
	</div>
		
	<?php
		include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/register and unregister user/specific foods/show_specific_food.html';
	?>
	
	<script src="/FoodFrenzy/application/AJAX/unregister user/food page/load_foods_ajax.js"></script>
	<script src="/FoodFrenzy/application/frontend/javascript/pages/unregister user/food/food_page.js"></script>	

</body>
</html>
