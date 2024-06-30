<!DOCTYPE html>
<html>
<head>
	<style>
		body{
			margin: 0 0;
		}

		.container {
			height: 300px;
			width: 100%;
			background: linear-gradient(90deg, rgba(18, 98, 157, 0.6) 0%, rgba(231, 102, 29, 0.6) 0%, rgba(140, 131, 54, 0.6) 100%);
			display: flex;  
			flex-direction: column; 
			align-items: center; 
		}

		#product_title {
			font-size: 32px;
			text-align: center; 
			font-weight: bold;	
			margin-bottom: 50px;
		}
			
		#glass {
			width: 40px;
			height: 40px;
			position: absolute;
		}
			
		#searchBar {
			text-align: 60px;
			background-color: #e0e0e0; 
			width: 300px;
			border-radius: 50px 50px 50px 50px;
			outline: none;
			border: none;
			padding: 10px 10px 10px 50px;
			font-size: 18px;
		}
		
		#searchBar:focus {
			background-color: #f0f0f0; 
		}

		#filter_jpg {
			width: 30px;
			height: 30px;
		}

		.filter {
			display: flex;
			align-items: center;
			position: absolute;
			left: 80px;
			margin-top: 180px;
		}

		#dropdown {
			height: 25px;
			border-radius: 5px;
			font-size: 14px;
			min-width: 80px;
		}

		#content {
			font-size: 25px;
			font-weight: bold;
			position: absolute;
			margin-top: 220px;
			display: none;
		}
			
		#foodList {
			margin-top: 220px;
		}
			
		.segment_skip_cont {
			transform: translate(0%, 1600px);
			display: flex;
			justify-content: center;
			align-items: center;
			display: none;
		}
			
		.skip-segment {
			font-size: 24px;
			padding: 5px 12px 5px 12px;
			font-weight: bold;
			border-radius: 5px;
			background-color: #d9d9d9;
			border: 1px solid #000000;
		}
			
		.skip-segment:hover {
			background-color: #c9c9c9;
		}
			
		.skip-segment:active {
			background-color: #d9d9d9;
		}
			
		.skip_symbol {
			width: 50px;
			height: 50px;
			display: none;
		}
			
		#left_skip {
			transform: rotate(90deg) translateX(18px);
		}

		#right_skip {
			transform: rotate(-90deg) translateX(-18px);
		}

			
	</style>
</head>
<body>
	<div class="container" id="container">
		<p id="product_title">PRODUCTS</p>
		<div class="bar_container" id="bar_container">
			<img src="/FoodFrenzy-Canteen-Food-Ordering-System/storage/svg/system/search.svg" alt="glass" id="glass">
			<input type="text" id="searchBar" placeholder="search your favourite food"  maxlength="30">
		</div>

		<div class="filter" id="filter">
			<img src="/FoodFrenzy-Canteen-Food-Ordering-System/storage/svg/system/filter.svg" id="filter_jpg">
			<select id="dropdown">
				<option value="all">All</option>
				<option value="main_dish">Main dishes</option>
				<option value="short_eat">Short eats</option>
				<option value="dessert">Desserts</option>
				<option value="drink">Drinks</option>
			</select>
		</div>
			
		<p id="content"></p>
		<div id="foodList" class="food-list">
			<!-- Food details will be dynamically added here -->
		</div>
		
		<div class="segment_skip_cont" id="segment_skip_cont">
			<div class="next_seg_buttons" id="next_seg_buttons"></div>
		</div>
	</div>

	<script>
		$(document).ready(function() {
			adjustDropdownWidth();

			$('#dropdown').on('change', function() {
				adjustDropdownWidth();
			});
			
			function adjustDropdownWidth() {
				const dropdown = $('#dropdown');
				const selectedOptionText = dropdown.find(':selected').text();

				const textWidth = selectedOptionText.length * 9; 
				
				dropdown.css('width', textWidth + 'px');
			}
		});
	</script>	
	
</body>
</html>