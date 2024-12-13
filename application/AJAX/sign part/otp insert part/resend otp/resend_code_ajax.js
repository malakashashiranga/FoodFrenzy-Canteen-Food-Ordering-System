function resendCode() {
	showLoadingSpinner();
    $.ajax({
        type: 'POST',
        url: '/FoodFrenzy/application/backend/php/sign part/resend verify code/resend_code.php',
        dataType: 'json', 
        success: function(data) {
			hideLoadingSpinner();
			if (data.success) {
                if (data.showAlertContent === true) {
					showPreventCustomAlertBox(data.alert);
				}
            } else {
                if (data.error_page) {
					const encodedAlert = encodeURIComponent(data.alert);
					goBackURL = "/FoodFrenzy/application/frontend/php/pages/sign part/otp insert part/get_verify_code.php";  
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
			const goBackURL = "/FoodFrenzy/application/frontend/php/pages/sign part/otp insert part/get_verify_code.php"; 
			const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedError}&goBackURL=${encodeURIComponent(goBackURL)}`;
			window.location.href = errorPageURL;
        }
    });
}
