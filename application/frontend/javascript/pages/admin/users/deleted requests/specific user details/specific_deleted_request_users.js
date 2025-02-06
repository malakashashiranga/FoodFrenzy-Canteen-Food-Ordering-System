normalizeTitles();
normalizeSubTitles();
selectTitle(usersTitle);
hideAllSublists();
showSublist(userList);
selectsubLists(deleteRequests);


disableFormFields('specific_user_details_form');

function disableFormFields(formId) {
    const form = document.getElementById(formId);
    const inputFields = form.querySelectorAll('input, textarea'); // Include <textarea>

    inputFields.forEach((input) => {
        input.setAttribute('disabled', 'disabled');
    });
}