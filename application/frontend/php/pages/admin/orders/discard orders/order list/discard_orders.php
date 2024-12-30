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
	<div class="full_screen">
		<?php
			include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin/side bar/side_bar.html';
		?>
		
		<div class="cont_container"> 
			<p id="page_title">Discard Orders</p>
	
			<div class="mid_container">
				<p id="nothing_alert"></p>
		
				<div class="bar_container" id="bar_container">
					<img src="/FoodFrenzy-Canteen-Food-Ordering-System/storage/svg/system/search.svg" alt="glass" id="glass">
					<input type="text" id="searchBar" placeholder="search order ID you want to check">
				</div>
		
				<div id="discardOrdersContainer" class="tableContainer">
					<table id="discardOrdersTable" class ="contentTable">
						<thead>
							<tr>
								<th>Order ID</th>
								<th>User ID</th>
								<th>Email</th>
								<th>Ordered Date</th>
								<th>Ordered Time</th>
							</tr>
						</thead>
						
						<tbody id="discardOrdersTableBody">
								<!-- Data rows will be added here dynamically -->
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/javascript/pages/admin/orders/discard orders/order list/discard_orders.js"></script>
	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/AJAX/admin/orders/discard orders/order list/load_discard_orders_ajax.js"></script>

</body>
</html>
