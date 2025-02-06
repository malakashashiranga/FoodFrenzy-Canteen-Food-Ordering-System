document.addEventListener("DOMContentLoaded", function() {
    const showPasswordCheckbox = document.getElementById("showPassword");
    const newPasswordInput = document.getElementById("newPassword");
    let retypePasswordInput = document.getElementById("retypePassword");

    showPasswordCheckbox.addEventListener("change", function() {
        newPasswordInput.type = this.checked ? "text" : "password";
        if (retypePasswordInput) {
            retypePasswordInput.type = this.checked ? "text" : "password";
        }
    });

    const inputBoxes = document.querySelectorAll(".input_box");
    inputBoxes.forEach(function(inputBox) {
        inputBox.addEventListener("focus", function() {
            showPasswordCheckbox.checked = false;
            newPasswordInput.type = "password";
            if (retypePasswordInput) {
                retypePasswordInput.type = "password";
            }
        });
    });
});
