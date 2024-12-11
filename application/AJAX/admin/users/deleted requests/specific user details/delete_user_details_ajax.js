$(document).ready(function() {
    $('#delete_button').click(function() {
		event.preventDefault();
		var userID = $('#user_id').val();
		showLoadingSpinner();

        $.ajax({
            url: '/FoodFrenzy/application/backend/php/admin/users/deleted requests/specific user details/delete_user_details.php', // Specify the URL of the PHP script that will handle the delete request
            method: 'POST',
            dataType: 'json',
            data: {
				userID: userID
            },
            success: function(data) {
				hideLoadingSpinner();
                if (data.success) {
                    if (data.alertContent) {
						showCustomAlertBox(data.alert);	
						setTimeout(function() {
							window.open("/FoodFrenzy/application/frontend/php/pages/admin/users/deleted requests/user list/deleted_request_users.php");
						}, 5000); 
					}
                } else {
                    if (data.error_page) {
						const encodedAlert = encodeURIComponent(data.alert);
						const goBackURL = "/FoodFrenzy/application/frontend/php/pages/admin/users/deleted requests/specific user details/specific_deleted_request_users.php"; 
						const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
						window.location.href = errorPageURL;
					}
                }
            },
            error: function(xhr, status, error) {
				hideLoadingSpinner();
                const encodedAlert = encodeURIComponent(error);
				const goBackURL = "/FoodFrenzy/application/frontend/php/pages/admin/users/deleted requests/specific user details/specific_deleted_request_users.php"; 
				const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
				window.location.href = errorPageURL;
            }
        });
    });
});
