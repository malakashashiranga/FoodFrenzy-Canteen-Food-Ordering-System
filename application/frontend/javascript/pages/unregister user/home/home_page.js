var dropDown = document.getElementById('drop_part');
var closeUp = document.getElementById('close');
var secPart = document.getElementById('s_nd_part');
var orderButtonMiddle = document.getElementById('order_button_middle');
var readMoreButton = document.getElementById('read_more_button');
var showMoreFoods = document.getElementById('show_more_foods');


dropDown.addEventListener('click', function() {
	secPart.style.display = 'block';
	dropDown.style.display = 'none';
});

closeUp.addEventListener('click', function() {
	secPart.style.display = 'none';
	dropDown.style.display = 'block';
});
	
orderButtonMiddle.addEventListener("click", function () {
	window.location.href = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/register user/foods/food_page.php";
});

readMoreButton.addEventListener("click", function () {
	window.location.href = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/unregister user/about us/about_us.php";
});

showMoreFoods.addEventListener("click", function () {
	window.location.href = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/unregister user/food page/food_page.php";
});

	
