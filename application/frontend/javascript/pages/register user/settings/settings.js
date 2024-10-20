var userImage = document.getElementById("user_image");
var imageForm = document.getElementById("image_form");
var picCancelButton = document.getElementById("pic_cancel_button");
var picConfirmButton = document.getElementById("pic_confirm_button");

var borderColorChanged = false;

disableFormFields('info_form');
disableFormFields('password_form');
disableFormFields('editEmail');

var isPasswordFormVisible = false; 

var imageUploadInput = document.getElementById('imageUpload');

const settingContainer = document.querySelector('.setting_container');

var editDetailsButton = document.getElementById('prof_info');
var saveDetailsButton = document.getElementById('conf_change_prof_info');

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


imageUploadInput.addEventListener('change', function() {
    picConfirmButton.style.display = this.files.length > 0 ? 'block' : 'none';
});

picCancelButton.addEventListener('click', function() {
    defPageCreateFunc();
    imageUploadInput.value = ''; 
    picConfirmButton.style.display = 'none';
});


function hidePasswordCheckbox() {
	showPasswordCheckbox.checked = false;
	currentPasswordInput.type = "password";
    newPasswordInput.type = "password";
    confirmPasswordInput.type = "password";
}


document.getElementById("phone").addEventListener("input", function() {
  var value = this.value;
  this.value = value.replace(/\D/g, '');
});

userImage.addEventListener("click", function () {
	defPageCreateFunc();
    if (borderColorChanged) {
        hidePictureSegment();
    } else {
        showPictureSegment();
    }
    borderColorChanged = !borderColorChanged;
});


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


function showPictureSegment() {
    userImage.style.borderColor = "#F27622";
	settingContainer.style.top = '320px';
	showElement('image_form', 'class');

}


function hidePictureSegment() {
    userImage.style.borderColor = "#FFFFFF";
	settingContainer.style.top = '240px'; 
	hideElement('image_form', 'class');
	
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
	clearForm('image_form');
	clearForm('password_form');
    showElement('update_ques', 'id');
    hideElement('password_change_section', 'id');
    disableFormFields('password_form');
    hidePictureSegment();
	editDetailsButton.style.display = 'none';
	saveDetailsButton.style.display = 'block';
	changePasswordButton.style.display = 'block';
	savePasswordButton.style.display = 'none';
	disableFormFields('editEmail');
}

function emailEditButtonFunc() {
	enableFormFields('editEmail');
	clearForm('image_form');
	clearForm('password_form');
    showElement('update_ques', 'id');
    hideElement('password_change_section', 'id');
    disableFormFields('password_form');
    hidePictureSegment();
	changePasswordButton.style.display = 'block';
	savePasswordButton.style.display = 'none';
	saveDetailsButton.style.marginTop = '20px';
}


function passChangeButtonFunc() {
	showElement('password_change_section', 'id');
	enableFormFields('password_form');
    hideElement('update_ques', 'id');
    disableFormFields('info_form');
    hidePictureSegment();
	changePasswordButton.style.display = 'none';
	savePasswordButton.style.display = 'block';
	editDetailsButton.style.display = 'block';
	saveDetailsButton.style.display = 'none';
	disableFormFields('editEmail');
}

function defPageCreateFunc() {
	showElement('update_ques', 'id');
    disableFormFields('info_form');
	clearForm('image_form');
	clearForm('password_form');
    disableFormFields('password_form');
    hideElement('password_change_section', 'id');
    hidePictureSegment();
	changePasswordButton.style.display = 'block';
	savePasswordButton.style.display = 'none';
	editDetailsButton.style.display = 'block';
	saveDetailsButton.style.display = 'none';
	disableFormFields('editEmail');
}


const inputFields = document.querySelectorAll(".info_bars");

inputFields.forEach(function(field, index) {
    field.addEventListener("keydown", function(event) {
      if (event.key === "Enter") {
        event.preventDefault();
        if (index < inputFields.length - 1) {
          inputFields[index + 1].focus();
        }
      }
    });
});
