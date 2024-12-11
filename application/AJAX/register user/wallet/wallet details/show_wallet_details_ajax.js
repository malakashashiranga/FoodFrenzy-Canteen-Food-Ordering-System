$(document).ready(function() {
    
    function loadWalletDetails() {
        $.ajax({
            url: '/FoodFrenzy/application/backend/php/register user/wallet details/show current details/get_wallet_details.php',
            type: "POST",
            dataType: "json",
            data: {},
            success: function(data) {
                if (data.success) {
					$("#user_id").text(data.userID);
					$("#wallet_bal").text(data.currenBalance);
                } else {
                    if (data.error_page) {
                        const encodedAlert = encodeURIComponent(data.alert);
                        const goBackURL = "/FoodFrenzy/application/frontend/php/pages/register user/wallet/wallet.php"; 
                        const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                        window.location.href = errorPageURL;
                    }
                }
            },
            error: function(xhr, status, error) {
                const encodedAlert = encodeURIComponent(data.alert);
                const goBackURL = "/FoodFrenzy/application/frontend/php/pages/register user/wallet/wallet.php"; 
                const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                window.location.href = errorPageURL;
            }
        });
    }
    loadWalletDetails();
});
