normalizeTitles();
normalizeSubTitles();
selectTitle(contactFormsTitle);
hideAllSublists();
showSublist(contactFormList);
selectsubLists(pastContactForms);


document.addEventListener("DOMContentLoaded", function() {
    var otherFields = document.querySelectorAll(".fill_bars");

    otherFields.forEach(function(field) {
        field.disabled = true;
    });
});
