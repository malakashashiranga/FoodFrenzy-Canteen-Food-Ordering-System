$(document).ready(function () {
    $('.ajax-button').click(function (event) {
        event.preventDefault();
		showLoadingSpinner();

        if ($(this).attr('id') === 'cancel_button') {
            location.reload();
        } else if ($(this).attr('id') === 'submit_button') {
            const formData = new FormData();
            formData.append('reply', $('#reply').val());

            $.ajax({
                url: '/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin/contact forms/pending contact forms/reply to specific contact form/reply_to_contact_forms.php',
                method: 'POST',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (data) {
					hideLoadingSpinner();
                    if (data.success) {
						$('#replyAlert').text(data.replyMessageAlert);
                        if (data.alertContent) {
							showCustomAlertBox(data.alert);	
							setTimeout(function() {
								location.reload();
							}, 5000); 
						}
					} else {
                        $('#replyAlert').text(data.replyMessageAlert);

                        if (data.error_page) {
                            const encodedAlert = encodeURIComponent(data.alert);
                            goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/contact forms/pending contact forms/manage pending contact form/specific_pending_contact_form_page.php";
                            const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                            window.location.href = errorPageURL;
                        }   
                    }
                },
                error: function (xhr, status, error) {
					hideLoadingSpinner();
                    const encodedAlert = encodeURIComponent(data.alert);
                    const goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/contact forms/pending contact forms/manage pending contact form/specific_pending_contact_form_page.php";
                    const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                    window.location.href = errorPageURL;
                }
            });
        }
    });
});
