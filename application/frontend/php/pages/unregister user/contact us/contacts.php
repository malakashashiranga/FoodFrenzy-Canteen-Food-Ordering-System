<!DOCTYPE html>
<html>
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" href="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/css/pages/unregister/contacts/contacts.css">
</head>
<body>
	<div class="full_screen">
		<?php 
			include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/unregister user/navigation/navigation_bar.html';
		?>
		
		<?php 
			include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/register and unregister user/contact us/contact.html';
		?>
	</div>
	
	<?php
		include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/register and unregister user/alert bar/alert.html';
	?>
	
	<?php
		include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/loading spinner/loading_spinner.html';
	?>
	
	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/AJAX/unregister user/contact us/form_submit_ajax.js"></script>
	
</body>
</html>