$(document).ready(function() {
	const searchBar = $("#searchBar");
    
    function loadWalletDetails(searchTerm = '') {
        $.ajax({
            url: '/FoodFrenzy/application/backend/php/admin/wallets/update wallets/wallet list/load_users_wallets_details.php',
            type: "POST",
            dataType: "json",
            data: { searchTerm: searchTerm },
            success: function(data) {
                if (data.success) {
					if (data.noWallets) {
						$("#nothing_alert").show();
						$("#nothing_alert").html(data.content);
						$("#walletContainer").hide();
					} else {
						const tableBody = $("#walletTableBody");
						tableBody.empty();

						if (data.walletDetails && data.walletDetails.length > 0) {
							$("#walletContainer").show();
							$("#nothing_alert").hide();

							data.walletDetails.forEach(function (wallet) {
								const walletRow = $("<tr>");
								walletRow.html(`
									<td>${wallet.user_id}</td>
									<td>${wallet.user_name}</td>
									<td>${wallet.transaction_date}</td>
									<td>${wallet.transaction_method}</td>
									<td>${wallet.current_balance}</td>
								`);
								
								walletRow.on("click", function() {
									const userID = encodeURIComponent(wallet.user_id);
									window.location.href = `/FoodFrenzy/application/backend/php/admin/wallets/update wallets/redirect to specific wallet/redirect_to_specific_wallet.php?user_id=${userID}`;
								});

								tableBody.append(walletRow);
							});
							$("#totalWalletCount").text(data.totalWalletBalance);
						} else {
							$("#nothing_alert").show();
							$("#nothing_alert").html(data.content);
							$("#walletContainer").hide();
						}
					}
                } else {
                    if (data.error_page) {
                        const encodedAlert = encodeURIComponent(data.alert);
                        const goBackURL = "/FoodFrenzy/application/frontend/php/pages/admin/wallets/update wallets/wallet list/list_of_wallets.php"; 
                        const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                        window.location.href = errorPageURL;
                    }
                }
            },
            error: function(xhr, status, error) {
                const encodedAlert = encodeURIComponent(error);
                const goBackURL = "/FoodFrenzy/application/frontend/php/pages/admin/wallets/update wallets/wallet list/list_of_wallets.php"; 
                const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                window.location.href = errorPageURL;
            }
        });
    }

    loadWalletDetails('');

    $(window).on("scroll", function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            loadWalletDetails();
        }
    });
	
	$('#searchBar').keyup(function() {
		const selectedCategory = $("#dropdown").val();
		const searchTerm = $(this).val().trim();
		loadWalletDetails(searchTerm); // Changed from loadFoodItems to loadWalletDetails
	});

});
