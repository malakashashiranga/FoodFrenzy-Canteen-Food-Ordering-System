<?php
	session_set_cookie_params(0, '/', '', true, true);
	session_name('active_check');
	session_start();

	$_SESSION['currentPage'] = 'un_reg_home_page';
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>FoodFrenzy</title>
	
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="UTF-8">
	
    <link rel="stylesheet" href="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/css/pages/unregister/home/home_page.css">
	<link rel="stylesheet" href="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/css/common/register and unregister/home/home_food.css">
</head>
<body>
	<div class="fullScreen">
	
		<div class="f_st_part"> 
			<?php
				include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/unregister user/navigation/navigation_bar.html';
			?>
			<img src="/FoodFrenzy-Canteen-Food-Ordering-System/storage/photos/system/food_table.jpg" alt="img" id="wallpaper"> 
			<p class="greeting">Welcome<br/> to<br/> FoodFrenzy</p>
			<div id="order_button_middle">ORDER</div>

			
			<div class="dropdown" id="drop_part">
				<p id="drop_product">PRODUCTS</p>
				<img src="/FoodFrenzy-Canteen-Food-Ordering-System/storage/svg/system/mini white dropdown.svg" id="dropdown" alt="drop">
			</div>
			
		</div>
		
		<div class="s_nd_part" id="s_nd_part">
			<div class="close">
				<img src="/FoodFrenzy-Canteen-Food-Ordering-System/storage/svg/system/mini white dropdown.svg" id="close" alt="close">
				<p id="close_product">PRODUCTS</p>
			</div>
			
			<p id = "content"></p>
			
			<div id="foodList" class="food-list">
				<!-- Food details will be dynamically added here -->
			</div>
			<button class= "show_more_foods" id="show_more_foods">Show more...&gt;</button>
		</div>
	
		<div class="t_rd_part" id="t_rd_part">
			<p id="about_title">ABOUT US</p>
			<div class="about_con">
				<img src="/FoodFrenzy-Canteen-Food-Ordering-System/storage/photos/system/canteen.png" alt="canteen" id="canteen_phto">
				<p id="about_content">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Food Frenzy is your one-stop online portal for ordering delicious and convenient canteen meals! We are passionate about providing a hassle-free way to enjoy a variety of food options at the comfort of your desk.
									<br/><br/>
									<span style="font-size: 24px; font-weight: bold;">Our Mission</span>
									<br>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Our mission is to revolutionize the canteen food experience by offering:
									Convenience: Order your food anytime, anywhere through our user-friendly online system. Say goodbye to long lines and wasted lunch breaks!
									Variety: We offer a wide selection of dishes to cater to all ......................
				</p>
				<button id="read_more_button">Read More...&nbsp;&gt;</button>
			</div>
		</div>
		
		<div class="f_th_part">
			<?php
				include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/unregister user/site map/site_map.html';
			?>
		</div>
		
		<div class="fi_th_part"> 
			<?php
				include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/register and unregister user/copyright/copyright.html';
			?>
		</div>
	</div>

	<?php
		include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/register and unregister user/specific foods/show_specific_food.html';
	?>


	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/javascript/pages/unregister user/home/home_page.js"></script>
	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/AJAX/unregister user/home page/load_foods_ajax.js"></script>	

</body>
</html>
