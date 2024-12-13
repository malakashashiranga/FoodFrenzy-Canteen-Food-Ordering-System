$(document).ready(function() {
    $('.pic-ajax-button').click(function(event) {
		showLoadingSpinner();
        event.preventDefault();

        var imageFile = $('#imageUpload')[0].files[0]; // Get the selected image file
        var buttonId = $(this).attr('id');
        var buttonClass = $(this).attr('class');
        var action = $(this).data('action');

        var formData = new FormData();
        formData.append('imageFile', imageFile);
        formData.append('buttonId', buttonId);
        formData.append('buttonClass', buttonClass);
        formData.append('action', action);

        $.ajax({
            url: '/FoodFrenzy/application/backend/php/register user/settings/handle profile image/handle_image.php', 
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(data) {
				hideLoadingSpinner();
                if (data.success) {
					if (data.alertContent) {
						if (data.alertType === 'prof_pic_changed') {
							document.getElementById('img_remv').style.display = 'block';
							document.getElementById('pic_confirm_button').style.display = 'none';
							showCustomAlertBox(data.alert);	
							setTimeout(function () {
								window.location.href = window.location.href + '?timestamp=' + new Date().getTime();
							}, 2000);
						} else if (data.alertType === 'prof_pic_removed') {
							document.getElementById('img_remv').style.display = 'none';
							showCustomAlertBox(data.alert);
							setTimeout(function () {
								window.location.href = window.location.href + '?timestamp=' + new Date().getTime();
								location.reload();
							}, 2000);
						}
                    }
                } else {
					if (data.alertContent) {
						if (data.alertType === 'prof_pic_changed') {
							showCustomAlertBox(data.alert);		
						}
                    }
                    
					if (data.error_page) {
						const encodedAlert = encodeURIComponent(data.alert);
                        goBackURL = "/FoodFrenzy/application/frontend/php/pages/register user/settings/settings.php";  
                        const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                        window.location.href = errorPageURL;
                    }
                }
            },
            error: function(xhr, status, error) {
				hideLoadingSpinner();
                const encodedError = encodeURIComponent(error);
                const goBackURL = "/FoodFrenzy/application/frontend/php/pages/register user/settings/settings.php";
                const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedError}&goBackURL=${encodeURIComponent(goBackURL)}`;
                window.location.href = errorPageURL;
            }
        });
    });
});
