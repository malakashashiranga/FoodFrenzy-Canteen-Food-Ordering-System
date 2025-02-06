<?php
	include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin and register user/check authentication token/check_auth_token.php';
?>

<?php
	include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin and register user/user type check/admin/admin_checking.php';
?>

<?php
	if (isset($_SESSION['replyed_contact_form_details'])) {
		$replyedContactForm = $_SESSION['replyed_contact_form_details'];
	} else {
	   header('Location: /FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/contact forms/replyed contact forms/replyed contact forms/replyed_contact_forms.php'); 
	}
?>


<html>
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/css/common/admin/content/content.css">
	<link rel="stylesheet" type="text/css" href="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/css/pages/admin/contact forms/replyed contact forms/replyed specific contact form/specific_replyed_contact_form_page.css">
</head>
<body>  
	<div class="full_screen">
		<?php
			include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin/side bar/side_bar.html';
		?>

		<div class="cont_container"> 
			<p id="page_title">Specific Replyed Contact Forms</p>
			<div class="mid_container">
				<form action="" method="post" class="" id="repl_contact_form">
			
					<div class="bar_segments">
						<label for="user_id" class="form_labels">User ID</label>
						<span id="col1">:</span>
						<input type="text" id="user_id" name="user_id" class="fill_bars" autocomplete="off" value="<?php echo isset($replyedContactForm['userID']) ? $replyedContactForm['userID'] : ''; ?>"><br/>
					</div>
			
					<div class="bar_segments">
						<label for="full_name" class="form_labels">Sender full name</label>
						<span id="col2">:</span>
						<input type="text" id="full_name" name="full_name" class="fill_bars" autocomplete="off" value="<?php echo isset($replyedContactForm['name']) ? $replyedContactForm['name'] : ''; ?>"><br/>
					</div>
			
					<div class="bar_segments">
						<label for="sender_email" class="form_labels">E mail</label>
						<span id="col3">:</span>
						<input type="text" id="sender_email" name="sender_email" class="fill_bars" autocomplete="off" value="<?php echo isset($replyedContactForm['senderEmail']) ? $replyedContactForm['senderEmail'] : ''; ?>"><br/>
					</div>
			
					<div class="bar_segments">
						<label for="sender_phone" class="form_labels">Phone number</label>
						<span id="col4">:</span>
						<input type="text" id="sender_phone" name="sender_phone" class="fill_bars" autocomplete="off" value="<?php echo isset($replyedContactForm['senderPhone']) ? $replyedContactForm['senderPhone'] : ''; ?>"><br/>
					</div>
			
					<div class="bar_segments">
						<label for="message" class="form_labels">Message</label>
						<span id="col5">:</span>
						<textarea id="message" name="message" class="fill_bars" autocomplete="off"><?php echo isset($replyedContactForm['message']) ? $replyedContactForm['message'] : ''; ?></textarea><br/>
					</div>
			
					<div class="bar_segments">
						<label for="rec_date_time" class="form_labels">Received Date and Time</label>
						<span id="col6">:</span>
						<input id="rec_date_time" name="rec_date_time" class="fill_bars" autocomplete="off" value="<?php echo isset($replyedContactForm['receivedDateTime']) ? $replyedContactForm['receivedDateTime'] : ''; ?>"><br/>
					</div>
			
					<div class="bar_segments">
						<label for="reply" class="form_labels">Reply message</label>
						<span id="col7">:</span>
						<textarea id="reply" name="reply" class="fill_bars" autocomplete="off"><?php echo isset($replyedContactForm['reply']) ? $replyedContactForm['reply'] : ''; ?></textarea><br/>
					</div>
			
			
					<div class="bar_segments">
						<label for="rep_date_time" class="form_labels">Replyed Date and Time</label>
						<span id="col8">:</span>
						<input id="rep_date_time" name="rep_date_time" class="fill_bars" autocomplete="off" value="<?php echo isset($replyedContactForm['replyedDateTime']) ? $replyedContactForm['replyedDateTime'] : ''; ?>"><br/>
					</div>
			
				</form>
			</div>
		</div>
	</div>

	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/javascript/pages/admin/contact forms/replyed contact forms/specific replyed contact form/specific_replyed_contact_form_page.js"></script>

</body>
</html>
