$(document).ready(function() {
    $("#alert_second_btn").click(function(event) { 
		showLoadingSpinner();
        var buttonId = $(this).attr('id');
        var buttonClass = $(this).attr('class');

        event.preventDefault(); 
        $.ajax({
            url: '/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/register user/settings/logout and delete account/logout_&_delete_account.php',
            method: 'POST',
            data: { buttonId: buttonId, buttonClass: buttonClass}, 
            dataType: 'json',
            success: function(data) {
                if (data.success) {
					$('.alert').text('');
					if (data.alertType === 'delete_acc'){
						const newPageURL = '/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/register user/settings/send account delete message/send_email_message.php';
						window.location.href = newPageURL;
					} else if (data.alertType === 'logout_acc'){
						const newPageURL = '/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/sign part/sign in/sign_in.php';
						window.location.href = newPageURL;
					}
                } else {
					hideLoadingSpinner();
					if (data.error_page) {
						const encodedAlert = encodeURIComponent(data.alert);
                        goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/register user/settings/settings.php";  
                        const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                        window.location.href = errorPageURL;
                    }
                }
            },
            error: function(xhr, status, error) {
				hideLoadingSpinner();
				const encodedError = encodeURIComponent(error);
                const goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/register user/settings/settings.php"; 
                const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedError}&goBackURL=${encodeURIComponent(goBackURL)}`;
                window.location.href = errorPageURL;
            }
        });
    });
});
