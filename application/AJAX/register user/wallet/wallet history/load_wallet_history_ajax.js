$(document).ready(function() {
    function getWalletHistoryDetails() {
        $.ajax({
            url: '/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/register user/wallet details/show wallet history/load_wallet_history.php',
            type: "POST",
            dataType: "json",
            data: {},
            success: function (data) {
                if (data.success) {
                    if (data.noWalletHistory) {
						$('#walletHistoryBody').empty();
						$(".userDetailsTable").hide();
						$("#noItemAlert").show();
						$("#noItemAlert").text(data.alert);
                    } else {
						$("#noItemAlert").hide();
                        const pastWalletDetails = data.pastWalletDetails;
						$('#walletHistoryBody').empty();
                        pastWalletDetails.forEach(function(walletItem) {
                            const row = `<tr>
											<td style="padding: 20px 70px;">${walletItem.date}</td>
                                            <td style="padding: 20px 70px;">${walletItem.pastBalance}</td>
                                            <td style="padding: 20px 70px;">${walletItem.transactionType}</td>
                                            <td style="padding: 20px 70px;">${walletItem.transactionBalance}</td>
                                            <td style="padding: 20px 70px;">${walletItem.newBalance}</td>
                                        </tr>`;
                            $('#walletHistoryBody').append(row);
                        });
                    }
                } else {
                    if (data.error_page) {
                        const encodedAlert = encodeURIComponent(data.alert);
                        const goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/register user/wallet history/wallet_history.php";
                        const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                        window.location.href = errorPageURL;
                    }
                }
            },
            error: function (xhr, status, error) {
                if (data.error_page) {
                    const encodedAlert = encodeURIComponent(data.alert);
                    const goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/register user/wallet history/wallet_history.php"; 
                    const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                    window.location.href = errorPageURL;
                }
            }
        });
    }
    getWalletHistoryDetails();
});
