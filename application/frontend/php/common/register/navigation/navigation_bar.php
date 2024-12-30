<!DOCTYPE html>
<html>
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	
	<style>

		body {	
			margin: 0 0;
		}

		.header {
			display:flex;
			align-items: center;
			left: 50%; 
			transform: translate(-50%, -50%);
			position: absolute;
			top: 8%;	
			z-index: 1;
		}

		.nav_item {
			font-family: 'Lusitana';
			font-weight: 500;
			font-size: 20px;
			border: 3.5px solid #C9C9C9;
			border-radius : 15px;
			padding: 4px;
			height: 22px;
			width: 120px;
			display: flex;
			justify-content: center;
			margin-left: 50px;
			margin-right: 50px;
			cursor: default;
		}

		.nav_item:hover {
			color: #C9C9C9;
		}

		.nav_item:active {
			color: #a9a9a9;
		}

		#navigation_logo {
		  width: 100px; 
		  height: 100px;
		  justify-content: center;  
		  margin-right: 70px;
		}

		.nav_list {
			color:#FFFFFF;
			display:flex;
		}

		.spec_bar_symbol {
			display: flex;

		}

		.symbol_item img {	

			display: flex;
			width: 32px;
			height: 32px;
			border: 2.5px solid;
			border-radius : 15px;
			padding: 3px;
			border-color: #ffffff;  
		}

		.prof_bar_user_name {
			color:#FFFFFF;
			font-size: 20px;
			font-family: Lusitana;
			margin-left:5px;
			cursor: default;
		}

		.spec_bar {
			margin-left: 70px;
		}

		.prof_bar_container {
			position: absolute;
			display: none;
			right: 0px;
		}

		.row0,.row,.row2 {
			display: flex;
			height:50px;
			width: 240px;
			background-color: #ffffff;
			align-items: center;
			cursor:default;
		}

		.row0 {
			border-radius: 10px 10px 0 0;
		}

		.row2 {
			border-radius: 0 0 10px 10px;
		}

		.prof_bar_icons {
			width: 25px;
			height: 25px;
			margin-left: 10px;
			margin-right: 15px;
		}

		.prof_bar_name {
			font-size: 21px;
			font-family: Asul;
		}
		 
		.row:hover {
			background-color: #eeeeee;
		}

		.row2:hover {
			background-color: #eeeeee;
		}

		.row:active {
			background-color: #dddddd;
		}

		.row2:active {
			background-color: #dddddd;
		}

		#prof_bar_full_name {
			font-size: 18px;
			font-family: Asul;
			text-align: center;
			font-weight: bold;
		}

		.row0 {
			justify-content:center;
		}

	</style>
