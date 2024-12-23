$(document).ready(function() {
    $("#alert_second_btn").click(function(event) { 
        var buttonId = $(this).attr('id');
        var buttonClass = $(this).attr('class');
		showLoadingSpinner();

        event.preventDefault(); 
        $.ajax({
            url: '/FoodFrenzy/application/backend/php/admin/settings/log out/log_out.php',
            method: 'POST',
            data: { buttonId: buttonId, buttonClass: buttonClass}, 
            dataType: 'json',
            success: function(data) {
				hideLoadingSpinner();
                if (data.success) {
					$('.alert').text('');
					if (data.alertType === 'logout_acc'){
						const newPageURL = '/FoodFrenzy/application/frontend/php/pages/sign part/sign in/sign_in.php';
						window.location.href = newPageURL;
					}
                } else {
					if (data.error_page) {
						const encodedAlert = encodeURIComponent(data.alert);
                        goBackURL = "/FoodFrenzy/application/frontend/php/pages/admin/settings/settings.php";
                        const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                        window.location.href = errorPageURL;
                    }
                }
            },
            error: function(xhr, status, error) {
				hideLoadingSpinner();
				const encodedError = encodeURIComponent(error);
                const goBackURL = "/FoodFrenzy/application/frontend/php/pages/admin/settings/settings.php"; 
				const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedError}&goBackURL=${encodeURIComponent(goBackURL)}`;
                window.location.href = errorPageURL;
            }
        });
    });
});
