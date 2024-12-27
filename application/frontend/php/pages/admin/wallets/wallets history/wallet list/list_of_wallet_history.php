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

	<?php
		include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/admin/side bar/side_bar.html';
	?>

	<div class="cont_container"> 
		<p id="page_title">Users Wallets History</p>
	
		<div class="mid_container">
			<p id="nothing_alert"></p>
			
			<div class="bar_container" id="bar_container">
				<img src="/FoodFrenzy/storage/svg/system/search.svg" alt="glass" id="glass">
				<input type="text" id="searchBar" placeholder="type user ID and search wallet history">
			</div>
		
			<div id="walletHistoryContainer" class="tableContainer">
				<table id="walletHistoryTable" class ="contentTable">
					<thead>
						<tr>
							<th>User ID</th>
							<th>Transaction Date</th>
							<th>Transaction Type</th>
							<th>Prev.&nbsp; Balance</th>
							<th>Transac.&nbsp; Balance</th>
						</tr>
					</thead>
					<tbody id="walletHistoryTableBody">
						<!-- Data rows will be added here dynamically -->
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<script src="/FoodFrenzy/application/frontend/javascript/pages/admin/wallets/wallets history/wallet list/list_of_wallet_history.js"></script>
	<script src="/FoodFrenzy/application/AJAX/admin/wallets/wallets history/wallet list/load_wallet_history_details_ajax.js"></script>

</body>
</html>
