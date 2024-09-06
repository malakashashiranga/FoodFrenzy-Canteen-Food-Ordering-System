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
	<link rel="stylesheet" type="text/css" href="/FoodFrenzy/application/frontend/css/pages/admin/wallets/update wallets/wallet list/list_of_wallet.css">
</head>
<body>  
	<div class="full_screen">
		<?php
			include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/admin/side bar/side_bar.html';
		?>

		<div class="cont_container"> 
			<p id="page_title">Manage Users Wallets</p>
	
			<div class="mid_container">
				<div class= "totalWalletBalance">
					<p id= "totalWalletBalanceTitle">Users Wallets Balance</p>
					<p id= "totalWalletCount"></p>
				</div>
				
				<div class="secondWalletPart">
					<div class="secondWalletPart">
						<p id="nothing_alert" style="position: absolute; top: 100px; transform: translate(-50%, 0%);"></p>
						
						<div class="bar_container" id="bar_container">
							<img src="/FoodFrenzy/storage/svg/system/search.svg" alt="glass" id="glass">
							<input type="text" id="searchBar" placeholder="type user ID and search wallet">
						</div>
		
						<div id="walletContainer" class="tableContainer">
							<table id="walletTable" class ="contentTable">
								<thead>
									<tr>
										<th>User ID</th>
										<th>User Name</th>
										<th>Last Transaction Date</th>
										<th>Transaction Type</th>
										<th>Current Bal.</th>
									</tr>
								</thead>
								<tbody id="walletTableBody">
									<!-- Data rows will be added here dynamically -->
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="/FoodFrenzy/application/frontend/javascript/pages/admin/wallets/update wallets/wallet lists/list_of_wallet.js"></script>
	<script src="/FoodFrenzy/application/frontend/javascript/ajax/admin/wallets/update wallets/wallet list/load_wallet_details_ajax.js"></script>

</body>
</html>
