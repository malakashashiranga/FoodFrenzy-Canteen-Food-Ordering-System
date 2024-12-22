$(document).ready(function() {
    
    function userDetailsShow() {
        $.ajax({
            url: '/FoodFrenzy/application/backend/php/admin and register user/settings/show profile details/profile_details.php', 
            method: 'POST', 
            dataType: 'json', 
            success: function (data) {
                if (data.success) {
					document.getElementById('first_name').value = data.first_name;
					document.getElementById('last_name').value = data.last_name;
					document.getElementById('email').value = data.email;
					document.getElementById('phone_number').value = data.mobile_number;
					document.getElementById('address').value = data.address;
                } else {
                    if (data.error_page) {
                        const encodedAlert = encodeURIComponent(data.alert);
                        goBackURL = "/FoodFrenzy/application/frontend/php/pages/admin/settings/settings.php";
                        const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                        window.location.href = errorPageURL;
                    }
                }
            },
            error: function (xhr, status, error) {
                const encodedError = encodeURIComponent(error);
				const goBackURL = "/FoodFrenzy/application/frontend/php/pages/admin/settings/settings.php"; 
				const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedError}&goBackURL=${encodeURIComponent(goBackURL)}`;
				window.location.href = errorPageURL;
            }
        });
    }
    userDetailsShow();
});
