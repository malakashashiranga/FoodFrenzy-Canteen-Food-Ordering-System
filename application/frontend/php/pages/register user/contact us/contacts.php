<?php
	include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin and register user/check authentication token/check_auth_token.php';
?>

<?php
	include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin and register user/user type check/customers/register_user_check.php';
?>


<!DOCTYPE html>
<html>
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" href="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/css/pages/unregister/contacts/contacts.css">
	<link rel="stylesheet" href="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/css/pages/register/contact us/contacts.css">
</head>
<body>
	<div class="full_screen">
		<?php 
			include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/common/register/navigation/navigation_bar.php';
		?>
		
		<?php 
			include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/register and unregister user/contact us/contact.html';
		?>
	</div>
	
	<?php
		include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/register and unregister user/alert bar/alert.html';
	?>
	
	<?php
		include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/loading spinner/loading_spinner.html';
	?>
	
	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/AJAX/register user/contact us/form_submit_ajax.js"></script>
	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/AJAX/register user/check last active/last_active_ajax.js"></script>
	
</body>
</html>