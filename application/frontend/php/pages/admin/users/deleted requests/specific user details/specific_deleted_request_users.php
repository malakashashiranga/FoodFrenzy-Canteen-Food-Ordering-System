<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/check authentication token/check_auth_token.php';
?>

<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/user type check/admin/admin_checking.php';
?>

<?php
	if (isset($_SESSION['specific_user_details'])) {
		$specificUserDetails = $_SESSION['specific_user_details'];
	} else {
	   header('Location: /FoodFrenzy/application/frontend/php/pages/admin/users/deleted requests/user list/deleted_request_users.php'); 
	}
?>


<html>
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/FoodFrenzy/application/frontend/css/same/admin/content/content.css">
	<link rel="stylesheet" type="text/css" href="/FoodFrenzy/application/frontend/css/pages/admin/users/deleted requests/specific_deleted_request_users.css">
</head>
<body>  

	<?php
		include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/admin/side bar/side_bar.html';
	?>

	<div class="cont_container"> 
		<p id="page_title">Specific User Details</p>
		<div class="mid_container">
			<form id= "specific_user_details_form" enctype="multipart/form-data">
			
				<div class="image_part" id="">
					<img id="user_image" name="user_image" class="container_image" src="<?php echo isset($specificUserDetails['userPhotoPath']) ? '/FoodFrenzy/storage/photos/users/'.urlencode(strtolower($specificUserDetails['userID'])). '/' . urlencode($specificUserDetails['userPhotoPath']) : '/FoodFrenzy/storage/photos/users/default user/default_user.svg'; ?>" alt="userImage">
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">User ID</span>
					<span id="col1" class= "doub_colon">:</span>
					<input type="text" id="user_id" name="user_id" class="fill_bars" autocomplete="off" value="<?php echo isset($specificUserDetails['userID']) ? $specificUserDetails['userID'] : ''; ?>"><br/>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">User name</span>
					<span id="col2" class= "doub_colon">:</span>
					<input type="text" id="user_name" name="user_name" class="fill_bars" autocomplete="off" value="<?php echo isset($specificUserDetails['userName']) ? $specificUserDetails['userName'] : ''; ?>"><br/>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Email</span>
					<span id="col3" class= "doub_colon">:</span>
					<input type="text" id="email" name="email" class="fill_bars" autocomplete="off" value="<?php echo isset($specificUserDetails['email']) ? $specificUserDetails['email'] : ''; ?>"><br/>

				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Phone number</span>
					<span id="col4" class= "doub_colon">:</span>
					<input type="text" id="phone_number" name="phone_number" class="fill_bars" autocomplete="off" value="<?php echo isset($specificUserDetails['mobileNumber']) ? $specificUserDetails['mobileNumber'] : ''; ?>"><br/>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Wallet balance</span>
					<span id="col5" class= "doub_colon">:</span>
					<input type="text" id="wallet_balance" name="wallet_balance" class="fill_bars" autocomplete="off" value="<?php echo isset($specificUserDetails['walletBalance']) ? $specificUserDetails['walletBalance'] : ''; ?>"><br/>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Last active date & time</span>
					<span id="col6" class= "doub_colon">:</span>
					<input type="text" id="last_active" name="last_active" class="fill_bars" autocomplete="off" value="<?php echo isset($specificUserDetails['lastOnlineTime']) ? $specificUserDetails['lastOnlineTime'] : ''; ?>"><br/>

				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Last sigin date & time</span>
					<span id="col7" class= "doub_colon">:</span>
					<input type="text" id="last_sign" name="last_sign" class="fill_bars" autocomplete="off" value="<?php echo isset($specificUserDetails['lastSignDateTime']) ? $specificUserDetails['lastSignDateTime'] : ''; ?>"><br/>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Registered date & time</span>
					<span id="col8" class= "doub_colon">:</span>
					<input type="text" id="reg_date" name="reg_date" class="fill_bars" autocomplete="off" value="<?php echo isset($specificUserDetails['lastSignDateTime']) ? $specificUserDetails['lastSignDateTime'] : ''; ?>"><br/>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Account status</span>
					<span id="col9" class= "doub_colon">:</span>
					<input type="text" id="acc_status" name="acc_status" class="fill_bars" autocomplete="off" value="<?php echo isset($specificUserDetails['accountStatus']) ? $specificUserDetails['accountStatus'] : ''; ?>"><br/>
				</div>
				
				<button id = "delete_button">Delete</button>
				
			</form>	
		</div>
	</div>
	
	<?php
		include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/admin/alert bar/alert.html';
	?>
	
	<?php
		include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/loading spinner/loading_spinner.html';
	?>

	<script src="/FoodFrenzy/application/frontend/javascript/pages/admin/users/deleted requests/specific user details/specific_deleted_request_users.js"></script>
	<script src="/FoodFrenzy/application/frontend/javascript/ajax/admin/users/deleted requests/specific user details/delete_user_details_ajax.js"></script>
	
</body>
</html>

