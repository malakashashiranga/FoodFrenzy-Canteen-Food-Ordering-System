normalizeTitles();
selectTitle(settingsTitle);
hideAllSublists();
	
disableFormFields('info_form');
disableFormFields('email_form');
disableFormFields('password_form');

	
function enableFormFields(formId) {
    const form = document.getElementById(formId);
    const inputFields = form.querySelectorAll('input');

    inputFields.forEach((input) => {
        input.removeAttribute('disabled');
    });
}


function disableFormFields(formId) {
    const form = document.getElementById(formId);
    const inputFields = form.querySelectorAll('input');

    inputFields.forEach((input) => {
        input.setAttribute('disabled', 'disabled');
    });
}


function hideElement(elementIdentifier, elementType) {
    const elements = elementType === 'id'
        ? [document.getElementById(elementIdentifier)]
        : Array.from(document.getElementsByClassName(elementIdentifier));

    elements.forEach(element => {
        if (element) {
            element.style.display = 'none';
        }
    });
}


function showElement(elementIdentifier, elementType) {
    const elements = elementType === 'id'
        ? [document.getElementById(elementIdentifier)]
        : Array.from(document.getElementsByClassName(elementIdentifier));

    elements.forEach(element => {
        if (element) {
            element.style.display = 'block';
        }
    });
}


const fillBars = document.querySelectorAll(".fill_bars");

fillBars.forEach(function(field, index) {
    field.addEventListener("keydown", function(event) {
      if (event.key === "Enter") {
        event.preventDefault();
        if (index < fillBars.length - 1) {
          fillBars[index + 1].focus();
        }
      }
    });
});


var editDetailsButton = document.getElementById('prof_info');
var saveDetailsButton = document.getElementById('conf_change_prof_info');

email_form

var changePasswordButton = document.getElementById('password_ch');
var savePasswordButton = document.getElementById('conf_change_pword');

const showPasswordCheckbox = document.getElementById("showPassword");
const currentPasswordInput = document.getElementById("currentPassword");
const newPasswordInput = document.getElementById("newPassword");
const confirmPasswordInput = document.getElementById("confirmPassword");


showPasswordCheckbox.addEventListener("change", function() {
    const type = this.checked ? "text" : "password";
    currentPasswordInput.type = type;
    newPasswordInput.type = type;
    confirmPasswordInput.type = type;
});

currentPasswordInput.addEventListener("focus", function() {
    hidePasswordCheckbox();
});
newPasswordInput.addEventListener("focus", function() {
    hidePasswordCheckbox();
});
confirmPasswordInput.addEventListener("focus", function() {
    hidePasswordCheckbox();
});

document.getElementById("phone_number").addEventListener("input", function() {
  var value = this.value;
  this.value = value.replace(/\D/g, '');
});


function hidePasswordCheckbox() {
	showPasswordCheckbox.checked = false;
	currentPasswordInput.type = "password";
    newPasswordInput.type = "password";
    confirmPasswordInput.type = "password";
}


function clearForm(formId) {
    var form = document.getElementById(formId);
    var elements = form.elements;
    for (var i = 0; i < elements.length; i++) {
        if (elements[i].type === 'text' || elements[i].type === 'password') {
            elements[i].value = '';
        } else if (elements[i].type === 'file') {
            elements[i].value = ''; // Reset file input by clearing its value
        } else if (elements[i].tagName === 'TEXTAREA') {
            elements[i].value = '';
        }
    }
}


function profInfoButtonFunc() {
	enableFormFields('info_form');
	clearForm('password_form');
    showElement('update_ques', 'id');
    hideElement('password_change_section', 'id');
    disableFormFields('password_form');
	editDetailsButton.style.display = 'none';
	saveDetailsButton.style.display = 'block';
	changePasswordButton.style.display = 'block';
	savePasswordButton.style.display = 'none';
	disableFormFields('email_form');
}

function emailEditButtonFunc() {
	enableFormFields('email_form');
	clearForm('password_form');
    showElement('update_ques', 'id');
    hideElement('password_change_section', 'id');
    disableFormFields('password_form');
	changePasswordButton.style.display = 'block';
	savePasswordButton.style.display = 'none';
	saveDetailsButton.style.marginTop = '20px';
}


function passChangeButtonFunc() {
	showElement('password_change_section', 'id');
	enableFormFields('password_form');
    hideElement('update_ques', 'id');
    disableFormFields('info_form');
	changePasswordButton.style.display = 'none';
	savePasswordButton.style.display = 'block';
	editDetailsButton.style.display = 'block';
	saveDetailsButton.style.display = 'none';
	disableFormFields('email_form');
}

function defPageCreateFunc() {
	showElement('update_ques', 'id');
    disableFormFields('info_form');
	clearForm('password_form');
    disableFormFields('password_form');
    hideElement('password_change_section', 'id');
	changePasswordButton.style.display = 'block';
	savePasswordButton.style.display = 'none';
	editDetailsButton.style.display = 'block';
	saveDetailsButton.style.display = 'none';
	disableFormFields('email_form');
}
