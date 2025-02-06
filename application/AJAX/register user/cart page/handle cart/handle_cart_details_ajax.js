$(document).ready(function() {
    function getCartDetails() {
        $.ajax({
            url: '/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/register user/cart handling/load cart details/get_cart_details.php',
            type: "POST",
            dataType: "json",
            data: {},
            success: function (data) {
                if (data.success) {
                    if (data.noFoods) {
						$("#noItemAlert").show();
						$("#noItemAlert").text(data.alert);
						$('#cartTableBody').empty();
						$(".userDetailsTable").hide();
						$(".checkOutField").hide();
                    } else if (Array.isArray(data.foodDetails)) {
						$("#noItemAlert").hide();
                        const foodDetails = data.foodDetails;
						$('#cartTableBody').empty();
                        foodDetails.forEach(function(cartItem) {
                            const row = `<tr>
											<td id="${cartItem.food_number}" style="display: none;">${cartItem.food_number}</td>
                                            <td><img src="${cartItem.photo_path}" alt="Food Image" class="cart-food-image"></td>
                                            <td>${cartItem.food_name}</td>
                                            <td>${cartItem.price}</td>
                                            <td><span class="control-count-item-bg"><img src = "/FoodFrenzy-Canteen-Food-Ordering-System/storage/svg/system/minus.svg" class="decrease-items"></span><span class = "item-cart-quantity">${cartItem.quantity}</span><span class="control-count-item-bg"><img src= "/FoodFrenzy-Canteen-Food-Ordering-System/storage/svg/system/plus.svg" class="increase-items"></span></td>
                                            <td><button class="remove-item">Remove</button></td>
                                        </tr>`;
                            $('#cartTableBody').append(row);
                        });
						if (data.checkOutButton) {
							$("#totalPrice").text(data.totalPrice);
							$(".checkOutField").show();
						}
                    } else {
                        console.log('Invalid cart data format.');
                    }
                } else {
                    if (data.error_page) {
                        const encodedAlert = encodeURIComponent(data.alert);
                        const goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/register user/cart/cart.php";
                        const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                        window.location.href = errorPageURL;
                    }
                }
            },
            error: function (xhr, status, error) {
                // Handle AJAX error
                if (status === 'error') {
                    const encodedAlert = encodeURIComponent('AJAX Error');
                    const goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/register user/cart/cart.php"; 
                    const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                    window.location.href = errorPageURL;
                }
            }
        });
    }
	
	
	
	$('#cartTableBody').on('click', '.decrease-items', function() {
		const foodId = $(this).closest('tr').find('td[id]').attr('id');
		updateQuantity(foodId, -1);
	});

	$('#cartTableBody').on('click', '.increase-items', function() {
		const foodId = $(this).closest('tr').find('td[id]').attr('id');
		updateQuantity(foodId, 1);
	});

    function updateQuantity(foodId, change) {
        $.ajax({
            url: '/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/register user/cart handling/update quantity/update_quantity.php',
            type: 'POST',
            dataType: 'json',
            data: { foodId: foodId, change: change},
            success: function(data) {
                if (data.success) {
					console.log(`${data.foodName} has been ${data.command} to the cart in one quantity`);
					if (data.removeAlert){
						console.log(`${data.foodName} has been deleted from the cart`);	
					}
                    getCartDetails();
                } else {
                    if (data.error_page) {
                        const encodedAlert = encodeURIComponent(data.alert);
                        const goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/register user/cart/cart.php";
                        const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                        window.location.href = errorPageURL;
                    }
                }
            },
            error: function(xhr, status, error) {
                if (data.error_page) {
                    const encodedAlert = encodeURIComponent('AJAX Error');
                    const goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/register user/cart/cart.php"; 
                    const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                    window.location.href = errorPageURL;
                }
            }
        });
    }
	
	
	
	$('#cartTableBody').on('click', '.remove-item', function() {
		const foodId = $(this).closest('tr').find('td[id]').attr('id');
		removeCartFood(foodId, "removeFood");
	});
	
	function removeCartFood(foodId, operationType) {
        $.ajax({
            url: '/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/register user/cart handling/remove food item/remove_food.php',
            type: 'POST',
            dataType: 'json',
            data: { foodId: foodId, operationType: operationType},
            success: function(data) {
                if (data.success) {
					if (data.removeAlert){
						console.log(`Food item has been deleted from the cart`);	
					}
                    getCartDetails();
                } else {
                    if (data.error_page) {
                        const encodedAlert = encodeURIComponent(data.alert);
                        const goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/register user/cart/cart.php";
                        const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                        window.location.href = errorPageURL;
                    }
                }
            },
            error: function(xhr, status, error) {
                if (data.error_page) {
                    const encodedAlert = encodeURIComponent('AJAX Error');
                    const goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/register user/cart/cart.php"; 
                    const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                    window.location.href = errorPageURL;
                }
            }
        });
    }
	
    getCartDetails();
});
