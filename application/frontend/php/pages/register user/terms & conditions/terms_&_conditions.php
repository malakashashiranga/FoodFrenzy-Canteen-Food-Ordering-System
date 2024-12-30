<?php
	include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin and register user/check authentication token/check_auth_token.php';
?>

<?php
	include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin and register user/user type check/customers/register_user_check.php';
?>


<!DOCTYPE html>
<html>
<head>
	<link rel= "stylesheet" href = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/css/pages/register/terms & conditions/terms_&_conditions.css">
	<link rel= "stylesheet" href = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/css/common/register/hide header/hide_header.css">
	<link rel="stylesheet" href="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/css/common/register and unregister/help, terms, privacy/style.css">
</head>
<body>
	<?php
		include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/common/register/navigation/navigation_bar.php';
	?>
	
	<?php 
		include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/register and unregister user/terms & conditions/terms_&_conditions.html';
	?>

	<script src= "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/javascript/pages/register user/terms & conditions/terms_&_conditions.js"></script>
	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/AJAX/register user/check last active/last_active_ajax.js"></script>
	
</body>
</html>