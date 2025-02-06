$(document).ready(function() {

    function loadContactFormDetails() {
        $.ajax({
            url: '/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin/contact forms/pending contact forms/pending contact forms/load_pending_contact_forms.php',
            type: "POST",
            dataType: "json",
            data: {},
            success: function(data) {
                if (data.success) {
                    if (data.noContactForms) {
                        $("#nothing_alert").show();
                        $("#nothing_alert").html(data.content);
                        $("#contactFormsContainer").hide();
                    } else if (data.finishTable !== true) {
                        const tableBody = $("#contactFormsTableBody");

                        $("#contactFormsContainer").show();
                        $("#nothing_alert").hide();

                        data.contactFormsDetails.forEach(function(cForm) {
                            const formRow = $("<tr>");
                            formRow.html(`
                                <td>${cForm.number}</td>
                                <td>${cForm.senderName}</td>
                                <td>${cForm.senderEmail}</td>
                                <td>${cForm.senderPhoneNumber}</td>
                            `);

                            formRow.on("click", function() {
                                const formNumber = encodeURIComponent(cForm.number);
                                window.location.href = `/FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/admin/contact forms/pending contact forms/redirect to specific contact form/redirect_to_specific_contact_form.php?form_number=${formNumber}`;
                            });
                            tableBody.append(formRow);
                        });
                    }
                } else {
                    if (data.error_page) {
                        const encodedAlert = encodeURIComponent(data.alert);
                        const goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/contact forms/pending contact forms/pending contact forms/pending_contact_forms_page.php";
                        const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                        window.location.href = errorPageURL;
                    }
                }
            },
            error: function(xhr, status, error) {
                const encodedAlert = encodeURIComponent(error);
                const goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/contact forms/pending contact forms/pending contact forms/pending_contact_forms_page.php";
                const errorPageURL = `/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html?alert=${encodedAlert}&goBackURL=${encodeURIComponent(goBackURL)}`;
                window.location.href = errorPageURL;
            }
        });
    }

    loadContactFormDetails();

});
