$(document).ready(function() {
    const pendingOrdersContainer = $("#pendingOrdersContainer");
    let isLoading = false;
	const searchBar = $("#searchBar");

    // Function to load food items
    function loadPendingOrders(searchTerm = "") {
        if (isLoading) {
            return;
        }
        isLoading = true;

        $.ajax({
            url: '/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin/orders/pending orders/order list/load_pending_orders_to_page.php',
            type: "POST",
            dataType: "json",
            data: {searchTerm: searchTerm},
            success: function(data) {
				if (data.success) {
					isLoading = false;
					const tableBody = $("#pendingOrdersTableBody");
					tableBody.empty();

					if (data.noFoods) {
						$("#nothing_alert").show();
						$("#nothing_alert").html(data.content);
						$("#pendingOrdersContainer").hide();
					} else {
						data.ordersDetails.forEach(function (order) {
							$("#pendingOrdersContainer").show();
							$("#nothing_alert").hide();
							const orderRow = $("<tr>");
							orderRow.html(`
								<td>${order.orderID}</td>
								<td>${order.userID}</td>
								<td>${order.email}</td>
								<td>${order.placedDate}</td>
								<td>${order.placedTime}</td>
							`);
							
							orderRow.on("click", function() {
								const orderID = order.orderID;
								const encodedOrderID = encodeURIComponent(orderID);
								window.location.href = `/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin/orders/pending orders/redirect to specific order/redirect_to_specific_pending_orders.php?orderID=${encodedOrderID}`;
							});

							tableBody.append(orderRow);
						});
					}
				} else {
					if (data.error_page) {
						const encodedAlert = encodeURIComponent(data.alert);
						const goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/orders/pending orders/order list/pending_orders.php"; 
						const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
						window.location.href = errorPageURL;
					}
				}
            },
            error: function(xhr, status, error) {
				const encodedAlert = encodeURIComponent(data.alert);
				const goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/orders/pending orders/order list/pending_orders.php"; 
				const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
				window.location.href = errorPageURL;
					
            }
        });
    }

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
