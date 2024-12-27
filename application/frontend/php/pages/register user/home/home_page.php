<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/check authentication token/check_auth_token.php';
?>

<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/user type check/customers/register_user_check.php';
?>

<?php
	$_SESSION['currentPage'] = 'reg_home_page';
?>

<?php 
	ob_start();
	include '/xampp/htdocs/FoodFrenzy/configuration/php/connect to database/con_server.php';
	$includedContent = ob_get_clean();
		
	$conn_status_data = json_decode($includedContent, true);
	$conn_status = $conn_status_data['connection_status'];

	if ($conn_status === "successfully") {
		
		$getUserNameQuery = "SELECT first_name, last_name FROM users WHERE user_id = ?";

		if ($stmt = $conn->prepare($getUserNameQuery)) {
		$stmt->bind_param("s", $UserId);
		$stmt->execute();
		$result = $stmt->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc(); 
				$titleUserName = ucfirst($row['first_name']). ' ' . ucfirst($row['last_name']);
			} else {
				$errorMessage = "Database user name field error: " . $conn->error; 
				redirectToErrorPage($errorMessage);        
			}
		} else {
			$errorMessage = "Database getUserNameQuery error: " . $conn->error; 
			redirectToErrorPage($errorMessage);
		}
		$stmt->close(); 
	} else {    
		$errorMessage = "Database connection failed: " . $connection_status;
		redirectToErrorPage($errorMessage);        
	}
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<title>FoodFrenzy</title>
	
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="UTF-8">
	
    <link rel="stylesheet" href="/FoodFrenzy/application/frontend/css/pages/register/home/home_page.css">
	<link rel="stylesheet" href="/FoodFrenzy/application/frontend/css/same/register and unregister/home/home_food.css">
</head>
<body>
	<div class="fullScreen">
	
		<div class="f_st_part"> 
			<?php
				include '/xampp/htdocs/FoodFrenzy/application/frontend/php/same/register/navigation/navigation_bar.php';
			?>
			<img src="/FoodFrenzy/storage/photos/system/mushroom pizza.png" alt="img" id="wallpaper"> 
			<p class="greeting">HI !<br/><span id = "title_user_full_name"><?php echo $titleUserName; ?></span><br/>Welcome to FoodFrenzy<br/>Choose your flavor</p>
			<div id="order_explore">Explore...></div>
		</div>
		
		<div class="s_nd_part" id="s_nd_part">
			<p id="product_title">PRODUCTS</p>
			<p id = "content"></p>
			<div id="foodList" class="food-list">
				<!-- Food details will be dynamically added here -->
			</div>
			<div class= "show_more_foods" id="show_more_foods">Show more...&gt;</div>
		</div>
		
		<div class="t_rd_part">
			<?php
				include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/register user/site map/site_map.html';
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

	<?php
		include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/register user/add foods alert/add_foods_alert.html';
	?>
	
	<script src = "/FoodFrenzy/application/frontend/javascript/pages/register user/home/home_page.js"></script>
	<script src = "/FoodFrenzy/application/AJAX/register user/home page/load foods/load_foods_ajax.js"></script>
	<script src = "/FoodFrenzy/application/AJAX/register user/home page/foods add to cart/food_add_to_cart_ajax.js"></script>
	<script src="/FoodFrenzy/application/AJAX/register user/check last active/last_active_ajax.js"></script>

</body>
</html>
