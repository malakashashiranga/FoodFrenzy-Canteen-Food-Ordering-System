$(document).ready(function() {
    $(document).on('click', '.add-to-cart', function() {
        const foodNumber = $(this).closest('.food-item').find('.food_number').text();

        $.ajax({
            type: 'POST',
            url: '/FoodFrenzy/application/backend/php/register user/foods add to cart/foods_add_to_cart.php',
            data: { foodNumber: foodNumber},
            success: function(data) {
                if (data.success) {
					if (data.addCartAlert) {
						showCartAddAlertBox(data.addCartAlert);
					}
                } else {
                    if (data.error_page) {
                        const encodedAlert = encodeURIComponent(data.alert);
                        const goBackURL = "/FoodFrenzy/application/frontend/php/pages/register user/home_page.php";
                        const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                        window.location.href = errorPageURL;
                    }
                }
            },
            error: function(xhr, status, error) {
                const encodedAlert = encodeURIComponent('AJAX Error');
                const goBackURL = "/FoodFrenzy/application/frontend/php/pages/register user/home_page.php";
                const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                window.location.href = errorPageURL;
            }
        });
    });
});
