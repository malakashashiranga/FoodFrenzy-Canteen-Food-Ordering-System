normalizeTitles();
normalizeSubTitles();
selectTitle(manageWalletsTitle);
hideAllSublists();
showSublist(walletList);
selectsubLists(updateUserWallets);

document.addEventListener("DOMContentLoaded", function() {
    var transactionTypeRadios = document.querySelectorAll("input[name='transaction_type']");
    var cashPaymentField = document.getElementById("cash_payment");
    var otherFields = document.querySelectorAll(".fill_bars");

    otherFields.forEach(function(field) {
        field.disabled = true;
    });

    transactionTypeRadios.forEach(function(radio) {
        radio.disabled = false;
        radio.addEventListener("change", handleRadioChange);
    });
    cashPaymentField.disabled = false;

    function handleRadioChange() {
        // Disable other radio buttons
        transactionTypeRadios.forEach(function(radio) {
            if (!radio.checked) {
                radio.disabled = true;
            }
        });

        cashPaymentField.disabled = false;
    }
});


const cashPayment = document.getElementById("cash_payment");

function restrictToNumbers(event) {
  const inputElement = event.target;
  let value = inputElement.value;
  
  // Replace all non-numeric characters except one dot
  value = value.replace(/[^\d.]+/g, '');

  // Ensure there's only one dot
  const dotCount = value.split('.').length - 1;
  if (dotCount > 1) {
    // More than one dot, remove the extras
    value = value.replace(/\./g, '');
  }

  // Update the input value
  inputElement.value = value;
}

cashPayment.addEventListener("input", restrictToNumbers);
