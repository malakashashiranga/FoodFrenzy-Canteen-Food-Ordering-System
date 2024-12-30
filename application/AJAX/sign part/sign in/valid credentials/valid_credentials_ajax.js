$(document).ready(function () {
    $('#loginForm').submit(function (event) {
        event.preventDefault(); 
        var formData = $(this).serialize();
		showLoadingSpinner();

        $.ajax({
            type: 'POST', 
            url: '/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/sign part/sign in/valid credentials/check_credential_validation.php', 
            data: formData,
            dataType: 'json', 
            success: function (data) {
                if (data.success) {
                    $('.alert').text('');
					if (data.valid === true) {
						const newPageURL = '/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/sign part/sign in/set cookies/set_cookies.php';
						window.location.href = newPageURL;
					}
                } else {
					hideLoadingSpinner();
					$('#emailAlert').text(data.emailAlert);
                    $('#passwordAlert').text(data.passwordAlert);

                    if (data.error_page) {
						const encodedAlert = encodeURIComponent(data.alert);
						const goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/sign part/sign in/sign_in.php"; 
						const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedError}&goBackURL=${encodeURIComponent(goBackURL)}`;
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
				const goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/sign part/sign in/sign_in.php"; 
				const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedError}&goBackURL=${encodeURIComponent(goBackURL)}`;
				window.location.href = errorPageURL;
            }
        });
    });
});
