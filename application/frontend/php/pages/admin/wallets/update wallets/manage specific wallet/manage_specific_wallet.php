<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/check authentication token/check_auth_token.php';
?>

<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/user type check/admin/admin_checking.php';
?>

<?php
	if (isset($_SESSION['wallet_details'])) {
		$walletDetails = $_SESSION['wallet_details'];
	} else {

	   header('Location: /FoodFrenzy/application/frontend/php/pages/admin/wallets/update wallets/wallet list/list_of_wallets.php'); 
	}
?>


<html>
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/FoodFrenzy/application/frontend/css/same/admin/content/content.css">
	<link rel="stylesheet" type="text/css" href="/FoodFrenzy/application/frontend/css/pages/admin/wallets/update wallets/specific wallet/manage_specific_wallet.css">
</head>
<body>  
	<div class="full_screen">
		<?php
			include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/admin/side bar/side_bar.html';
		?>

		<div class="cont_container"> 
			<p id="page_title">Manage <span id="title_wallet_name"><?php echo isset($walletDetails['userName']) ? ($walletDetails['userName']) : ''; ?></span> Wallet Details</p>
			
			<div class="mid_container">
				<form action="" method="post" class="" id="wallet_form" enctype="multipart/form-data">
		
					<div class="image_part" id="">
						<img id="user_image" name="user_image" class="container_image" src="<?php echo isset($walletDetails['photoPath']) ? '/FoodFrenzy/storage/photos/users/' . urlencode(strtolower($walletDetails['wallet_user_id'])) . '/' . urlencode($walletDetails['photoPath']) : '/FoodFrenzy/storage/photos/users/default user/default_user.svg'; ?>" alt="userPicture">
						<p id = "user_id"><?php echo isset($walletDetails['wallet_user_id']) ? ($walletDetails['wallet_user_id']) : ''; ?></p>
					</div>
				
					<div class="bar_segments">
						<label for="user_name" class="form_labels">User name</label>
						<span id="col1">:</span>
						<input type="text" id="user_name" name="user_name" class="fill_bars" autocomplete="off" value="<?php echo isset($walletDetails['userName']) ? $walletDetails['userName'] : ''; ?>"><br/>
					</div>
				
					<div class="bar_segments">
						<label for="current_bal" class="form_labels">Current balance Rs.</label>
						<span id="col2">:</span>
						<input type="text" id="current_bal" name="current_bal" class="fill_bars" autocomplete="off" value="<?php echo isset($walletDetails['current_balance']) ? $walletDetails['current_balance'] : ''; ?>"><br/>
					</div>
				
					<div id="category_radios" class="bar_segments">
						<label for="transaction_type" class="form_labels">Transaction type</label>
							<span id="col3">:</span>
						<label>
							<input type="radio" name="transaction_type" value="deposits" class="radio_buttons">
							<span class="radio_label">Deposit</span>
						</label>

						<label>
							<input type="radio" name="transaction_type" value="withdraw" class="radio_buttons">
							<span class="radio_label">Withdraw</span>
							<div id="transactionAlert" class="bottom_alert_para"></div>
						</label>
					</div>
				
					<div class="bar_segments">
						<label for="cash_payment" class="form_labels">Cash payment Rs.</label>
						<span id="col4">:</span>
						<input type="text" id="cash_payment" name="cash_payment" class="fill_bars" autocomplete="off"><br/>
						<div id="cashPaymentAlert" class="bottom_alert_para"></div>
					</div>
				
				
					<div class="button-container">
						<button type="reset" class="ajax-button" id="cancel_button">Cancel</button>
						<button type="button" class="ajax-button" id="submit_button">Save</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<?php
		include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/admin/alert bar/alert.html';
	?>
	
	<?php
		include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/loading spinner/loading_spinner.html';
	?>
	
	<script src="/FoodFrenzy/application/frontend/javascript/pages/admin/wallets/update wallets/manage specific wallet/manage_specific_wallet.js"></script>
	<script src="/FoodFrenzy/application/AJAX/admin/wallets/update wallets/manage specific wallet/update_wallet_ajax.js"></script>

</body>
</html>
