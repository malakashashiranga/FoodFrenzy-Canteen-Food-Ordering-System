$(document).ready(function() {
    const form = $("#food_form");
    const submitButton = $("#submit_button");
    const cancelButton = $("#cancel_button");

    submitButton.on("click", function() {
        const formData = new FormData(form[0]);
		const category = $("input[name='food_category']:checked").val();
		const availability = $("input[name='availability']:checked").val();

		formData.append("food_category", category);
		formData.append("availability", availability);
		showLoadingSpinner();
	
		$.ajax({
			type: "POST",
			url: "/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin/menu/add new food/add_new_food.php",
			data: formData,
			dataType: 'json',
			contentType: false, 
			processData: false, 
            success: function (data) {
				hideLoadingSpinner();
                if (data.success) {
					$('#foodPictureAlert').text(data.foodPictureAlert);
                    $('#foodNameAlert').text(data.foodNameAlert);
                    $('#discountPriceAlert').text(data.discountPriceAlert);
                    $('#nonDiscountPriceAlert').text(data.nonDiscountPriceAlert);
					$('#foodDetailsAlert').text(data.foodDetailsAlert);
                    $('#categoryAlert').text(data.categoryAlert);
                    $('#availabilityAlert').text(data.availabilityAlert);
					
					if (data.alertContent) {
						formclear();
						showCustomAlertBox(data.alert);	
                    }
                } else {
					if (data.alertContent) {
						formclear();
						showCustomAlertBox(data.alert);
                    }
					
                    $('#foodPictureAlert').text(data.foodPictureAlert);
                    $('#foodNameAlert').text(data.foodNameAlert);
                    $('#discountPriceAlert').text(data.discountPriceAlert);
                    $('#nonDiscountPriceAlert').text(data.nonDiscountPriceAlert);
					$('#foodDetailsAlert').text(data.foodDetailsAlert);
                    $('#categoryAlert').text(data.categoryAlert);
                    $('#availabilityAlert').text(data.availabilityAlert);

                    if (data.error_page) {
						const encodedAlert = encodeURIComponent(data.alert);
						const goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/menu/add new food/add_new_food_page.php"; 
						const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
						window.location.href = errorPageURL;
					}
                }
            },
            error: function (xhr, status, error) {
				hideLoadingSpinner();
				const encodedAlert = encodeURIComponent(data.alert);
				const goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/menu/add new food/add_new_food_page.php"; 
				const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
				window.location.href = errorPageURL;
				
			}
        });
    });

    
    cancelButton.on("click", function() {
        formclear();
    });


	function formclear(){
		form[0].reset();
		foodImage.src = "/FoodFrenzy-Canteen-Food-Ordering-System/storage/svg/system/question_mark.svg";
			
		$('#foodPictureAlert').text("");
		$('#foodNameAlert').text("");
		$('#discountPriceAlert').text("");
		$('#nonDiscountPriceAlert').text("");
		$('#foodDetailsAlert').text("");
		$('#categoryAlert').text("");
		$('#availabilityAlert').text("");	
	}
	
});

