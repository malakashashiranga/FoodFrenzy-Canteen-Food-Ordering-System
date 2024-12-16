$(document).ready(function () {
    $('#password_form').submit(function (event) {
        event.preventDefault(); 
        var formData = $(this).serialize();
		showLoadingSpinner();

        $.ajax({
            type: 'POST', 
            url: '/FoodFrenzy/application/backend/php/sign part/change or set password/password validation/password_validation.php', 
            data: formData,
            dataType: 'json', 
            success: function (data) {
				hideLoadingSpinner();
                if (data.success) {
					$('.alert').text('');
					window.close();
					const newPageURL = '/FoodFrenzy/application/backend/php/sign part/change or set password/store user details/store_details_in_db.php';
					window.open(newPageURL, '_blank');	
				} else {
                    $('#newPasswordAlert').text(data.newPasswordAlert);
                    $('#retypePasswordAlert').text(data.retypePasswordAlert);
                    
                    if (data.error_page) {
						goBackURL = "/FoodFrenzy/application/frontend/php/pages/sign part/change or set password/change_or_set_password.php";  
						const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
						window.location.href = errorPageURL;
					}
					
					if (data.showAlertContent === true) {
						showPreventCustomAlertBox(data.alert);
					}
                }
            },
            error: function (xhr, status, error) {
				hideLoadingSpinner();
				const encodedError = encodeURIComponent(error);
				const goBackURL = "/FoodFrenzy/application/frontend/php/pages/sign part/change or set password/change_or_set_password.php"; 
				const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedError}&goBackURL=${encodeURIComponent(goBackURL)}`;
				window.location.href = errorPageURL;
			}
        });
    });
});
