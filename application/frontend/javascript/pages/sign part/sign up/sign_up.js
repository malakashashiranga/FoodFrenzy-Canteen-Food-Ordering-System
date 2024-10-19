document.getElementById("mobileNumber").addEventListener("input", function() {
  var value = this.value;
  this.value = value.replace(/\D/g, '');
});

function toSignIpPage() {
    window.location.href = "/FoodFrenzy/application/frontend/php/pages/sign part/sign in/sign_in.php";
}