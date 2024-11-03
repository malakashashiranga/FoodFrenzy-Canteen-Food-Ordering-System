<?php
	session_set_cookie_params(0, '/', '', true, true);
	session_name('active_check');
	session_start();

	if  ((!isset($_SESSION['step1_completed']) || $_SESSION['step1_completed'] === false) || (!isset($_SESSION['step2_completed']) || $_SESSION['step2_completed'] === false)) {

		header("Location: /FoodFrenzy/application/frontend/php/pages/sign part/sign in/sign_in.php");
		exit;
	}
?>

<!DOCTYPE html>
<html>
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" href="/FoodFrenzy/application/frontend/css/same/sign part/sign.css">
	<link rel="stylesheet" href="/FoodFrenzy/application/frontend/css/pages/sign part/otp insert part/get_verify_code.css">
</head>
<body>

	<div class="full_screen">
		<img src="/FoodFrenzy/storage/photos/system/old food table.jpg" alt="wallpaper" id="wallpaper">
		<div class="container">
			<p id="title">Please enter the verification code that<br/> was sent to your e-mail <br/><span class="blue_letter" id="e_mail"><?php echo $_SESSION['e_mail']; ?></span></p>
			<form id="otp_form">
				<label class="otp-container">
					<input type="text" class="input_box required" maxlength="8" pattern="[0-9]+" id="verifyCode" placeholder="XXXXXXXX" autocomplete="off" name="user_otp">
				</label>
				<p class="blue_letter" id="resend" onclick="resendCode()">Resend Code</p>
			</form>
		</div>
	</div>

	<?php
		include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/register and unregister user/alert bar/alert.html';
	?>
	
	<?php
		include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/loading spinner/loading_spinner.html';
	?>
	
	<script src="/FoodFrenzy/application/frontend/javascript/pages/sign part/otp insert part/verify.js"></script>
	<script src="/FoodFrenzy/application/frontend/javascript/ajax/sign part/otp insert part/otp validation/otp_validation_ajax.js"></script>
	<script src="/FoodFrenzy/application/frontend/javascript/ajax/sign part/otp insert part/resend otp/resend_code_ajax.js"></script>

</body>
</html>
