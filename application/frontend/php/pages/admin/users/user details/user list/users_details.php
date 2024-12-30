<?php
	include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin and register user/check authentication token/check_auth_token.php';
?>

<?php
	include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin and register user/user type check/admin/admin_checking.php';
?>


<html>
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/css/common/admin/content/content.css">
</head>
<body>  

	<?php
		include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin/side bar/side_bar.html';
	?>

	<div class="cont_container"> 
		<p id="page_title">Users Details</p>
	
		<div class="mid_container">
			<p id="nothing_alert"></p>
		
			<div class="bar_container" id="bar_container">
				<img src="/FoodFrenzy-Canteen-Food-Ordering-System/storage/svg/system/search.svg" alt="glass" id="glass">
				<input type="text" id="searchBar" placeholder="search user ID to find users">
			</div>
		
			<div id="usersDetailsContainer" class="tableContainer">
				<table id="usersDetailsTable" class ="contentTable">
					<thead>
						<tr>
							<th>User ID</th>
							<th>User Name</th>
							<th>Email</th>
							<th>Phone Number</th>
							<th>Registered Date</th>
						</tr>
					</thead>
					
					<tbody id="usersDetailsTableBody">
							<!-- Data rows will be added here dynamically -->
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/javascript/pages/admin/users/user details/user list/users_details.js"></script>
	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/AJAX/admin/users/user details/user list/load_users_details_ajax.js"></script>

</body>
</html>
