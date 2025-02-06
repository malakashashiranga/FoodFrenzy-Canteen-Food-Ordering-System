$(document).ready(function() {
    function loadContactFormDetails() {
        $.ajax({
            url: '/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin/contact forms/replyed contact forms/replyed contact forms/load_replyed_contact_forms.php',
            type: "POST",
            dataType: "json",
            data: {},
            success: function(data) {
                if (data.success) {
                    if (data.contactFormsDetails && data.contactFormsDetails.length > 0) {
                        const tableBody = $("#contactFormsTableBody");
                        tableBody.empty();

                        $("#contactFormsContainer").show();
                        $("#nothing_alert").hide();

                        data.contactFormsDetails.forEach(function(cForm) {
                            const formRow = $("<tr>");
                            formRow.html(`
								<td style="display: none;">${cForm.number}</td>
                                <td>${cForm.senderName}</td>
                                <td>${cForm.senderEmail}</td>
                                <td>${cForm.replyedDate}</td>
                                <td>${cForm.replyedTime}</td>
                            `);

                            formRow.on("click", function() {
                                const formNumber = encodeURIComponent(cForm.number);
                                window.location.href = `/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin/contact forms/replyed contact forms/redirect to specific contact form/redirect_to_specific_replyed_contact_form.php?form_number=${formNumber}`;
                            });

                            tableBody.append(formRow);
                        });
                    } else {
                        $("#nothing_alert").show();
                        $("#nothing_alert").html("No replyed contact forms found.");
                        $("#contactFormsContainer").hide();
                    }
                } else {
                    if (data.error_page) {
                        const encodedAlert = encodeURIComponent(data.alert);
                        const goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/contact forms/replyed contact forms/replyed contact forms/replyed_contact_forms.php"; // Set your go back URL
                        const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                        window.location.href = errorPageURL;
                    }
                }
            },
            error: function(xhr, status, error) {
                const encodedAlert = encodeURIComponent("Error occurred while loading contact form details.");
                const goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/contact forms/replyed contact forms/replyed contact forms/replyed_contact_forms.php"; // Set your go back URL
                const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                window.location.href = errorPageURL;
            }
        });
    }

    loadContactFormDetails();

    $(window).on("scroll", function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            loadContactFormDetails();
        }
    });
});
