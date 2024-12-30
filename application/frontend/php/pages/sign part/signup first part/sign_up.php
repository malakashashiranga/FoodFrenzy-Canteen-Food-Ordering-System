<!DOCTYPE html>
<html>
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" href="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/css/pages/sign part/sign up/sign_up.css">
	<link rel="stylesheet" href="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/css/common/sign part/sign.css">
</head>
<body>

	<div class="full_screen" id="full_screen">
		<img src="/FoodFrenzy-Canteen-Food-Ordering-System/storage/photos/system/old food table.jpg" alt="wallpaper" id="wallpaper">
		
		<div class="container">
			<img src="/FoodFrenzy-Canteen-Food-Ordering-System/storage/photos/system/candies.jpg" id="mini_wallpaper">
			
			<div class="form_container">
				<p id="form_title">Welcome to FoodFrenzy</p>
				<p id="question">Already signed up?&nbsp;<span class="blue_letter" onclick="toSignIpPage()">sign in</span></p><br/>

				<form id="signupForm">
					<label class="labels">
						<input type="text" class="input_box required" placeholder="First name" name="f_name" maxlength="30" autocomplete="off"><br/>
						<div id="firstNameAlert" class="alert"></div>
					</label>

					<label class="labels">
						<input type="text" class="input_box required" placeholder="Last name" name="l_name" maxlength="30" autocomplete="off"><br/>
						<div id="lastNameAlert" class="alert"></div>
					</label>

					<label class="labels">
						<input type="text" class="input_box required" placeholder="Mobile number" id="mobileNumber" pattern="[0-9]+" name="mob_number" autocomplete="off" maxlength="10"><br/>
						<div id="mobileNumberAlert" class="alert"></div>
					</label>

					<label class="labels">
						<textarea class="input_box required" placeholder="Home address" id="address_bar" name="address" maxlength="150"></textarea><br/>
						<div id="addressAlert" class="alert"></div>
					</label>
					
					<input type="submit" value="Next" class="button">
				</form>
			</div>
		</div>
	</div>
	
	<?php
		include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/loading spinner/loading_spinner.html';
	?>

	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/javascript/common/sign part/jump to next/jump.js"></script>
	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/javascript/common/sign part/remove placeholder/placeholder.js"></script>
	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/javascript/common/sign part/fill bars error check/fill_bar_error_checker.js"></script>
	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/javascript/pages/sign part/sign up/sign_up.js"></script>
	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/AJAX/sign part/sign up/form_validation_ajax.js"></script>

</body>
</html>
