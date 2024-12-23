$(document).ready(function() {
    function getOrderHistoryDetails() {
        $.ajax({
            url: '/FoodFrenzy/application/backend/php/register user/order history/load_order_history.php',
            type: "POST",
            dataType: "json",
            data: {},
            success: function (data) {
                if (data.success) {
                    if (data.noCartHistory) {
                        $('#orderHistoryTableBody').empty();
                        $(".userDetailsTable").hide();
                        $("#noItemAlert").show().text(data.alert);
                    } else {
                        $("#noItemAlert").hide();
                        const cartHistory = data.cartHistory;
                        $('#orderHistoryTableBody').empty();
                        $.each(cartHistory, function(orderID, order) {
                            const row = `<tr>
                                <td>${orderID}</td>
                                <td>${order.orderDate}</td>
                                <td>${order.orderedTime}</td>
								<td>Rs ${order.netPrice}</td>
								<td>${order.state}</td>
                            </tr>`;
                            $('#orderHistoryTableBody').append(row);
                        });
                    }
                } else {
                    if (data.error_page) {
                        const encodedAlert = encodeURIComponent(data.alert);
                        const goBackURL = "/FoodFrenzy/application/frontend/php/pages/register user/order history/order_history.php"; 
						const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedError}&goBackURL=${encodeURIComponent(goBackURL)}`;
						window.location.href = errorPageURL;
                    }
                }
            },
            error: function (xhr, status, error) {
                // Handle AJAX error
                console.error(error);
                const encodedAlert = encodeURIComponent('AJAX Error');
                const goBackURL = "/FoodFrenzy/application/frontend/php/pages/register user/order history/order_history.php"; 
				const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedError}&goBackURL=${encodeURIComponent(goBackURL)}`;
				window.location.href = errorPageURL;
            }
        });
    }
    getOrderHistoryDetails();
});
