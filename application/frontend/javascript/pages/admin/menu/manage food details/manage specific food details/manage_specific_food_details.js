normalizeTitles();
normalizeSubTitles();
selectTitle(menuTitle);
hideAllSublists();
showSublist(menuList);
selectsubLists(manageFoodsDetails);

const editButton = document.getElementById('edit_button');
const cancelButton = document.getElementById('cancel_button');
const submitButton = document.getElementById('submit_button');
const deleteButton = document.getElementById('delete_button');

const imageUploadSegment = document.getElementById('image_upload_segment');

const imageUpload = document.getElementById("imageUpload");
const foodImage = document.getElementById("food_image");

disableFormFields('food_form');

function enableFormFields(formId) {
    const form = document.getElementById(formId);
    const inputFields = form.querySelectorAll('input, textarea'); // Include <textarea>

    inputFields.forEach((input) => {
        input.removeAttribute('disabled');
    });
}

function disableFormFields(formId) {
    const form = document.getElementById(formId);
    const inputFields = form.querySelectorAll('input, textarea'); // Include <textarea>

    inputFields.forEach((input) => {
        input.setAttribute('disabled', 'disabled');
    });
}


// Check if foodDetails are available
if (typeof foodDetails !== 'undefined') {
    // Get the "title_food_name" element by ID
    const titleFoodNameElement = document.getElementById('title_food_name');

    // Check if the element exists
    if (titleFoodNameElement) {
        // Update the content of the element with the food name
        titleFoodNameElement.textContent = foodDetails.foodName;
    }
}


function editFoodButtonFunc() {
	editButton.style.display = 'none';
	cancelButton.style.display = 'block';
	submitButton.style.display = 'block';
	enableFormFields('food_form');
	imageUploadSegment.style.display = 'block';
}


function deleteFoodButtonFunc() {
	editButton.style.display = 'block';
	cancelButton.style.display = 'none';
	submitButton.style.display = 'none';
	deleteButton.style.display = 'none';
	disableFormFields('food_form');
	imageUploadSegment.style.display = 'none';
} 


function cancelFoodChangeButtonFunc() {
	editButton.style.display = 'block';
	cancelButton.style.display = 'none';
	submitButton.style.display = 'none';
	disableFormFields('food_form');
	imageUploadSegment.style.display = 'none';

}


function saveFoodUpdatedFunc() {
	editButton.style.display = 'block';
	cancelButton.style.display = 'none';
	submitButton.style.display = 'none';
	disableFormFields('food_form');
	imageUploadSegment.style.display = 'none';
}


document.getElementById('alert_first_btn').addEventListener('click', function() {
	deleteButton.style.display = 'block';
});


imageUpload.addEventListener("change", function () {
    const file = imageUpload.files[0]; 
    if (file) {
        const reader = new FileReader();

        reader.addEventListener("load", function () {
            foodImage.src = reader.result; 
        });

        reader.readAsDataURL(file);
    } else {
        foodImage.src = "";
    }
});


function disableRadioButtons() {
    const transactionTypeRadios = document.querySelectorAll("input[name='transaction_type']");
    
    transactionTypeRadios.forEach(function(radio) {
        radio.disabled = true;
    });
}

// Enable radio buttons when needed
function enableRadioButtons() {
    const transactionTypeRadios = document.querySelectorAll("input[name='transaction_type']");
    
    transactionTypeRadios.forEach(function(radio) {
        radio.disabled = false;
    });
}

// Add event listener to handle radio button change
document.addEventListener("DOMContentLoaded", function() {
    const transactionTypeRadios = document.querySelectorAll("input[name='transaction_type']");

    // Disable radio buttons initially
    disableRadioButtons();

    transactionTypeRadios.forEach(function(radio) {
        radio.addEventListener("change", function(event) {
            // Check your conditions here
            const selectedValue = event.target.value;

            // Example condition: Disable radio buttons if selected value is 'withdraw'
            if (selectedValue === 'withdraw') {
                disableRadioButtons();
            } else {
                enableRadioButtons();
            }
        });
    });
});