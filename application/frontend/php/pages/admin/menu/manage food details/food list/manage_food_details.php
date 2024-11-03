<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/check authentication token/check_auth_token.php';
?>

<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/user type check/admin/admin_checking.php';
?>

<?php
	$alertMessage = "";
	if (isset($_SESSION['alert'])) {
		$alertMessage = $_SESSION['alert'];
		unset($_SESSION['alert']);
	}
?>


<html>
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/FoodFrenzy/application/frontend/css/same/admin/content/content.css">
</head>
<body>  
	<div class="full_screen">
		<?php
			include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/admin/side bar/side_bar.html';
		?>

		<div class="cont_container"> 
			<p id="page_title">Manage Food Items</p>
			
			<div class="mid_container">
				<p id="nothing_alert"></p>
				
				<div class="bar_container" id="bar_container">
					<img src="/FoodFrenzy/storage/svg/system/search.svg" alt="glass" id="glass">
					<input type="text" id="searchBar" placeholder="search food you want to change">
				</div>
		
				<div class="filter" id="filter">
					<img src="/FoodFrenzy/storage/svg/system/filter.svg" id="filter_jpg">
					<select id="dropdown">
						<option value="all" id="" selected>All</option>
						<option value="main_dish" id="">Main dishes</option>
						<option value="short_eat" id="">Short eats</option>
						<option value="dessert" id="">Desserts</option>
						<option value="drink" id="">Drinks</option>
					</select>
				</div>
		
				<div id="foodContainer" class="tableContainer">
					<table id="foodTable" class ="contentTable">
						<thead>
							<tr>
								<th>Food Name</th>
								<th>Discount Price</th>
								<th>Non-Discount Price</th>
								<th>Category</th>
								<th>Availability</th>
							</tr>
						</thead>
						<tbody id="foodTableBody">
							<!-- Data rows will be added here dynamically -->
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<?php
		include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/admin/alert bar/alert.html';
	?>

	<script src="/FoodFrenzy/application/frontend/javascript/pages/admin/menu/manage food details/food list/manage_food_details.js"></script>
	<script src="/FoodFrenzy/application/frontend/javascript/ajax/admin/menu/manage food details/food list/load_food_ajax.js"></script>

</body>
</html>
