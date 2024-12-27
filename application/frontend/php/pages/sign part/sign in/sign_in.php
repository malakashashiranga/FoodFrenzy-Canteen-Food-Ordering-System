<?php
	session_set_cookie_params(0, '/', '', true, true);
	session_name('active_check');
	session_start();
	
	$alertMessage = "";
	if (isset($_SESSION['alert'])) {
		$alertMessage = $_SESSION['alert'];

		unset($_SESSION['alert']);
	}
	session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="/FoodFrenzy/application/frontend/css/pages/sign part/sign in/sign_in.css">
    <link rel="stylesheet" href="/FoodFrenzy/application/frontend/css/same/sign part/sign.css">
</head>
<body>

	<div class="full_screen">
		<img src="/FoodFrenzy/storage/photos/system/old food table.jpg" alt="wallpaper" id="wallpaper">
		<div class="container">
			<img src="/FoodFrenzy/storage/photos/system/get_food.jpeg" alt="left_wallpaper" id="mini_wallpaper">
			<div class="form">
			
				<p id="form_title">Welcome to FoodFrenzy</p>
				<p id="question">Don't have an account?&nbsp;<span class="blue_letter" onclick="toSignUpPage()">sign up</span></p><br/>
				
				<form id="loginForm">
				
					<label class="labels">
						<input type="text" class="input_box required" placeholder="Enter E-mail" id="emailInput" name="email" autocomplete="off"><br/>
						<div id="emailAlert" class="alert"></div>
					</label>
					
					<label class="labels">
						<input type="password" class="input_box required" placeholder="Enter Password" id="newPassword" name="passwordIn" autocomplete="off">
						<div id="passwordAlert" class="alert"></div>
					</label>
					
					<label class="show-password">
						<input type="checkbox" id="showPassword"> Show Password
					</label>
					
					<p class="blue_letter" id="forget_password" onclick="forgetPassword()">Forget password?</p>
					<input type="submit" value="Sign in" class="button" id="signInButton">
					
				</form>
			</div>
		</div>
	</div>
	
	<?php
		include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/register and unregister user/alert bar/alert.html';
	?>
	
	<?php
		include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/loading spinner/loading_spinner.html';
	?>
		
	<script src="/FoodFrenzy/application/frontend/javascript/same/sign part/show passwords/show_password.js"></script>
	<script src="/FoodFrenzy/application/frontend/javascript/same/sign part/jump to next/jump.js"></script>
	<script src="/FoodFrenzy/application/frontend/javascript/same/sign part/remove placeholder/placeholder.js"></script>
	<script src="/FoodFrenzy/application/frontend/javascript/same/sign part/fill bars error check/fill_bar_error_checker.js"></script>
	<script src="/FoodFrenzy/application/frontend/javascript/pages/sign part/sign in/sign_in.js"></script>
	<script src="/FoodFrenzy/application/AJAX/sign part/sign in/forget password/forget_password_ajax.js"></script>
	<script src="/FoodFrenzy/application/AJAX/sign part/sign in/valid credentials/valid_credentials_ajax.js"></script>

</body>
</html>
