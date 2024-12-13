$(document).ready(function() {
    function loadFoods(process, segmentIndex, className) {
        var selectedValue = $('#dropdown').val();
        var searchValue = $('#searchBar').val(); 
        var requestData = {
            process: process,
            selectedOption: selectedValue,
            searchValue: searchValue,
            className: className // Include className in requestData
        };
        if (segmentIndex !== undefined) {
            requestData.segmentIndex = segmentIndex;
        }
        $.ajax({
            url: '/FoodFrenzy/application/backend/php/register and unregister user/load foods with segments/load_foods.php',
            type: "POST",
            dataType: "json",
            data: requestData,
            success: function (data) {
                if (data.success) {
                    const foodList = $("#foodList");
                    foodList.empty(); // Clear existing content
                            
                    if (data.noFoods) {
                        $("#content").show();
                        $("#content").html(data.content);
                        $("#foodList").hide();
                        $("#show_more").hide();
                    } else {
                        document.getElementById('container').style.height = '550px';
                                
                        if (data.moreHeightSize) {
                            const additionalHeight = data.moreHeightSize;
                            const decreaseHeightSize = data.decreaseHeightSize;
                            const currentHeight = $("#container").height();
                            $("#container").height(currentHeight + additionalHeight);
                        }
                                
                        const foodItems = data.foodDetails.map((food, index) => {
                            return `
                                <div class="food-item">
                                    <img src="${food.photo_path}" alt="${food.foodName}" id="food_image">
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

                        $("#content").hide();
                        $("#foodList").show();
                                
                        if (data.moreButton == "yes") {
                            $("#segment_skip_cont").show();
                        } else if (data.moreButton == "no") {
                            $("#segment_skip_cont").hide();
                        }
                                
                        if (data.segmentNumbers) {
                            $("#segment_skip_cont").show();
                            $("#right_skip").show();
                            const segmentNumbers = data.segmentNumbers.split(',').map(Number); 
                            const nextSegButtonsDiv = $('.next_seg_buttons');
                            nextSegButtonsDiv.empty(); 
                                    
                            segmentNumbers.forEach((segment, index) => {
                                const button = $(`<button class="skip-segment">${segment}</button>`);
                                nextSegButtonsDiv.append(button);
                            });
                        }
                    }
                } else {
                    if (data.error_page) {
                        const encodedAlert = encodeURIComponent(data.alert);
                        const goBackURL = "/FoodFrenzy/application/frontend/php/pages/unregister_user/food_page/food_page.php";
                        const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                        window.location.href = errorPageURL;
                    }
                }
            },
            error: function (xhr, status, error) {
                if (data.error_page) {
                    const encodedAlert = encodeURIComponent(data.alert);
                    const goBackURL = "/FoodFrenzy/application/frontend/php/pages/unregister_user/food_page/food_page.php";
                    const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                    window.location.href = errorPageURL;
                }
            }
        });
    }

    loadFoods('page_load');

    $('#dropdown').change(function() {
        loadFoods('page_load');
    });

    $('#searchBar').keyup(function() {
        loadFoods('page_load');
    });

    $(document).on('click', '.skip-segment', function() {
        var segmentIndex = $(this).index();
        var className = $(this).attr('class'); // Get className of the clicked button
        loadFoods('skip_food_seg', segmentIndex, className); // Pass className as parameter
    });
});