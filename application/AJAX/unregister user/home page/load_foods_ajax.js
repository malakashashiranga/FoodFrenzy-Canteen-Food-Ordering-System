$(document).ready(function() {
    function fetchFoodDetails() {
        $.ajax({
            url: '/FoodFrenzy/application/backend/php/register and unregister user/load foods/load_foods.php',
            type: "POST",
            dataType: "json",
            data: {},
            success: function(data) {
                if (data.success) {
                    const foodList = $("#foodList");
                    foodList.empty(); // Clear existing content

                    if (data.noFoods) {
                        $("#content").show();
                        $("#content").html(data.content);
                        $("#foodList").hide();
						
						if (data.moreButton) {
							$("#show_more_foods").show();
						} else {
							$("#show_more_foods").hide();
						}	
                    } else {
						document.getElementById('s_nd_part').style.height = '450px';
						
						if (data.moreHeightSize) {
							const additionalHeight = data.moreHeightSize;
							const currentHeight = $("#s_nd_part").height();
							$("#s_nd_part").height(currentHeight + additionalHeight);
						}
						
                        const foodItems = data.foodDetails.map((food, index) => {
                            return `
                                <div class="food-item">
                                    <img src="${food.photo_path}" alt="${food.foodName}" id="food_image" class="food_image">
									<div class="price_name_cont">
									<p id="food_number" class="food_number" style="display: none">${food.foodNumber}</p>
                                    <p id="food_name" class="name_price">${food.foodName}</p>
                                    <p id="food_price" class="name_price">${food.nonDiscountPrice}</p>
									</div>
                                    <button class="add-to-cart">Add to Cart +</button>
                                </div>
                            `;
                        });
						
						$('#foodList').on('click', '.add-to-cart', function() {
							window.location.href = "/FoodFrenzy/application/frontend/php/pages/register user/foods/food_page.php";
						});
						
                        const rows = [];
                        for (let i = 0; i < foodItems.length; i += 4) {
                            rows.push(foodItems.slice(i, i + 4));
                        }

                        rows.forEach((row) => {
                            const rowDiv = $("<div class='food-row'></div>");
                            rowDiv.append(row);
                            foodList.append(rowDiv);
                        });
						
                        // Hide content and show the grid layout
                        $("#content").hide();
                        $("#foodList").show();
						if (data.moreButton) {
							$("#show_more_foods").show();
						} else {
							$("#show_more_foods").hide();
						}	
                    }
                } else {
                    if (data.error_page) {
                        const encodedAlert = encodeURIComponent(data.alert);
                        const goBackURL = "/FoodFrenzy/index.php";
                        const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                        window.location.href = errorPageURL;
                    }
                }
            },
            error: function(xhr, status, error) {
                const encodedAlert = encodeURIComponent(error);
                const goBackURL = "/FoodFrenzy/index.php";
                const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                window.location.href = errorPageURL;
            }
        });
    }

    $('#drop_part').on('click', function() {
        fetchFoodDetails(); 
    });
});
