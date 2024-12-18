$(document).ready(function () {
    $('#confirm_button').click(function (event) {
        event.preventDefault();
		showLoadingSpinner();

        // Define formData and populate it with the data you want to send
        var formData = new FormData();
        formData.append('order_id', $('#order_id').val());

        $.ajax({
            url: '/FoodFrenzy/application/backend/php/admin/orders/pending orders/specific pending order/confirm_specific_pending_order.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (data) {
				hideLoadingSpinner();
                if (data.success) {
                    if (data.orderExpired) {
						showCustomAlertBox(data.alert);
						setTimeout(function() {
							window.location.href = '/FoodFrenzy/application/frontend/php/pages/admin/orders/pending orders/order list/pending_orders.php';
						}, 5000);
					} else {
						showCustomAlertBox(data.alert);
						setTimeout(function() {
							window.location.href = '/FoodFrenzy/application/frontend/php/pages/admin/orders/pending orders/order list/pending_orders.php';
						}, 5000);
					}
                } else {
                    if (data.error_page) {
						const encodedAlert = encodeURIComponent(data.alert);
						const goBackURL = "/FoodFrenzy/application/frontend/php/pages/admin/orders/pending orders/specific pending order/specific_pending_order.php"; 
						const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
						window.location.href = errorPageURL;
					}
                }
            },
            error: function (xhr, status, error) {
				hideLoadingSpinner();
                const encodedAlert = encodeURIComponent(error);
                const goBackURL = "/FoodFrenzy/application/frontend/php/pages/admin/orders/pending orders/specific pending order/specific_pending_order.php";
                const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                window.location.href = errorPageURL;
            }
        });
    });
});
