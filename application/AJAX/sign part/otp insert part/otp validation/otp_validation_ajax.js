$(document).ready(function () {
    var $otpForm = $('#otp_form');
    var $verifyCodeInput = $('#verifyCode');

    function verifyCodeValidation() {
		showLoadingSpinner();
        var formData = $otpForm.serialize();
        $.ajax({
            type: 'POST', 
            url: '/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/sign part/check verification code/check_verification_code.php', 
            data: formData,
            dataType: 'json', 
            success: function (data) {
				hideLoadingSpinner();
                if (data.success) {
					if (data.correctOTP === true) {
						const newPageURL = '/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/sign part/select otp/select_otp.php';
						window.location.href = newPageURL;
					}
                } else {
                    if (data.error_page) {
						const encodedAlert = encodeURIComponent(data.alert);
						goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/sign part/otp insert part/get_verify_code.php";
						const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
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
				const goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/sign part/otp insert part/get_verify_code.php"; 
				const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedError}&goBackURL=${encodeURIComponent(goBackURL)}`;
				window.location.href = errorPageURL;
            }
        });
    }

    $verifyCodeInput.on('input', function () {
        if ($(this).val().length === 8) {
            verifyCodeValidation();
        }
    });

    $otpForm.submit(function (event) {
        event.preventDefault();
    });
});
