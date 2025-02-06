document.addEventListener("DOMContentLoaded", function() {
	const inputFields = document.querySelectorAll(".input_box");

	inputFields.forEach(function(field, index) {
		field.addEventListener("keydown", function(event) {
			if (event.key === "Enter") {
				event.preventDefault();
				if (index < inputFields.length - 1) {
					inputFields[index + 1].focus();
				} else if (index === inputFields.length - 1) {
					document.querySelector(".button").click();
				}
			}
		});
	});
});
