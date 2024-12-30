$(document).ready(function () {
    $('.ajax-button').click(function (event) {
        event.preventDefault();
		showLoadingSpinner();

        if ($(this).attr('id') === 'cancel_button') {
            location.reload();
        } else if ($(this).attr('id') === 'submit_button') {
            const formData = new FormData();
            formData.append('cash_payment', $('#cash_payment').val());
            formData.append('transaction_type', $('input[name="transaction_type"]:checked').val());

            $.ajax({
                url: '/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin/wallets/update wallets/manage specific wallet/update_wallet.php',
                method: 'POST',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (data) {
					hideLoadingSpinner();
                    if (data.success) {
						$('#transactionAlert').text(data.transactionAlert);
						$('#cashPaymentAlert').text(data.cashPaymentAlert);
                        if (data.alertContent) {
							showCustomAlertBox(data.alert);	
							setTimeout(function() {
								location.reload();
							}, 5000); 
						}
					} else {
						if (data.alertContent) {
							showCustomAlertBox(data.alert);
							setTimeout(function() {
								location.reload();
							}, 5000); 
						}
                        $('#transactionAlert').text(data.transactionAlert);
						$('#cashPaymentAlert').text(data.cashPaymentAlert);

                        if (data.error_page) {
                            const encodedAlert = encodeURIComponent(data.alert);
                            goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/wallets/update wallets/manage specific wallet/manage_specific_wallet.php";
                            const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                            window.location.href = errorPageURL;
                        }
                    }
                },
                error: function (xhr, status, error) {
					hideLoadingSpinner();
                    const encodedAlert = encodeURIComponent(data.alert);
                    const goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/wallets/update wallets/manage specific wallet/manage_specific_wallet.php";
                    const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                    window.location.href = errorPageURL;
                }
            });
        }
    });
});
