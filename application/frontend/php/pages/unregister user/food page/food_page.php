<!DOCTYPE html>
<html lang="en">
<head>
	<title>FoodFrenzy / Food Page</title>
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="UTF-8">
    	
    <link rel="stylesheet" href="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/css/pages/unregister/foods/food_page.css">
	<link rel="stylesheet" href="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/css/common/register and unregister/home/home_food.css">
</head>
<body>
	<div class="fullScreen">
		<div class="f_st_part"> 
			<?php
				include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/unregister user/navigation/navigation_bar.html';
			?>
			
			<img src="/FoodFrenzy-Canteen-Food-Ordering-System/storage/photos/system/pizza_table.jpg" alt="img" id="wallpaper"> 
			<p class="greeting">Sign up now for reliable service,<br/>discounts,perks and more,<br/>only on our web site</p>
			<div id="sign_up_button">Sign up</div>
		</div>
		
		<div class="s_nd_part" id="s_nd_part">
			<?php
				include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/common/register and unregister user/food page food part/food_part.php';
			?>
		</div>
		
		<div class="t_rd_part">
			<?php
				include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/unregister user/site map/site_map.html';
			?>
		</div>
		
		<div class="f_th_part">
			<?php
				include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/register and unregister user/copyright/copyright.html';
			?>
		</div>
		
	</div>
	
	<script src="/FoodFrenzy/application/frontend/javascript/pages/unregister user/food/food_page.js"></script>	

</body>
</html>
