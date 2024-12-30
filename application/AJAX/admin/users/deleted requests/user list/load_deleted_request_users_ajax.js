$(document).ready(function() {
    const usersDetailsContainer = $("#usersDetailsContainer");
    let isLoading = false;
	const searchBar = $("#searchBar");

    // Function to load food items
    function loadUsersDetails(searchTerm = "") {
        if (isLoading) {
            return;
        }
        isLoading = true;

        $.ajax({
            url: '/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin/users/deleted requests/user list/load_deleted_request_users.php',
            type: "POST",
            dataType: "json",
            data: {searchTerm: searchTerm},
            success: function(data) {
				if (data.success) {
					isLoading = false;
					const tableBody = $("#usersDetailsTableBody");
					tableBody.empty();

					if (data.noFoods) {
						$("#nothing_alert").show();
						$("#nothing_alert").html(data.content);
						$("#usersDetailsContainer").hide();
					} else {
						data.userDetails.forEach(function (userDetails) {
							$("#usersDetailsContainer").show();
							$("#nothing_alert").hide();
							const userRow = $("<tr>");
							userRow.html(`
								<td>${userDetails.userID}</td>
								<td>${userDetails.userName}</td>
								<td>${userDetails.email}</td>
								<td>${userDetails.mobileNumber}</td>
								<td>${userDetails.lastActive}</td>
							`);
							
							userRow.on("click", function() {
								const userID = userDetails.userID;
								const encodedUserID = encodeURIComponent(userID);
								window.location.href = `/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin/users/deleted requests/redirect to specific user details/redirect_to_specific_deleted_request.php?userID=${encodedUserID}`;
							});

							tableBody.append(userRow);
						});
					}
				} else {
					if (data.error_page) {
						const encodedAlert = encodeURIComponent(data.alert);
						const goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/users/deleted requests/user list/deleted_request_users.php"; 
						const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
						window.location.href = errorPageURL;
					}
				}
            },
            error: function(xhr, status, error) {
				const encodedAlert = encodeURIComponent(data.alert);
				const goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/users/deleted requests/user list/deleted_request_users.php"; 
				const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
				window.location.href = errorPageURL;
					
            }
        });
    }

    loadUsersDetails();

    $(window).on("scroll", function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            loadUsersDetails();
        }
    });

    $("#dropdown").on("change", function() {
        const selectedCategory = $(this).val();
		const searchTerm = searchBar.val().trim();
        loadUsersDetails(searchTerm);
    });
	
	searchBar.on("input", function() {
        const searchTerm = searchBar.val().trim();
        loadUsersDetails(searchTerm);
	});
	
});
