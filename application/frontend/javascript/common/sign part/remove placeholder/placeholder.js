const textBars = document.querySelectorAll('.input_box');

textBars.forEach(input => {
    input.addEventListener('focus', () => {
        input.classList.add('hide-placeholder');
    });

    input.addEventListener('blur', () => {
        if (input.value === '') {
            input.classList.remove('hide-placeholder');
        }
    });
});