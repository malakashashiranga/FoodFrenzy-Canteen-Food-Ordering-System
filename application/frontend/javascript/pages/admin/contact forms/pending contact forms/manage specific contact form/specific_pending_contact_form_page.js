normalizeTitles();
normalizeSubTitles();
selectTitle(contactFormsTitle);
hideAllSublists();
showSublist(contactFormList);
selectsubLists(newContactForms);
	
document.addEventListener("DOMContentLoaded", function() {
    var replyMessageField = document.getElementById("reply");
    var otherFields = document.querySelectorAll(".fill_bars");

    otherFields.forEach(function(field) {
        field.disabled = true;
    });

    replyMessageField.disabled = false;
});
