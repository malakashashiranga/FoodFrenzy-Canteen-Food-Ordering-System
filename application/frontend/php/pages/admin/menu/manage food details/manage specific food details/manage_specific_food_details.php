<?php
	include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin and register user/check authentication token/check_auth_token.php';
?>

<?php
	include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin and register user/user type check/admin/admin_checking.php';
?>

<?php
	if (isset($_SESSION['food_details'])) {
		$foodDetails = $_SESSION['food_details'];
	} else {
	   header('Location: /FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/menu/manage food details/food list/manage_food_details.php'); 
	}
?>


<html>
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/css/common/admin/content/content.css">
	<link rel="stylesheet" type="text/css" href="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/css/pages/admin/menu/manage specific food/manage_specific_food.css">
</head>
<body>  
	<div class="full_screen">
		<?php
			include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin/side bar/side_bar.html';
		?>

		<div class="cont_container"> 
			<p id="page_title">Manage <span id="title_food_name"><?php echo isset($foodDetails['foodName']) ? ($foodDetails['foodName']) : ''; ?></span> Details</p>
			
			<div class="mid_container">
				<form action="" method="post" class="" id="food_form" enctype="multipart/form-data">
					<button type="button" class="ajax-button" id="delete_button">Delete</button>
					
					<div class="image_part" id="">
						<img id="food_image" name= "food_image" class="container_image" src="<?php echo isset($foodDetails['photoPath']) ? '/FoodFrenzy-Canteen-Food-Ordering-System/storage/photos/foods/' .lcfirst($foodDetails['foodName']). '/'. $foodDetails['photoPath'] : '/FoodFrenzy-Canteen-Food-Ordering-System/storage/photos/foods/default/question_mark.svg'; ?>" alt="foodImage">
					</div>
				
					<div class="fill_part" id="">
						<div class="bar_segments" id="image_upload_segment">
							<label for="imageUpload" class="form_labels">Food Picture</label>
							<span id="col1">:</span>
							<input type="file" name="imageUpload" id="imageUpload" accept="image/*"><br>
							<div id="foodPictureAlert" class="bottom_alert_para"></div>
						</div>
				
						<div class="bar_segments">
							<label for="food_name" class="form_labels">Food name</label>
							<span id="col2">:</span>
							<input type="text" id="food_name" name="food_name" class="fill_bars" autocomplete="off" value="<?php echo isset($foodDetails['foodName']) ? $foodDetails['foodName'] : ''; ?>"><br/>
							<div id="foodNameAlert" class="bottom_alert_para"></div>
						</div>
					
						<div class="bar_segments">
							<label for="discount_price" class="form_labels">Dis-countable price Rs.</label>
							<span id="col3">:</span>
							<input type="text" id="discount_price" name="discount_price" class="fill_bars" autocomplete="off" value="<?php echo isset($foodDetails['discountPrice']) ? $foodDetails['discountPrice'] : ''; ?>"><br/>
							<div id="discountPriceAlert" class="bottom_alert_para"></div>
						</div>
					
						<div class="bar_segments">
							<label for="non_discount_price" class="form_labels">Non dis-countable price Rs.</label>
							<span id="col4">:</span>
							<input type="text" id="non_discount_price" name="non_discount_price" class="fill_bars" autocomplete="off" value="<?php echo isset($foodDetails['nonDiscountPrice']) ? $foodDetails['nonDiscountPrice'] : ''; ?>"><br/>
							<div id="nonDiscountPriceAlert" class="bottom_alert_para"></div>
						</div>
					
						<div class="bar_segments" id="food_details_segment">
							<label for="food_details" class="form_labels">Details</label>
							<span id="col5">:</span>
							<textarea id="food_details" name="food_details" class="fill_bars" autocomplete="off"><?php echo isset($foodDetails['details']) ? $foodDetails['details'] : ''; ?></textarea>
							<div id="foodDetailsAlert" class="bottom_alert_para"></div>
						</div>

						<div id="category_radios" class="bar_segments">
							<label for="food_category" class="form_labels">Category</label>
								<span id="col6">:</span>
							<label>
								<input type="radio" name="food_category" value="main_dish" class="radio_buttons" <?php echo isset($foodDetails['category']) && $foodDetails['category'] === 'main_dish' ? 'checked' : ''; ?>>
								<span class="radio_label">main dish</span>
							</label>

							<label>
								<input type="radio" name="food_category" value="short_eat" class="radio_buttons" <?php echo isset($foodDetails['category']) && $foodDetails['category'] === 'short_eat' ? 'checked' : ''; ?>>
								<span class="radio_label">short eat</span>
							</label>

							<label>
								<input type="radio" name="food_category" value="dessert" class="radio_buttons" <?php echo isset($foodDetails['category']) && $foodDetails['category'] === 'dessert' ? 'checked' : ''; ?>>
								<span class="radio_label">dessert</span>
							</label>

							<label>
								<input type="radio" name="food_category" value="drink" class="radio_buttons" <?php echo isset($foodDetails['category']) && $foodDetails['category'] === 'drink' ? 'checked' : ''; ?>>
								<span class="radio_label">drink</span>
							</label>
							<div id="categoryAlert" class="bottom_alert_para"></div>
						</div>

						<div id="availability_radios" class="bar_segments">
							<label for="availability" class="form_labels">Availability</label>
								<span id="col7">:</span>
							<label>
								<input type="radio" name="availability" value="available" class="radio_buttons" <?php echo isset($foodDetails['availability']) && $foodDetails['availability'] === 'available' ? 'checked' : ''; ?>>
								<span class="radio_label">available</span>
							</label>
			
							<label>
								<input type="radio" name="availability" value="unavailable" class="radio_buttons" <?php echo isset($foodDetails['availability']) && $foodDetails['availability'] === 'unavailable' ? 'checked' : ''; ?>>
								<span class="radio_label">unavailable</span>
							</label>
							<div id="availabilityAlert" class="bottom_alert_para"></div>
						</div>

					
						<div class="button-container">
							<button type="button" class="ajax-button" id="edit_button">Change food details</button>
							<button type="button" class="ajax-button" id="cancel_button">Cancel</button>
							<button type="button" class="ajax-button" id="submit_button">Submit</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<?php
		include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin/alert bar/alert.html';
	?>
	
	<?php
		include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/loading spinner/loading_spinner.html';
	?>

	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/javascript/pages/admin/menu/manage food details/manage specific food details/manage_specific_food_details.js"></script>
	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/AJAX/admin/menu/manage food details/manage specific food/update food/update_food_ajax.js"></script>
	<script src="/FoodFrenzy-Canteen-Food-Ordering-System/application/AJAX/admin/menu/manage food details/manage specific food/delete food/delete_food_ajax.js"></script>

</body>
</html>
