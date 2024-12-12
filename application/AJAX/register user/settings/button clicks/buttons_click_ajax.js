$(document).ready(function() {
    $('.ajax-button').click(function(event) { 
		showLoadingSpinner();
        var buttonId = $(this).attr('id');
        var buttonClass = $(this).attr('class');
        var action = $(this).data('action');
		
		var formData1 = {};
        formData1['first_name'] = $('#prof_first_name').val();
        formData1['last_name'] = $('#prof_last_name').val();
        formData1['phone'] = $('#phone').val();
        formData1['address'] = $('#address').val();
		
		var formData2 = {};
		formData2['email'] = $('#email').val();
		
		var formData3 = {};
		formData3['currentPassword'] = $('#currentPassword').val();
        formData3['newPassword'] = $('#newPassword').val();
        formData3['confirmPassword'] = $('#confirmPassword').val();
		
        event.preventDefault(); 
        $.ajax({
            url: '/FoodFrenzy/application/backend/php/register user/settings/button clicks/button_clicks.php',
            method: 'POST',
            data: { buttonId: buttonId, buttonClass: buttonClass, action: action, formData1: formData1, formData2: formData2, formData3: formData3}, 
            dataType: 'json',
            success: function (data) {
				hideLoadingSpinner();
                if (data.success) {
					$('#firstNameAlert').text(data.firstNameAlert);
                    $('#lastNameAlert').text(data.lastNameAlert);
					$('#phoneNoAlert').text(data.mobileNumberAlert);
                    $('#addressAlert').text(data.addressAlert);
					
					$('#currentPasswordAlert').text(data.currentPasswordAlert);
                    $('#newPasswordAlert').text(data.newPasswordAlert);
                    $('#confirmPasswordAlert').text(data.confirmPasswordAlert);
								
					if (data.alertContent) {
						if (data.buttons === 'delete_acc_btn') {
							defPageCreateFunc();
							showAlertBox(data.alert, 'No', 'Yes', '180px', '20px');
						} else if (data.buttons === 'logout_acc_btn') {
							defPageCreateFunc();
							showAlertBox(data.alert, 'No', 'Yes', '260px', '10px');
						} else if (data.alertType === 'prof_info') {
							defPageCreateFunc();
							setTimeout(function () {
								location.reload();
							}, 2000);
							showCustomAlertBox(data.alert);
						} else if (data.alertType === 'password_ch') {
							defPageCreateFunc();
							showCustomAlertBox(data.alert);
						}
                    }
					
                    if (data.buttons === 'prof_info_btn') {
						profInfoButtonFunc();
                    } else if (data.buttons === 'email_ch_btn') {
						defPageCreateFunc();
						const newPageURL = '/FoodFrenzy/application/frontend/php/pages/sign part/ask email part/insert_email.php';
						window.location.href = newPageURL;
                    } else if (data.buttons === 'password_ch_btn') {
						passChangeButtonFunc();
                    } 
                } else {
					$('#firstNameAlert').text(data.firstNameAlert);
                    $('#lastNameAlert').text(data.lastNameAlert);
					$('#phoneNoAlert').text(data.mobileNumberAlert);
                    $('#addressAlert').text(data.addressAlert);
					
					$('#currentPasswordAlert').text(data.currentPasswordAlert);
                    $('#newPasswordAlert').text(data.newPasswordAlert);
                    $('#confirmPasswordAlert').text(data.confirmPasswordAlert);
					
                    if (data.error_page) {
						const encodedAlert = encodeURIComponent(data.alert);
                        goBackURL = "/FoodFrenzy/application/frontend/php/pages/register user/settings/settings.php";  
                        const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                        window.location.href = errorPageURL;
                    }
                }
            },
            error: function (xhr, status, error) {
				hideLoadingSpinner();
                const encodedError = encodeURIComponent(error);
                const goBackURL = "/FoodFrenzy/application/frontend/php/pages/register user/settings/settings.php"; 
                const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedError}&goBackURL=${encodeURIComponent(goBackURL)}`;
                window.location.href = errorPageURL;
            }
        });
    });
});
