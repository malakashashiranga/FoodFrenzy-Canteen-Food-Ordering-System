const checkOutButton = document.getElementById('checkOutButton');
const foodAlertCross = document.getElementById('food_alert_cross');
const checkOutFieldAlert = document.querySelector('.checkOutFieldAlert');
const fullScreen1 = document.querySelector('.full_screen');
const radioButtons = document.querySelectorAll('.payTypeRadio');
const walletPayAlert = document.getElementById('walletPayAlert');
const payAlert = document.getElementById('PayAlert');
var orderHistoryPageLink = document.getElementById("order_history_page_link");


checkOutButton.addEventListener('click', function() {
    showCheckOutFieldAlert();
});

foodAlertCross.addEventListener('click', function() {
    hideCheckOutFieldAlert();
});

function showCheckOutFieldAlert() {
    fullScreen1.classList.add('disable-interaction');
    fullScreen1.style.filter = 'blur(1px)';
    checkOutFieldAlert.style.display = 'block';
}

function hideCheckOutFieldAlert() {
    fullScreen1.classList.remove('disable-interaction');
    fullScreen1.style.filter = 'none';
    checkOutFieldAlert.style.display = 'none';
    radioButtons.forEach(radio => {
        radio.checked = false;
    });
    walletPayAlert.textContent = '';
	payAlert.textContent = '';
}

orderHistoryPageLink.addEventListener("click", function () {
	window.location.href = "/FoodFrenzy/application/frontend/php/pages/register user/order history/order_history.php";
});

