document.getElementById("verifyCode").addEventListener("input", function() {
    var value = this.value;
    this.value = value.replace(/\D/g, '');
});

document.getElementById("resend").addEventListener("click", function() {
    document.getElementById("verifyCode").value = ""; // Clear the input field
});

