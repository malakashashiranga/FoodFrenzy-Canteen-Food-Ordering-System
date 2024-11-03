document.addEventListener("DOMContentLoaded", function() {
	const form = document.querySelector("form");

	form.addEventListener("submit", function(event) {
		const requiredFields = form.querySelectorAll(".required");

		requiredFields.forEach(function(field) {
			if (!field.value.trim()) {
				field.classList.add("error");
			} else {
				field.classList.remove("error");
			}
		});
	});

	const inputFields = document.querySelectorAll(".required");
	inputFields.forEach(function(field) {
		field.addEventListener("input", function() {
		field.classList.remove("error");
		});
	});
});