</head>
<body>
	<div class="header">
		<img src="/FoodFrenzy-Canteen-Food-Ordering-System/storage/photos/system/logo.png" id="navigation_logo" alt = "logo">

		<div class="nav_list">
			<div class="nav_item" id= "menu_home">HOME</div>
			<div class="nav_item" id= "menu_products">PRODUCTS</div>
			<div class="nav_item" id= "menu_cart">CART</div>
			<div class="nav_item" id= "menu_wallet">WALLET</div>
		</div>
		
		<div class="spec_bar">
			<div class="spec_bar_symbol">
				<div class="symbol_item"><img src="/FoodFrenzy-Canteen-Food-Ordering-System/storage/svg/system/person.svg" id="main_ico"></div>
				<div class="prof_bar_user_name" id="prof_bar_user_name">
					<div class="" id="prof_bar_first_name"></div>
					<div class="" id="prof_bar_last_name"></div>
				</div>	 
			</div>
			
			<div class="prof_bar_container">
				<div class="row0">
					<span class="u_name" id="prof_bar_full_name"></span>
				</div>
				
				<div class="row" id="prof_bar_profile">
					<img src="/FoodFrenzy-Canteen-Food-Ordering-System/storage/svg/system/black_person.svg" class="prof_bar_icons">
					<div class="prof_bar_name" id = "prof_bar_profile_text">Profile Settings</div>
				</div>
				
				<div class="row" id="prof_bar_about">
					<img src="/FoodFrenzy-Canteen-Food-Ordering-System/storage/svg/system/persons.svg" class="prof_bar_icons">
					<div class="prof_bar_name" id = "prof_bar_about_text">About Us</div>
				</div>

				<div class="row" id="prof_bar_contact">
					<img src="/FoodFrenzy-Canteen-Food-Ordering-System/storage/svg/system/phone.svg" class="prof_bar_icons">
					<div class="prof_bar_name" id ="prof_bar_contact_text">Contact Us</div>
				</div>
				
				<div class="row" id="prof_bar_help">
					<img src="/FoodFrenzy-Canteen-Food-Ordering-System/storage/svg/system/question_mark.svg" class="prof_bar_icons">
					<div class="prof_bar_name" id = "prof_bar_helps_text">Helps & Supports</div>
				</div>
					
				<div class="row" id="prof_bar_terms">
					<img src="/FoodFrenzy-Canteen-Food-Ordering-System/storage/svg/system/terms & conditions.svg" class="prof_bar_icons">
					<div class="prof_bar_name" id = "prof_bar_terms_text">Terms & Conditions</div>
				</div>
									
				<div class="row2" id="prof_bar_privacy">
					<img src="/FoodFrenzy-Canteen-Food-Ordering-System/storage/svg/system/privacy & policy.svg" class="prof_bar_icons">
					<div class="prof_bar_name" id = "prof_bar_privacy_text">Privacy & Policy</div>
				</div>
			</div>
		</div>
	</div>
	
	<script>
		var menuHome = document.getElementById("menu_home");
		var menuProducts = document.getElementById("menu_products");
		var menuCart = document.getElementById("menu_cart");
		var menuWallet = document.getElementById("menu_wallet");


		var person_icon = document.getElementById("main_ico");
		var prof_bar_container = document.querySelector(".prof_bar_container");
		var userTextElement = document.querySelector(".prof_bar_user_name");



		var profileText = document.getElementById("prof_bar_profile");
		var aboutUsText = document.getElementById("prof_bar_about");
		var contactUsText = document.getElementById("prof_bar_contact");
		var helpSupportText = document.getElementById("prof_bar_help");
		var termsConditionsText = document.getElementById("prof_bar_terms");
		var privacyText = document.getElementById("prof_bar_privacy");


		menuHome.addEventListener("click", function () {
			window.location.href = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/register user/home/home_page.php";
		});
		menuProducts.addEventListener("click", function () {
			window.location.href = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/register user/foods/food_page.php";
		});
		menuCart.addEventListener("click", function () {
			window.location.href = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/register user/cart/cart.php";
		});
		menuWallet.addEventListener("click", function () {
			window.location.href = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/register user/wallet/wallet.php";
		});



		profileText.addEventListener("click", function () {
			window.location.href = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/register user/settings/settings.php";
		});
		aboutUsText.addEventListener("click", function () {
			window.location.href = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/register user/about us/about_us.php";
		});
		contactUsText.addEventListener("click", function () {
			window.location.href = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/register user/contact us/contacts.php";
		});
		helpSupportText.addEventListener("click", function () {
			window.location.href = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/register user/helps & supports/helps_&_supports.php";
		});
		termsConditionsText.addEventListener("click", function () {
			window.location.href = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/register user/terms & conditions/terms_&_conditions.php";
		});
		privacyText.addEventListener("click", function () {
			window.location.href = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/register user/privacy policy/privacy_policy.php";
		});


		function toggleElements() {
		  if (prof_bar_container.style.display === "none") {
			prof_bar_container.style.display = "block";
			person_icon.style.borderColor = "#F27622";
			userTextElement.style.textDecoration = "underline";
		  } else {
			prof_bar_container.style.display = "none";
			person_icon.style.borderColor = "#FFFFFF";
			userTextElement.style.textDecoration = "none";
		  }
		}

		person_icon.addEventListener("click", function (event) {
		  event.stopPropagation();
		  toggleElements();
		});

		userTextElement.addEventListener("click", function (event) {
		  event.stopPropagation();
		  toggleElements();
		});

		document.addEventListener("click", function (event) {
		  if (!prof_bar_container.contains(event.target)) {
			prof_bar_container.style.display = "none";
			person_icon.style.borderColor = "#FFFFFF";
			userTextElement.style.textDecoration = "none";
		  }
		});


		function showUserDetailsProfBar(first_name, last_name, fullname) {
			document.getElementById('prof_bar_first_name').innerText = first_name;
			document.getElementById('prof_bar_last_name').innerText = last_name;
			document.getElementById('prof_bar_full_name').innerText = fullname;
			
		}
	</script>

	<script>
	$(document).ready(function() {
		function profBarDetailsShow() {
			$.ajax({
				url: '/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/register user/navigation bar/profile_bar_names.php', 
				method: 'POST', 
				dataType: 'json', 
				success: function (data) {
					if (data.success) {
						showUserDetailsProfBar(data.prof_bar_first_name, data.prof_bar_last_name, data.prof_bar_full_name);
					} else {
						console.error("Profile bar data showing AJAX request failed. Message: " + response.message);
					}
				},
				error: function(xhr, status, error) {
					console.error("Profile bar sync AJAX error:", error);
				}
			});
		}
		profBarDetailsShow();
	});
	</script>

</body>
</html>
