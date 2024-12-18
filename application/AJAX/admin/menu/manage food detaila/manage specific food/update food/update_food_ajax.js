$(document).ready(function() {
    $('.ajax-button').click(function(event) { 
        var buttonId = $(this).attr('id');
        var buttonClass = $(this).attr('class');
		const form = $("#food_form");
		
		const formData = new FormData(form[0]);

		const category = $("input[name='food_category']:checked").val();
		const availability = $("input[name='availability']:checked").val();

		formData.append("food_category", category);
		formData.append("availability", availability);
		formData.append("buttonId", buttonId);
		formData.append("buttonClass", buttonClass);
		
		showLoadingSpinner();
		
        event.preventDefault(); 
        $.ajax({
            url: '/FoodFrenzy/application/backend/php/admin/menu/manage food details/manage specific food details/button clicks/buttons_clicks.php',
            method: 'POST',
            data: formData,
			dataType: 'json',
			contentType: false,
			processData: false,
            success: function (data) {
				hideLoadingSpinner();
                if (data.success) {
					
                    if (data.buttons === 'edit_food_btn') {
						editFoodButtonFunc();
                    } else if (data.buttons === 'delete_food_btn') {
						deleteFoodButtonFunc();
						showAlertBox(data.alert, 'No', 'Yes', '250px', '15px');
                    } else if (data.buttons === 'food_change_cancel_btn') {
						cancelFoodChangeButtonFunc();
						setTimeout(function () {
							location.reload();
						}, 2000);
                    } else if (data.buttons === 'food_change_save_btn') {
						saveFoodUpdatedFunc();
						showCustomAlertBox(data.alert);	
						setTimeout(function () {
							location.reload();
						}, 2000);
                    } 
			
					$('#foodPictureAlert').text(data.foodPictureAlert);
                    $('#foodNameAlert').text(data.foodNameAlert);
                    $('#discountPriceAlert').text(data.discountPriceAlert);
                    $('#nonDiscountPriceAlert').text(data.nonDiscountPriceAlert);
					$('#foodDetailsAlert').text(data.foodDetailsAlert);
                    $('#categoryAlert').text(data.categoryAlert);
                    $('#availabilityAlert').text(data.availabilityAlert);
					
					if (data.consoleAlert) {
						console.log(data.consoleAlert);
					}
					
                } else {
					
					$('#foodPictureAlert').text(data.foodPictureAlert);
                    $('#foodNameAlert').text(data.foodNameAlert);
                    $('#discountPriceAlert').text(data.discountPriceAlert);
                    $('#nonDiscountPriceAlert').text(data.nonDiscountPriceAlert);
					$('#foodDetailsAlert').text(data.foodDetailsAlert);
                    $('#categoryAlert').text(data.categoryAlert);
                    $('#availabilityAlert').text(data.availabilityAlert);
					
                    if (data.error_page) {
                        const encodedAlert = encodeURIComponent(data.alert);
                        goBackURL = "/FoodFrenzy/application/frontend/php/pages/admin/menu/manage food details/manage specific food details/manage_specific_food_details.php";
                        const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                        window.location.href = errorPageURL;
                    }
					
					if (data.consoleAlert) {
						console.log(data.consoleAlert);
					}
                }
            },
            error: function (xhr, status, error) {
				hideLoadingSpinner();
                const encodedAlert = encodeURIComponent(data.alert);
				const goBackURL = "/FoodFrenzy/application/frontend/php/pages/admin/menu/manage food details/manage specific food details/manage_specific_food_details.php"; 
				const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
				window.location.href = errorPageURL;
            }
        });
    });
});
