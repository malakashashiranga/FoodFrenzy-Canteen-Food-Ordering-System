$(document).ready(function() {
    const foodContainer = $("#foodContainer");
    let isLoading = false;
    const searchBar = $("#searchBar");
    let offset = 0; // Initialize offset with 0
	let newOffset;

    function loadFoodItems(category, searchTerm, offset) {
        if (isLoading) {
            return;
        }
        isLoading = true;

        $.ajax({
            url: '/FoodFrenzy/application/backend/php/admin/menu/manage food details/load foods/load_food_to_page.php',
            type: "POST",
            dataType: "json",
            data: { category: category, searchTerm: searchTerm, offset: offset },
            success: function(data) {
                if (data.success) {
                    isLoading = false;
                    const tableBody = $("#foodTableBody");
                    if (offset === 0) {
                        tableBody.empty();
                    }

                    if (data.noFoods) {
                        $("#nothing_alert").show();
                        $("#nothing_alert").html(data.content);
                        $("#foodContainer").hide();
                    } else {
						if (data.finishTable != true) {
							data.foodDetails.forEach(function(food) {
								$("#foodContainer").show();
								$("#nothing_alert").hide();
								const foodRow = $("<tr>");
								foodRow.html(`
									<td style="display: none;">${food.foodNumber}</td>
									<td>${food.foodName}</td>
									<td>${food.discountPrice}</td>
									<td>${food.nonDiscountPrice}</td>
									<td>${food.category}</td>
									<td>${food.availability}</td>
								`);

								foodRow.on("click", function() {
									const dropdown = document.getElementById("dropdown");
									dropdown.value = "all";
									$('#searchBar').val('');
									const foodId = food.foodNumber;
									const encodedFoodId = encodeURIComponent(foodId); // Encode the foodId
									const redirectURL = `/FoodFrenzy/application/backend/php/admin/menu/manage food details/redirect to specific food details page/redirect_to_specific_food_page.php?food_id=${encodedFoodId}`;
									window.location.href = redirectURL;
								});


								tableBody.append(foodRow);
							});
							offset += data.foodDetails.length; // Update offset
							newOffset = offset;
						}
					}
                } else {
                    if (data.error_page) {
                        const encodedAlert = encodeURIComponent(data.alert);
                        const goBackURL = "/FoodFrenzy/application/frontend/php/pages/admin/menu/manage food details/food list/manage_food_details.php";
                        const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                        window.location.href = errorPageURL;
                    }
                }
            },
            error: function(xhr, status, error) {
                const encodedAlert = encodeURIComponent(data.alert);
                const goBackURL = "/FoodFrenzy/application/frontend/php/pages/admin/menu/manage food details/food list/manage_food_details.php";
                const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                window.location.href = errorPageURL;
            }
        });
    }

    loadFoodItems("all", "", offset);

    $(".mid_container").on("scroll", function() {
		const element = $(this)[0];
		const scrollTop = element.scrollTop;
		const windowHeight = element.clientHeight;
		const documentHeight = element.scrollHeight;

		if (scrollTop + windowHeight >= documentHeight - 100) {
			const selectedCategory = $("#dropdown").val();
			const searchTerm = searchBar.val().trim();
			loadFoodItems(selectedCategory, searchTerm, newOffset);
		}
	});


    $("#dropdown").on("change", function() {
        const selectedCategory = $(this).val();
        const searchTerm = searchBar.val().trim();
        offset = 0;
        loadFoodItems(selectedCategory, searchTerm, offset);
    });


    $('#searchBar').keyup(function() {
        const selectedCategory = $("#dropdown").val();
        const searchTerm = $(this).val().trim();
        offset = 0;
        loadFoodItems(selectedCategory, searchTerm, offset);
    });
});
