<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/check authentication token/check_auth_token.php';
?>

<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/user type check/admin/admin_checking.php';
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
			<p id="page_title">Finished Orders</p>
	
			<div class="mid_container">
				<p id="nothing_alert"></p>
		
				<div class="bar_container" id="bar_container">
					<img src="/FoodFrenzy/storage/svg/system/search.svg" alt="glass" id="glass">
					<input type="text" id="searchBar" placeholder="search order ID you want to check">
				</div>
		
				<div id="finishedOrdersContainer" class="tableContainer">
					<table id="finishedOrdersTable" class ="contentTable">
						<thead>
							<tr>
								<th>Order ID</th>
								<th>User ID</th>
								<th>Email</th>
								<th>Finished Date</th>
								<th>Finished Time</th>
							</tr>
						</thead>
						
						<tbody id="finishedOrdersTableBody">
								<!-- Data rows will be added here dynamically -->
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<script src="/FoodFrenzy/application/frontend/javascript/pages/admin/orders/finished orders/order list/finished_orders.js"></script>
	<script src="/FoodFrenzy/application/frontend/javascript/ajax/admin/orders/finished orders/food list/load_finished_orders_ajax.js"></script>

</body>
</html>
