$(document).ready(function() {
    $("#alert_second_btn").click(function(event) { 
        var buttonId = $(this).attr('id');
        var buttonClass = $(this).attr('class');
		showLoadingSpinner();

        event.preventDefault(); 
        $.ajax({
            url: '/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin/menu/manage food details/manage specific food details/delete food/delete_food.php',
            method: 'POST',
            data: { buttonId: buttonId, buttonClass: buttonClass}, 
            dataType: 'json',
            success: function(data) {
				hideLoadingSpinner();
                if (data.success) {
					$('.alert').text('');
					if (data.alertType === 'delete_food'){
						hideAlertBox();
						window.location.href = '/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/menu/manage food details/food list/manage_food_details.php';
					} 
                } else {
					if (data.error_page) {
						const encodedAlert = encodeURIComponent(data.alert);
                        goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/menu/manage food details/manage specific food details/manage_specific_food_details.php";
                        const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                        window.location.href = errorPageURL;
                    }
                }
            },
            error: function(xhr, status, error) {
				hideLoadingSpinner();
				const encodedAlert = encodeURIComponent(data.alert);
				const goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/menu/manage food details/manage specific food details/manage_specific_food_details.php"; 
				const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
				window.location.href = errorPageURL;
            }
        });
    });
});
