<?php
	session_set_cookie_params(0, '/', '', true, true);
	session_name('active_check');
	session_start();

	if (!isset($_SESSION['step1_completed']) || $_SESSION['step1_completed'] === false) {
		header("Location: /FoodFrenzy/application/frontend/php/pages/sign part/sign in/sign_in.php");
		exit;
	} 
?>


<!DOCTYPE html>
<html>
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" href="/FoodFrenzy/application/frontend/css/pages/sign part/ask email part/insert_email.css">
	<link rel="stylesheet" href="/FoodFrenzy/application/frontend/css/same/sign part/sign.css">
</head>
<body>
	<div class="full_screen">
		<img src="/FoodFrenzy/storage/photos/system/old food table.jpg" alt="wallpaper" id="wallpaper">
		
		<div class="container">
			<form id="emailForm">
				<label class="label">
				<p id="title">Enter your E-mail</p>
				<input type="text" id="emailInput" class="input_box required" placeholder="Enter E-mail" autocomplete="off" name="e_mail"><br/>
				<div id="emailAlert" class="alert"></div>
				</label>
				<input type="submit" value="Send Code" class="button">
			</form>
		</div>
	</div>
	
	<?php
		include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/register and unregister user/alert bar/alert.html';
	?>
	
	<?php
		include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/loading spinner/loading_spinner.html';
	?>

	<script src="/FoodFrenzy/application/frontend/javascript/same/sign part/remove placeholder/placeholder.js"></script>
	<script src="/FoodFrenzy/application/frontend/javascript/same/sign part/fill bars error check/fill_bar_error_checker.js"></script>
	<script src="/FoodFrenzy/application/frontend/javascript/ajax/sign part/ask email/email_validation_ajax.js"></script>
	<script src="/FoodFrenzy/application/frontend/javascript/same/register and unregister user/prevent scroll bar/prevent_scroll_bar.js"></script>

</body>
</html>
