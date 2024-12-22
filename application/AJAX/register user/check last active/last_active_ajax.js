$(document).ready(function() {
    function getLastActiveTime() {
        $.ajax({
            type: 'POST',
            url: '/FoodFrenzy/application/backend/php/register user/update user last active/update_last_active.php',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    console.log("Last response time is recorded.");
                } else {
                    console.error("Last response AJAX request failed. Message: " + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Last response AJAX error:", error);
            }
        });
    }

    getLastActiveTime();

    setInterval(getLastActiveTime, 60000);
});
