$(document).ready(function () {
    const checkOutFieldForm = $('#checkOutFieldForm');
    checkOutFieldForm.submit(function (event) {
        event.preventDefault();
		showLoadingSpinner();
        $('#payButton').prop('disabled', true);
		
        const formData = checkOutFieldForm.serialize();
        $.ajax({
            url: '/FoodFrenzy/application/backend/php/register user/cart payment/make_payment.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function (data) {
				hideLoadingSpinner();
                if (data.success) {
                    $('#walletPayAlert').text('');
					hideCheckOutFieldAlert();
					if (data.successMessage) {
						showCustomAlertBox(data.successMessage);	
						setTimeout(function() {
							location.reload();
						}, 6000); 
					}
                } else {
                    if (data.walletPayAlert) {
						$('#walletPayAlert').text(data.alert);
					}
					if (data.payError) {
						$('#PayAlert').text(data.alert);
					}
					setTimeout(function() {
						location.reload();
					}, 2000); 
					
					if (data.error_page) {
                        const encodedAlert = encodeURIComponent(data.alert);
                        const goBackURL = "/FoodFrenzy/application/frontend/php/pages/register user/cart/cart.php";
                        const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                        window.location.href = errorPageURL;
                    }
                }
            },
            error: function (xhr, status, error) {
				hideLoadingSpinner();
                const encodedAlert = encodeURIComponent(error);
                const goBackURL = "/FoodFrenzy/application/frontend/php/pages/register user/cart/cart.php"; 
                const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                window.location.href = errorPageURL;
            }
        });
    });
});
