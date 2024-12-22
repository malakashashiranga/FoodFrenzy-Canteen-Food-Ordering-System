$(document).ready(function() {
    const finishedOrdersContainer = $("#finishedOrdersContainer");
    let isLoading = false;
	const searchBar = $("#searchBar");

    // Function to load food items
    function loadPendingOrders(searchTerm = "") {
        if (isLoading) {
            return;
        }
        isLoading = true;

        $.ajax({
            url: '/FoodFrenzy/application/backend/php/admin/orders/finished orders/food list/load_finished_orders_to_page.php',
            type: "POST",
            dataType: "json",
            data: {searchTerm: searchTerm},
            success: function(data) {
				if (data.success) {
					isLoading = false;
					const tableBody = $("#finishedOrdersTableBody");
					tableBody.empty();

					if (data.noFoods) {
						$("#nothing_alert").show();
						$("#nothing_alert").html(data.content);
						$("#finishedOrdersContainer").hide();
					} else {
						data.ordersDetails.forEach(function (order) {
							$("#finishedOrdersContainer").show();
							$("#nothing_alert").hide();
							const orderRow = $("<tr>");
							orderRow.html(`
								<td>${order.orderID}</td>
								<td>${order.userID}</td>
								<td>${order.email}</td>
								<td>${order.finishedDate}</td>
								<td>${order.finishedTime}</td>
							`);
							
							orderRow.on("click", function() {
								const orderID = order.orderID;
								const encodedOrderID = encodeURIComponent(orderID);
								window.location.href = `/FoodFrenzy/application/backend/php/admin/orders/finished orders/redirect to specific finished order/redirect_to_specific_finished_orders.php?orderID=${encodedOrderID}`;
							});

							tableBody.append(orderRow);
						});
					}
				} else {
					if (data.error_page) {
						const encodedAlert = encodeURIComponent(data.alert);
						const goBackURL = "/FoodFrenzy/application/frontend/php/pages/admin/orders/finished orders/order list/finished_orders.php"; 
						const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
						window.location.href = errorPageURL;
					}
				}
            },
            error: function(xhr, status, error) {
				const encodedAlert = encodeURIComponent(data.alert);
				const goBackURL = "/FoodFrenzy/application/frontend/php/pages/admin/orders/finished orders/order list/finished_orders.php"; 
				const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
				window.location.href = errorPageURL;
					
            }
        });
    }

    // Initially load some food details
    loadPendingOrders();

    // Implement infinite scrolling by loading more items when the user reaches the bottom
    $(window).on("scroll", function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            loadPendingOrders();
        }
    });

    // Attach an event listener to the category dropdown
    $("#dropdown").on("change", function() {
        const selectedCategory = $(this).val();
		const searchTerm = searchBar.val().trim();
        loadPendingOrders(searchTerm);
    });
	
	searchBar.on("input", function() {
        const searchTerm = searchBar.val().trim();
        loadPendingOrders(searchTerm);
	});
	
});
