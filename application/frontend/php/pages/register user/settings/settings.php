<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/check authentication token/check_auth_token.php';
?>

<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/user type check/customers/register_user_check.php';
?>

<?php
	$alertMessage = '';
	if (isset($_SESSION['alert'])) {
		$alertMessage = $_SESSION['alert'];
		unset($_SESSION['alert']);
	}
?>

<?php
	$_SESSION['currentPage'] = 'reg_settings';
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="UTF-8">
	
	<link rel="stylesheet" href="/FoodFrenzy/application/frontend/css/pages/register/settings/settings.css">
</head>
<body>
	<div class="full_screen">
		<?php
			include '/xampp/htdocs/FoodFrenzy/application/frontend/php/same/register/navigation/navigation_bar.php';
		?>
    
		<div class="profile_pic">
			<div id="user_image">
				<img id="profile_image" src="" alt="User Profile Image">
			</div>
			
			<form action="" method="post" enctype="multipart/form-data" class="image_form" id="image_form">
				<div class="image_field">
					<input type="file" name="imageUpload" id="imageUpload" accept="image/*" style="display: none;">
					<label for="imageUpload" class="custom-button" id="imageUploadLabel">Add Image +</label>
					<input type="button" value="" class="" id="pic_cancel_button" data-action="pro_pic_cancel">
					<input type="button" value="" class="pic-ajax-button" id="pic_confirm_button" data-action="pro_pic_confirm">
				</div>
				<button id = "img_remv" data-action="pro_pic_remove" class="pic-ajax-button">Remove Picture</button>
			</form>
		</div>

		<div class="setting_container">
			<form class="form-container" id="info_form">
				<h2>Profile Information</h2>
				<p id="userId" name="userId" class="user_id_seg">User ID : <span id="user_id_block"></span></p>
		  
				<div class="bar_segments">
					<label for="first_name" class="form_labels">First name:</label><br/>
					<input type="text" id="prof_first_name" name="first_name" value="" class="info_bars" autocomplete="off"><br/>
					<div id="firstNameAlert" class="bottom_alert_para"></div>
				</div>
		  
				<div class="bar_segments">
					<label for="last_name" class="form_labels">Last name :</label><br/>
					<input type="text" id="prof_last_name" name="last_name" value="" class="info_bars" autocomplete="off"><br/>
					<div id="lastNameAlert" class="bottom_alert_para"></div>
				</div>
		  
				<div class="bar_segments">
					<label for="phone" class="form_labels">Phone :</label><br/>
					<input type="text" id="phone" name="phone" value="" class="info_bars" pattern="[0-9]+" autocomplete="off" maxlength="10"><br/>
					<div id="phoneNoAlert" class="bottom_alert_para"></div>
				</div>
		  
				<div class="bar_segments">
					<label for="address" class="form_labels">Address :</label><br/>
					<input type="text" id="address" name="address" value="" class="info_bars" autocomplete="off"><br/>
					<div id="addressAlert" class="bottom_alert_para"></div><br/>
				</div>
		  
				<button class="ajax-button" data-action="prof_info" id="prof_info">Edit Details</button>
				<button type="submit" class="ajax-button" data-action="conf_prof_info" id="conf_change_prof_info" style="display: none;">Save Details</button>
			</form>
		
			<form class="form-container" id="editEmail">
				<h2>Change E-mail</h2>
				<div class="bar_segments">
					<input type="text" id="email" name="email" value="" class="info_bars" autocomplete="off" style="margin-left: 20px;"><br/>
					<div id="emailAlert" class="bottom_alert_para"></div>
					<button class="ajax-button" data-action="email_ch" id="email_ch">Edit Email</button>
				</div>
			</form>
	
			<form class="form-container" id="password_form">
				<h2>Change Password</h2>
				<p class="ques" id="update_ques">Are you sure you want to update your password?</p>
				
				<div class="update_seg" id="password_change_section">
					<p id="update_instruct"><em>"Your new password should be a minimum of 8 to 16 characters in length<br/>and must include both uppercase and lowercase letters<br>special characters and numbers are allowed."</em></p>
          
					<div class="bar_segments">
						<label for="currentPassword" class="form_labels">Current Password :</label><br/>
						<input type="password" id="currentPassword" name="currentPassword" class="info_bars" value=""><br/>
						<div id="currentPasswordAlert" class="bottom_alert_para"></div>
					</div>
		  
					<div class="bar_segments">
						<label for="newPassword" class="form_labels">New Password :</label><br/>
						<input type="password" id="newPassword" name="newPassword" class="info_bars" value=""><br/>
						<div id="newPasswordAlert" class="bottom_alert_para"></div>
					</div>
		  
					<div class="bar_segments">
						<label for="confirmPassword" class="form_labels">Confirm New Password :</label><br/>
						<input type="password" id="confirmPassword" name="confirmPassword" class="info_bars" value=""><br/>
						<div id="confirmPasswordAlert" class="bottom_alert_para"></div><br/>
						
						<label class="show-password">
							<input type="checkbox" id="showPassword"> Show Passwords
						</label>
					</div>
				</div>
				<button class="ajax-button" data-action="password_ch" id="password_ch">Change Password</button>
				<button type="submit" class="ajax-button" data-action="conf_password_ch" id="conf_change_pword" style="display: none;">Save Password</button>
			</form>
    
			<form class="form-container" id="logOutSystem">
				<h2>Logout from system</h2>
				<p class="ques">Are you sure you want to logout from system?</p><br/>
				<button id="log_out_button" type="button" class="ajax-button" data-action="logout_acc">Log Out</button>
			</form>
		
			<form class="form-container" id="deleteAccountForm">
				<h2>Delete Account</h2>
				<p class="ques">Are you sure you want to delete your account?</p><br/>
				<button id="delete_button" type="button" class="ajax-button" data-action="delete_acc">Delete Account</button>
			</form>
		</div>
	</div>
  
	<?php
		include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/register and unregister user/alert bar/alert.html';
	?>
	
	<?php
		include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/loading spinner/loading_spinner.html';
	?>
	  
	<script src="/FoodFrenzy/application/frontend/javascript/pages/register user/settings/settings.js"></script>
	<script src="/FoodFrenzy/application/frontend/javascript/ajax/register user/settings/show user details/show_user_details_ajax.js"></script>
	<script src="/FoodFrenzy/application/frontend/javascript/ajax/register user/settings/profile picture handle/pic_form_ajax.js"></script>
	<script src="/FoodFrenzy/application/frontend/javascript/ajax/register user/settings/button clicks/buttons_click_ajax.js"></script>
	<script src="/FoodFrenzy/application/frontend/javascript/ajax/register user/settings/logout and delete account/account_delete_&_logout_ajax.js"></script>
	<script src="/FoodFrenzy/application/frontend/javascript/ajax/register user/check last active/last_active_ajax.js"></script>

</body>
</html>