<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/check authentication token/check_auth_token.php';
?>

<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/user type check/admin/admin_checking.php';
?>


<html>
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/FoodFrenzy/application/frontend/css/pages/admin/menu/add new food/add_new_food_page.css">
	<link rel="stylesheet" type="text/css" href="/FoodFrenzy/application/frontend/css/same/admin/content/content.css">
</head>
<body>  
	<div class="full_screen">
		<?php
			include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/admin/side bar/side_bar.html';
		?>

		<div class="cont_container"> 
			<p id="page_title">Add Food Item</p>
			
			<div class="mid_container">
				<form action="" method="post" class="" id="food_form" enctype="multipart/form-data">
				
					<div class="image_part" id="">
						<img id="food_image" class="container_image" src="/FoodFrenzy/storage/svg/system/question_mark.svg" alt="foodImage">
					</div>
			
					<div class="fill_part" id="">
						<div class="bar_segments">
							<label for="food_name" class="form_labels">Food Picture</label>
							<span id="col1">:</span>
							<input type="file" name="imageUpload" id="imageUpload" accept="image/*"><br>
							<div id="foodPictureAlert" class="bottom_alert_para"></div>
						</div>
			
						<div class="bar_segments">
							<label for="food_name" class="form_labels">Food name</label>
							<span id="col2">:</span>
							<input type="text" id="food_name" name="food_name" class="fill_bars" autocomplete="off"><br/>
							<div id="foodNameAlert" class="bottom_alert_para"></div>
						</div>
				
						<div class="bar_segments">
							<label for="discount_price" class="form_labels">Dis-countable price Rs.</label>
							<span id="col3">:</span>
							<input type="text" id="discount_price" name="discount_price" class="fill_bars" autocomplete="off"><br/>
							<div id="discountPriceAlert" class="bottom_alert_para"></div>
						</div>
				
						<div class="bar_segments">
							<label for="non_discount_price" class="form_labels">Non dis-countable price Rs.</label>
							<span id="col4">:</span>
							<input type="text" id="non_discount_price" name="non_discount_price" class="fill_bars" autocomplete="off"><br/>
							<div id="nonDiscountPriceAlert" class="bottom_alert_para"></div>
						</div>
				
						<div class="bar_segments" id="food_details_segment">
							<label for="food_details" class="form_labels">Details</label>
							<span id="col5">:</span>
							<textarea id="food_details" name="food_details" class="fill_bars" autocomplete="off"></textarea>
							<div id="foodDetailsAlert" class="bottom_alert_para"></div>
						</div>
				
						<div id="category_radios" class="bar_segments">
							<label for="food_category" class="form_labels">Category</label>
							<span id="col6">:</span>
							
							<label>
								<input type="radio" name="food_category" value="main_dish" class="radio_buttons"> <span class="radio_label">main dish</span>
							</label>
							
							<label>
								<input type="radio" name="food_category" value="short_eat" class="radio_buttons"> <span class="radio_label">short eat</span>
							</label>
							
							<label>
								<input type="radio" name="food_category" value="dessert" class="radio_buttons"> <span class="radio_label">dessert</span>
							</label>
							
							<label>
								<input type="radio" name="food_category" value="drink" class="radio_buttons"> <span class="radio_label">drink</span>
							</label>
							
							<div id="categoryAlert" class="bottom_alert_para"></div>
						</div>
				
						<div id="availability_radios" class="bar_segments">
							<label for="food_category" class="form_labels">Availability</label>
							<span id="col7">:</span>
							
							<label>
								<input type="radio" name="availability" value="available" class="radio_buttons"> <span class="radio_label">available</span>
							</label>
							
							<label>
								<input type="radio" name="availability" value="unavailable" class="radio_buttons"> <span class="radio_label">unavailable</span>
							</label>
							
							<div id="availabilityAlert" class="bottom_alert_para"></div>
						</div>
				
						<div class="button-container">
							<button type="button" class="cancel_button" id="cancel_button">Cancel</button>
							<button type="button" class="submit_button" id="submit_button">Submit</button>
						</div>
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
	
	<script src="/FoodFrenzy/application/frontend/javascript/pages/admin/menu/add new food/add_new_food_page.js"></script>
	<script src="/FoodFrenzy/application/frontend/javascript/ajax/admin/menu/add new food/submit_food_ajax.js"></script>
	
</body>
</html>
