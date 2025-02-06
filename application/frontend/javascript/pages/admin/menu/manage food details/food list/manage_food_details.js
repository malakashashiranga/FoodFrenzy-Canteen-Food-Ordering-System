normalizeTitles();
normalizeSubTitles();
selectTitle(menuTitle);
hideAllSublists();
showSublist(menuList);
selectsubLists(manageFoodsDetails);


$(document).ready(function() {
    adjustDropdownWidth();

    $('#dropdown').on('change', function() {
        adjustDropdownWidth();
    });
    
    function adjustDropdownWidth() {
        const dropdown = $('#dropdown');
        const selectedOptionText = dropdown.find(':selected').text();

        const textWidth = selectedOptionText.length * 9; // Adjust the multiplier as needed
        
        dropdown.css('width', textWidth + 'px');
    }
});

