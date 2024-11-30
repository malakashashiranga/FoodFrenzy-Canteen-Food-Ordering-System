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
            url: '/FoodFrenzy/application/backend/php/admin/users/user details/user list/load_users_details.php',
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
							const orderRow = $("<tr>");
							orderRow.html(`
								<td>${userDetails.userID}</td>
								<td>${userDetails.userName}</td>
								<td>${userDetails.email}</td>
								<td>${userDetails.mobileNumber}</td>
								<td>${userDetails.registeredDate}</td>
							`);
							
							orderRow.on("click", function() {
								const userID = userDetails.userID;
								const encodedUserID = encodeURIComponent(userID);
								window.location.href = `/FoodFrenzy/application/backend/php/admin/users/user details/redirect to specific user details/redirect_to_specific_user_details.php?userID=${encodedUserID}`;
							});

							
							tableBody.append(orderRow);
						});
					}
				} else {
					if (data.error_page) {
						const encodedAlert = encodeURIComponent(data.alert);
						const goBackURL = "/FoodFrenzy/application/frontend/php/pages/admin/users/user details/user list/users_details.php"; 
						const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
						window.location.href = errorPageURL;
					}
				}
            },
            error: function(xhr, status, error) {
				const encodedAlert = encodeURIComponent(data.alert);
				const goBackURL = "/FoodFrenzy/application/frontend/php/pages/admin/users/user details/user list/users_details.php"; 
				const errorPageURL = `/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
				window.location.href = errorPageURL;
					
            }
        });
    }

    // Initially load some food details
    loadUsersDetails();

    // Implement infinite scrolling by loading more items when the user reaches the bottom
    $(window).on("scroll", function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            loadUsersDetails();
        }
    });

    // Attach an event listener to the category dropdown
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
