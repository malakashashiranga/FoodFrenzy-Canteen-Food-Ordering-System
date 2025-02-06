<?php
	include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin and register user/check authentication token/check_auth_token.php';
?>

<?php
	include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin and register user/user type check/admin/admin_checking.php';
?>

<?php
	$alertMessage = '';
	if (isset($_SESSION['alert'])) {
		$alertMessage = $_SESSION['alert'];
		unset($_SESSION['alert']);
	}
?>

<?php
	$_SESSION['currentPage'] = 'admin_settings';
?>


<html>
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/css/common/admin/content/content.css">
	<link rel="stylesheet" type="text/css" href="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/css/pages/admin/settings/settings.css">
</head>
<body>  
	<div class="full_screen">
		<?php
			include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin/side bar/side_bar.html';
		?>

		<div class="cont_container"> 
			<p id="page_title">Admin Settings</p>
	
			<div class="mid_container">
				<div id ="whole_settings_parts">
					<div class= "settings_parts" id = "profile_settings">
						<form id = "info_form">
							<p class="one_part_title">Profile Information</p>
							<div class="bar_segments">
								<label for="first_name" class="form_labels">First name :</label><br/>
								<input type="text" id="first_name" name="first_name" value="" class="fill_bars" autocomplete="off"><br/>
								<div id="firstNameAlert" class="bottom_alert_para"></div>
							</div>
			
							<div class="bar_segments">
								<label for="last_name" class="form_labels">Last name :</label><br/>
								<input type="text" id="last_name" name="last_name" value="" class="fill_bars" autocomplete="off"><br/>
								<div id="lastNameAlert" class="bottom_alert_para"></div>
							</div>
			
							<div class="bar_segments">
								<label for="phone_number" class="form_labels">Phone number :</label><br/>
								<input type="text" id="phone_number" name="phone_number" value="" class="fill_bars" autocomplete="off"  pattern="[0-9]+" maxlength="10"><br/>
								<div id="phoneNumberAlert" class="bottom_alert_para"></div>
							</div>
			
							<div class="bar_segments">
								<label for="address" class="form_labels">Address :</label><br/>
								<input type="text" id="address" name="address" value="" class="fill_bars" autocomplete="off"><br/>
								<div id="addressAlert" class="bottom_alert_para"></div>
							</div>
			
							<div class="button-container">
								<button type="button" class="buttons" id="prof_info" data-action="prof_info">Edit Details</button>
								<button type="button" class="buttons" id="conf_change_prof_info" style="display: none;" data-action="conf_prof_info">Save Details</button>
							</div>
						</form>
					</div>
		
					<div class= "settings_parts" id = "profile_settings">
						<form id = "email_form">
							<p class="one_part_title">Change E-mail</p>
							<div class="bar_segments">
								<input type="text" id="email" name="email" value="" class="fill_bars" autocomplete="off"><br/>
							</div>
			
							<div class="button-container">
								<button type="button" class="buttons" id="email_ch" data-action="email_ch">Edit Details</button>
							</div>
						</form>
					</div>
		
					<div class= "settings_parts" id = "password_settings">
						<form id="password_form">
							<p class="one_part_title">Change Password</p>
							<p class="ques" id="update_ques">Are you sure you want to update your password?</p>
							<div class="update_seg" id="password_change_section">
								<p id="update_instruct"><em>"Your new password should be a minimum of 8 to 16 characters in length<br/>and must include both uppercase and lowercase letters<br>special characters and numbers are allowed."</em></p>
          
								<div class="bar_segments">
									<label for="currentPassword" class="form_labels">Current Password :</label><br/>
									<input type="password" id="currentPassword" name="currentPassword" class="fill_bars" value=""><br/>
									<div id="currentPasswordAlert" class="bottom_alert_para"></div>
								</div>
		  
								<div class="bar_segments">
									<label for="newPassword" class="form_labels">New Password :</label><br/>
									<input type="password" id="newPassword" name="newPassword" class="fill_bars" value=""><br/>
									<div id="newPasswordAlert" class="bottom_alert_para"></div>
								</div>
		  
								<div class="bar_segments">
									<label for="confirmPassword" class="form_labels">Confirm New Password :</label><br/>
									<input type="password" id="confirmPassword" name="confirmPassword" class="fill_bars" value=""><br/>
									<div id="confirmPasswordAlert" class="bottom_alert_para"></div><br/>
									<label class="show-password">
										<input type="checkbox" id="showPassword"> Show Passwords
									</label>
								</div>
							</div>
							
							<div class="button-container">
								<button type="button" class="buttons" id="password_ch" data-action="password_ch">Change Password</label><br/>
								<button type="button" class="buttons" id="conf_change_pword" style="display: none;" data-action="conf_password_ch">Save Details</button>
							</div>
						</form>
					</div>
	
					<div class= "settings_parts" id = "logout_settings">
						<form id="log_out_system">
							<p class="one_part_title">Logout from system</p>
							<p class="ques">Are you sure you want to logout from system?</p><br/>
							<button type="button" class="buttons" id="logout_acc" data-action="logout_acc">Log Out</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php
		include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin/alert bar/alert.html';
	?>
	
	<?php
		include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/loading spinner/loading_spinner.html';
	?>

	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/javascript/pages/admin/settings/settings.js"></script>
	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/AJAX/admin/settings/show admin profile details/show_admin_details_ajax.js"></script>
	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/AJAX/admin/settings/button clicks/button_click_ajax.js"></script>
	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/AJAX/admin/settings/log out/log_out_ajax.js"></script>

</body>
</html>
