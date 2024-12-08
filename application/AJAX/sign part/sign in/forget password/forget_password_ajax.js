function forgetPassword() {
	showLoadingSpinner();
    $.ajax({
        type: 'POST',
        url: '/FoodFrenzy/application/backend/php/sign part/sign in/forget password/forget_password.php',
        dataType: 'json',
        success: function(data) {
			hideLoadingSpinner();
            if (data.success) {
				const newPageURL = '/FoodFrenzy/application/frontend/php/pages/sign part/ask email part/insert_email.php';
                window.location.href = newPageURL;
            } else {
				if (data.error_page) {
					const encodedAlert = encodeURIComponent(data.alert);
					const goBackURL = "/FoodFrenzy/application/frontend/php/pages/sign part/sign in/sign_in.php"; 
					const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
					window.location.href = errorPageURL;
				}
            }
        },
        error: function(xhr, status, error) {
			hideLoadingSpinner();
            const encodedError = encodeURIComponent(error);
			const goBackURL = "/FoodFrenzy/application/frontend/php/pages/sign part/sign in/sign_in.php"; 
			const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedError}&goBackURL=${encodeURIComponent(goBackURL)}`;
			window.location.href = errorPageURL;
        }
    });
}

