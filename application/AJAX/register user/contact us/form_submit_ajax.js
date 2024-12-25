$(document).ready(function () {
    const contactForm = $('#conatact_form');

    contactForm.submit(function (event) {
        event.preventDefault();
        const formData = contactForm.serialize();
		showLoadingSpinner();

        $.ajax({
            url: '/FoodFrenzy/application/backend/php/register and unregister user/submit contact form/submit_contact_form.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function (data) {
				hideLoadingSpinner();
                if (data.success) {
					$('#full_name_alert').text(data.firstNameAlert);
                    $('#email_alert').text(data.eMailAlert);
                    $('#phone_number_alert').text(data.mobileNumberAlert);
                    $('#message_alert').text(data.messageAlert);
					
					if (data.alertContent) {
						showCustomAlertBox(data.alert);	
						setTimeout(function() {
							location.reload();
						}, 5000); 
					}
                } else {
					$('#full_name_alert').text(data.firstNameAlert);
                    $('#email_alert').text(data.eMailAlert);
                    $('#phone_number_alert').text(data.mobileNumberAlert);
                    $('#message_alert').text(data.messageAlert);
                    
					if (data.error_page) {
						const encodedAlert = encodeURIComponent(data.alert);
						const goBackURL = "/FoodFrenzy/application/frontend/php/pages/register user/contact us/contacts.php"; 
						const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
						window.location.href = errorPageURL;
					}
                }
            },
            error: function (xhr, status, error) {
				hideLoadingSpinner();
                const encodedAlert = encodeURIComponent(xhr.responseText);
                const goBackURL = "/FoodFrenzy/application/frontend/php/pages/register user/contact us/contacts.php";
                const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                window.location.href = errorPageURL;
            }
        });
    });
});
