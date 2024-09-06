<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/check authentication token/check_auth_token.php';
?>

<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/user type check/admin/admin_checking.php';
?>

<?php
	if (isset($_SESSION['specific_wallet_history_details'])) {
		$specificWalletHistoryDetails = $_SESSION['specific_wallet_history_details'];
	} else {
	   header('Location: /FoodFrenzy/application/frontend/php/pages/admin/wallets/wallets history/wallet list/list_of_wallet_history.php'); 
	}
?>


<html>
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/FoodFrenzy/application/frontend/css/same/admin/content/content.css">
	<link rel="stylesheet" type="text/css" href="/FoodFrenzy/application/frontend/css/pages/admin/wallets/wallets history/specific wallet history/specific_wallet_history.css">
</head>
<body>  

	<?php
		include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/admin/side bar/side_bar.html';
	?>

	<div class="cont_container"> 
		<p id="page_title">Specific User Wallet History</p>
		<div class="mid_container">
			<div id= "specific_discard_order_form">
				<div class="image_part" id="">
					<img id="user_image" name="user_image" class="container_image" src="<?php echo isset($specificWalletHistoryDetails['userPhotoPath']) ? '/FoodFrenzy/storage/photos/users/'.urlencode(strtolower($specificWalletHistoryDetails['userID'])). '/' . urlencode($specificWalletHistoryDetails['userPhotoPath']) : '/FoodFrenzy/storage/photos/users/default user/default_user.svg'; ?>" alt="foodImage">
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">User ID</span>
					<span id="col1" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($specificWalletHistoryDetails['userID']) ? $specificWalletHistoryDetails['userID'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">User name</span>
					<span id="col2" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($specificWalletHistoryDetails['userName']) ? $specificWalletHistoryDetails['userName'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Transaction date</span>
					<span id="col3" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($specificWalletHistoryDetails['transactionDate']) ? $specificWalletHistoryDetails['transactionDate'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Transaction time</span>
					<span id="col4" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($specificWalletHistoryDetails['transactionTime']) ? $specificWalletHistoryDetails['transactionTime'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Transaction method</span>
					<span id="col5" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($specificWalletHistoryDetails['transactionMethod']) ? $specificWalletHistoryDetails['transactionMethod'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Previous balance</span>
					<span id="col6" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($specificWalletHistoryDetails['pastBalance']) ? $specificWalletHistoryDetails['pastBalance'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Transaction amount</span>
					<span id="col7" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($specificWalletHistoryDetails['transactionAmount']) ? $specificWalletHistoryDetails['transactionAmount'] : ''; ?></span>
				</div>
				
				<div class="bar_segments">
					<span class="form_labels">Updated balance</span>
					<span id="col8" class= "doub_colon">:</span>
					<span class = "plainTextDetails"><?php echo isset($specificWalletHistoryDetails['newBalance']) ? $specificWalletHistoryDetails['newBalance'] : ''; ?></span>
				</div>
			</div>	
		</div>
	</div>

	<script src="/FoodFrenzy/application/frontend/javascript/pages/admin/wallets/wallets history/specific wallet history/specific_wallet_history.js"></script>

</body>
</html>

