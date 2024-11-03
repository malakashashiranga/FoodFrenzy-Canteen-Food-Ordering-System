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
	   header('Location: /FoodFrenzy/application/frontend/php/pages/admin/users/user details/user list/users_details.php'); 
	}
?>


<html>
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/FoodFrenzy/application/frontend/css/same/admin/content/content.css">
	<link rel="stylesheet" type="text/css" href="/FoodFrenzy/application/frontend/css/pages/admin/users/user details/specific user details/specific_user_details.css">
</head>
<body>  

	<?php
		include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/admin/side bar/side_bar.html';
	?>

	<div class="cont_container"> 
		<p id="page_title">Specific User Details</p>
		<div class="mid_container">
			<div id= "specific_user_details_form">
				<div class="image_part" id="">
					<img id="user_image" name="user_image" class="container_image" src="<?php echo isset($specificUserDetails['userPhotoPath']) ? '/FoodFrenzy/storage/photos/users/'.urlencode(strtolower($specificUserDetails['userID'])). '/' . urlencode($specificUserDetails['userPhotoPath']) : '/FoodFrenzy/storage/photos/users/default user/default_user.svg'; ?>" alt="userImage">
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">User ID</span>
					<span id="col1" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($specificUserDetails['userID']) ? $specificUserDetails['userID'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">User name</span>
					<span id="col2" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($specificUserDetails['userName']) ? $specificUserDetails['userName'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Email</span>
					<span id="col3" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($specificUserDetails['email']) ? $specificUserDetails['email'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Phone number</span>
					<span id="col4" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($specificUserDetails['mobileNumber']) ? $specificUserDetails['mobileNumber'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Last active date & time</span>
					<span id="col5" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($specificUserDetails['lastOnlineTime']) ? $specificUserDetails['lastOnlineTime'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Last sigin date & time</span>
					<span id="col6" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($specificUserDetails['lastSignDateTime']) ? $specificUserDetails['lastSignDateTime'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Registered date & time</span>
					<span id="col7" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($specificUserDetails['lastSignDateTime']) ? $specificUserDetails['lastSignDateTime'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Account status</span>
					<span id="col8" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($specificUserDetails['accountStatus']) ? $specificUserDetails['accountStatus'] : ''; ?></span>
				</div>
			</div>	
		</div>
	</div>

	<script src="/FoodFrenzy/application/frontend/javascript/pages/admin/users/user details/specific user details/specific_user_details.js"></script>

</body>
</html>

