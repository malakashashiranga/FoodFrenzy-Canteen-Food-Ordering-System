$(document).ready(function () {
    $('#emailForm').submit(function (event) {
        event.preventDefault(); 
        var formData = $(this).serialize();
		showLoadingSpinner();

        $.ajax({
            type: 'POST', 
            url: '/FoodFrenzy/application/backend/php/sign part/check email/check_email.php', 
            data: formData,
            dataType: 'json', 
            success: function (data) {
                if (data.success) {
                    $('.alert').text('');
					const newPageURL = '/FoodFrenzy/application/backend/php/sign part/get user id/get_id.php';
                    window.location.href = newPageURL;
                } else {
					hideLoadingSpinner();
                    $('#emailAlert').text(data.eMailAlert);
                    if (data.error_page) {
						const encodedAlert = encodeURIComponent(data.alert);
						goBackURL = "/FoodFrenzy/application/frontend/php/pages/sign part/sign in/sign_in.php";  
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
				const goBackURL = "/FoodFrenzy/application/frontend/php/pages/sign part/ask email part/insert_email.php"; 
				const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedError}&goBackURL=${encodeURIComponent(goBackURL)}`;
				window.location.href = errorPageURL;
            }
        });
    });
});
