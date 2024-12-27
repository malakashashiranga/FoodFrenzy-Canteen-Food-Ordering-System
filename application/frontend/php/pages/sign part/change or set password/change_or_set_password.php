<?php
	session_set_cookie_params(0, '/', '', true, true);
	session_name('active_check');
	session_start();
	
	$alertMessage = "";
	if (isset($_SESSION['alert'])) {
		$alertMessage = $_SESSION['alert'];
		unset($_SESSION['alert']);
	}

	if  ((!isset($_SESSION['step1_completed']) || $_SESSION['step1_completed'] === false) ||
		 (!isset($_SESSION['step2_completed']) || $_SESSION['step2_completed'] === false) ||
		 (!isset($_SESSION['step3_completed']) || $_SESSION['step3_completed'] === false)) {
		
		header("Location: /FoodFrenzy/application/frontend/php/pages/sign part/sign in/sign_in.php");
		exit;
	}
?>

<!DOCTYPE html>
<html>
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" href="/FoodFrenzy/application/frontend/css/pages/sign part/change or set password/change_or_set_password.css">
	<link rel="stylesheet" href="/FoodFrenzy/application/frontend/css/same/sign part/sign.css">
</head>
<body>

	<div class="full_screen">
		<img src="/FoodFrenzy/storage/photos/system/old food table.jpg" alt="wallpaper" id="wallpaper">
		<div class="container">
			<p class="titles" id="title"><?php echo isset($_SESSION['title']) ? $_SESSION['title'] . ' ' : ''; ?>Password</p>
			<p class="titles" id="sub_title"><em>"Your password should be a minimum of 8 to 16 characters in length<br/>and must include both uppercase and lowercase letters<br>special characters and numbers are allowed."</em></p>

			<form id="password_form">
				<label class="labels"> 
					<p class="typo">New Password</p>
					<input type="password" class="input_box required" placeholder="Password" id="newPassword" name="newPassword">&nbsp;&nbsp;&nbsp;
					<div id="newPasswordAlert" class="alert"></div>
				</label>

				<label class="labels">
					<p class="typo">Retype New Password</p>
					<input type="password" class="input_box required" placeholder="Retype Password" id="retypePassword" name="retypePassword">&nbsp;&nbsp;&nbsp;
					<div id="retypePasswordAlert" class="alert"></div>
				</label>

				<label class="show-password">
					<input type="checkbox" id="showPassword"> Show Password
				</label>

				<input type="submit" value="Confirm" class="button">
			</form>
		</div>
	</div>
	
	<?php
		include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/register and unregister user/alert bar/alert.html';
	?>
	
	<?php
		include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/loading spinner/loading_spinner.html';
	?>

	<script src="/FoodFrenzy/application/frontend/javascript/same/sign part/fill bars error check/fill_bar_error_checker.js"></script>
	<script src="/FoodFrenzy/application/frontend/javascript/same/sign part/remove placeholder/placeholder.js"></script>
	<script src="/FoodFrenzy/application/frontend/javascript/same/sign part/show passwords/show_password.js"></script>
	<script src="/FoodFrenzy/application/frontend/javascript/same/sign part/jump to next/jump.js"></script>
	<script src="/FoodFrenzy/application/AJAX/sign part/change or set password/password_validation_ajax.js"></script>

</body>
</html>