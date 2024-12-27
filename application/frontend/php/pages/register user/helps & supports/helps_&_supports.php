<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/check authentication token/check_auth_token.php';
?>

<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/user type check/customers/register_user_check.php';
?>


<!DOCTYPE html>
<html>
<head>
	<link rel= "stylesheet" href = "/FoodFrenzy/application/frontend/css/pages/register/helps & supports/helps_&_supports.css">
	<link rel= "stylesheet" href = "/FoodFrenzy/application/frontend/css/same/register/hide header/hide_header.css">
	<link rel="stylesheet" href="/FoodFrenzy/application/frontend/css/same/register and unregister/help, terms, privacy/style.css">
</head>
<body>
	<?php
		include '/xampp/htdocs/FoodFrenzy/application/frontend/php/same/register/navigation/navigation_bar.php';
	?>
	
	<?php 
		include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/register and unregister user/helps & supports/helps_&_supports.html';
	?>

	<script src = "/FoodFrenzy/application/frontend/javascript/pages/register user/helps & supports/helps_&_supports.js"></script>
	<script src="/FoodFrenzy/application/AJAX/register user/check last active/last_active_ajax.js"></script>
</body>
</html>