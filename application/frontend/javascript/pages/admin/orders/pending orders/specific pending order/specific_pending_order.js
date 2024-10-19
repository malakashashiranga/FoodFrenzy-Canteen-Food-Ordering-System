normalizeTitles();
normalizeSubTitles();
selectTitle(ordersTitle);
hideAllSublists();
showSublist(ordersList);
selectsubLists(currentOrder);

var inputFields = document.querySelectorAll('.fill_bars');

inputFields.forEach(function(field) {
    field.disabled = true;
});
