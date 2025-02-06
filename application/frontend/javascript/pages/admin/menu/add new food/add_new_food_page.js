normalizeTitles();
normalizeSubTitles();
selectTitle(menuTitle);
hideAllSublists();
showSublist(menuList);
selectsubLists(addNewFoods);


const imageUpload = document.getElementById("imageUpload");
const foodImage = document.getElementById("food_image");

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


const discountPrice = document.getElementById("discount_price");
const nonDiscountPrice = document.getElementById("non_discount_price");


function restrictToNumbers(event) {
  const inputElement = event.target;
  let value = inputElement.value;
  
  value = value.replace(/[^\d.]+/g, '');

  const dotCount = value.split('.').length - 1;
  if (dotCount > 1) {
    value = value.replace(/\./g, '');
  }

  inputElement.value = value;
}

discountPrice.addEventListener("input", restrictToNumbers);
nonDiscountPrice.addEventListener("input", restrictToNumbers);
