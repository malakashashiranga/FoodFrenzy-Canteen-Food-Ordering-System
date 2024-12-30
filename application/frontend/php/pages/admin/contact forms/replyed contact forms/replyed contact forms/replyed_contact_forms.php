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
			<p id="page_title">Replyed Contact Forms</p>
	
			<div class="mid_container">
				<div id="contactFormsContainer" class="tableContainer">
					<table id="contactFormsTable" class ="contentTable" style="position: absolute; top: 7%">
						<thead>
							<tr>
								<th>Full Name</th>
								<th>Email</th>
								<th>Responsed date</th>
								<th>Responsed time</th>
							</tr>
						</thead>
				
						<tbody id="contactFormsTableBody">
							<!-- Data rows will be added here dynamically -->
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/javascript/pages/admin/contact forms/replyed contact forms/replyed contact forms/replyed_contact_forms.js"></script>
	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/AJAX/admin/contact forms/replyed contact forms/replyed contact forms/load_replyed_contact_forms_ajax.js"></script>

</body>
</html>
