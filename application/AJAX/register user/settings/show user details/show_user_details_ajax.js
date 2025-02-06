$(document).ready(function() {
    
    function userDetailsShow() {
        $.ajax({
            url: '/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin and register user/settings/show profile details/profile_details.php', 
            method: 'POST', 
            dataType: 'json', 
            success: function (data) {
                if (data.success) {
					document.getElementById('user_id_block').innerText = data.user_id;
					document.getElementById('prof_first_name').value = data.first_name;
					document.getElementById('prof_last_name').value = data.last_name;
					document.getElementById('email').value = data.email;
					document.getElementById('phone').value = data.mobile_number;
					document.getElementById('address').value = data.address;
					if (data.photo_path) {
						$('#profile_image').attr('src', data.photo_path);	
						document.getElementById('img_remv').style.display = 'block';
					}
                } else {
                    if (data.error_page) {
                        const encodedAlert = encodeURIComponent(data.alert);
                        goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/register user/settings/settings.php";
                        const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                        window.location.href = errorPageURL;
                    }
                }
            },
            error: function (xhr, status, error) {
                const encodedError = encodeURIComponent(error);
				const goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/register user/settings/settings.php"; 
				const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedError}&goBackURL=${encodeURIComponent(goBackURL)}`;
				window.location.href = errorPageURL;
            }
        });
    }

    $('#loadDataButton').click(function() {
        userDetailsShow();
    });

    userDetailsShow();
});
