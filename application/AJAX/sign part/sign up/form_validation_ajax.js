$(document).ready(function () {
    $('#signupForm').submit(function (event) {
        event.preventDefault(); 
        var formData = $(this).serialize();
		showLoadingSpinner();

        $.ajax({
            type: 'POST', 
            url: '/FoodFrenzy/application/backend/php/sign part/sign up first step/check_details.php', 
            data: formData,
            dataType: 'json', 
            success: function (data) {
				hideLoadingSpinner();
                if (data.success) {
                    $('.alert').text('');
                    const newPageURL = '/FoodFrenzy/application/frontend/php/pages/sign part/ask email part/insert_email.php';
                    window.location.href = newPageURL;
                } else {
                    $('#firstNameAlert').text(data.firstNameAlert);
                    $('#lastNameAlert').text(data.lastNameAlert);
                    $('#mobileNumberAlert').text(data.mobileNumberAlert);
                    $('#addressAlert').text(data.addressAlert);

                    if (data.error_page) {
						const encodedAlert = encodeURIComponent(data.alert);
						const goBackURL = "/FoodFrenzy/application/frontend/php/pages/sign part/signup first part/sign_up.php"; 
						const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
						window.location.href = errorPageURL;
					}
                }
            },
            error: function (xhr, status, error) {
				hideLoadingSpinner();
				const encodedError = encodeURIComponent(error);
				const goBackURL = "/FoodFrenzy/application/frontend/php/pages/sign part/signup first part/sign_up.php"; 
				const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedError}&goBackURL=${encodeURIComponent(goBackURL)}`;
				window.location.href = errorPageURL;
			}
        });
    });
});
