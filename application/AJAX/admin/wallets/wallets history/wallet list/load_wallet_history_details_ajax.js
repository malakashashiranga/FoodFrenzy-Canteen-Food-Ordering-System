$(document).ready(function() {
    const walletHistoryContainer = $("#walletHistoryContainer");
    let isLoading = false;
	const searchBar = $("#searchBar");

    function loadPendingOrders(searchTerm = "") {
        if (isLoading) {
            return;
        }
        isLoading = true;

        $.ajax({
            url: '/FoodFrenzy/application/backend/php/admin/wallets/wallets history/wallets list/load_wallet_history_to_page.php',
            type: "POST",
            dataType: "json",
            data: {searchTerm: searchTerm},
            success: function(data) {
				if (data.success) {
					isLoading = false;
					const tableBody = $("#walletHistoryTableBody");
					tableBody.empty();

					if (data.noFoods) {
						$("#nothing_alert").show();
						$("#nothing_alert").html(data.content);
						$("#walletHistoryContainer").hide();
					} else {
						data.walletHistoryDetails.forEach(function (walletHistory) {
							$("#walletHistoryContainer").show();
							$("#nothing_alert").hide();
							const orderRow = $("<tr>");
							orderRow.html(`
								<td style="display: none;">${walletHistory.recordNumber}</td>
								<td>${walletHistory.userID}</td>
								<td>${walletHistory.transactionDate}</td>
								<td>${walletHistory.paymentMethod}</td>
								<td>${walletHistory.pastBalance}</td>
								<td>${walletHistory.transactionAmount}</td>
							`);
							
							orderRow.on("click", function() {
								const recordNumber = walletHistory.recordNumber;
								const encodedRecordNumber = encodeURIComponent(recordNumber);
								window.location.href = `/FoodFrenzy/application/backend/php/admin/wallets/wallets history/redirect to specific wallet history/redirect_to_specific_user_wallet_history.php?recordNumber=${encodedRecordNumber}`;
							});

							tableBody.append(orderRow);
						});
					}
				} else {
					if (data.error_page) {
						const encodedAlert = encodeURIComponent(data.alert);
						const goBackURL = "/FoodFrenzy/application/frontend/php/pages/admin/wallets/wallets history/wallet list/list_of_wallet_history.php"; 
						const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
						window.location.href = errorPageURL;
					}
				}
            },
            error: function(xhr, status, error) {
				const encodedAlert = encodeURIComponent(data.alert);
				const goBackURL = "/FoodFrenzy/application/frontend/php/pages/admin/wallets/wallets history/wallet list/list_of_wallet_history.php"; 
				const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
				window.location.href = errorPageURL;
					
            }
        });
    }

    loadPendingOrders();

    $(window).on("scroll", function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            loadPendingOrders();
        }
    });
	
	searchBar.on("input", function() {
        const searchTerm = searchBar.val().trim();
        loadPendingOrders(searchTerm);
	});
	
});
