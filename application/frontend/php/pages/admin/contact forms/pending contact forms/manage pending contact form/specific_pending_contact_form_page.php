<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/check authentication token/check_auth_token.php';
?>

<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/user type check/admin/admin_checking.php';
?>

<?php
	if (isset($_SESSION['pending_contact_form_details'])) {
		$pendingContactForm = $_SESSION['pending_contact_form_details'];
	} else {
	   header('Location: /FoodFrenzy/application/frontend/php/pages/admin/contact forms/pending contact forms/pending contact forms/pending_contact_forms_page.php'); 
	}
?>


<html>
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/FoodFrenzy/application/frontend/css/same/admin/content/content.css">
	<link rel="stylesheet" type="text/css" href="/FoodFrenzy/application/frontend/css/pages/admin/contact forms/pending contact forms/specific pending contact form/specific_pending_contact_form_page.css">
</head>
<body>  
	<div class="full_screen">
		<?php
			include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/admin/side bar/side_bar.html';
		?>

		<div class="cont_container"> 
			<p id="page_title">Manage Pending Contact Forms</p>
			<div class="mid_container">
			
				<form action="" method="post" class="" id="pend_contact_form">
					<div class="bar_segments">
						<label for="user_id" class="form_labels">User ID</label>
						<span id="col1">:</span>
						<input type="text" id="user_id" name="user_id" class="fill_bars" autocomplete="off" value="<?php echo isset($pendingContactForm['userID']) ? $pendingContactForm['userID'] : ''; ?>"><br/>
					</div>
				
					<div class="bar_segments">
						<label for="full_name" class="form_labels">Sender full name</label>
						<span id="col2">:</span>
						<input type="text" id="full_name" name="full_name" class="fill_bars" autocomplete="off" value="<?php echo isset($pendingContactForm['name']) ? $pendingContactForm['name'] : ''; ?>"><br/>
					</div>
				
					<div class="bar_segments">
						<label for="sender_email" class="form_labels">E mail</label>
						<span id="col3">:</span>
						<input type="text" id="sender_email" name="sender_email" class="fill_bars" autocomplete="off" value="<?php echo isset($pendingContactForm['senderEmail']) ? $pendingContactForm['senderEmail'] : ''; ?>"><br/>
					</div>
				
					<div class="bar_segments">
						<label for="sender_phone" class="form_labels">Phone number</label>
						<span id="col4">:</span>
						<input type="text" id="sender_phone" name="sender_phone" class="fill_bars" autocomplete="off" value="<?php echo isset($pendingContactForm['senderPhone']) ? $pendingContactForm['senderPhone'] : ''; ?>"><br/>
					</div>
				
					<div class="bar_segments">
						<label for="message" class="form_labels">Message</label>
						<span id="col5">:</span>
						<textarea id="message" name="message" class="fill_bars" autocomplete="off"><?php echo isset($pendingContactForm['message']) ? $pendingContactForm['message'] : ''; ?></textarea><br/>
					</div>
				
					<div class="bar_segments">
						<label for="date_time" class="form_labels">Date and Time</label>
						<span id="col6">:</span>
						<input id="date_time" name="date_time" class="fill_bars" autocomplete="off" value="<?php echo isset($pendingContactForm['dateTime']) ? $pendingContactForm['dateTime'] : ''; ?>"><br/>
					</div>
				
					<div class="bar_segments">
						<label for="reply" class="form_labels">Reply message</label>
						<span id="col7">:</span>
						<textarea id="reply" name="reply" class="fill_bars" autocomplete="off"></textarea><br/>
						<div id="replyAlert" class="bottom_alert_para"></div>
						<div id="subjectAlert" class="bottom_alert_para">*Please only type the reply email subject in the provided text box. After the period (dot/.) character, the following characters will be automatically capitalized by the system.</div>
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
	
<script src="/FoodFrenzy/application/frontend/javascript/pages/admin/contact forms/pending contact forms/manage specific contact form/specific_pending_contact_form_page.js"></script>
<script src="/FoodFrenzy/application/AJAX/admin/contact forms/pending contact forms/specific contact form/reply_specific_pending_contact_form_ajax.js"></script>

</body>
</html>
