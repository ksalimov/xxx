import netteForms from 'nette-forms';

netteForms.showFormErrors = function (form, errors) {
    // Clear any previous error messages
    form.querySelectorAll('.form__error').forEach(function (el) {
        el.remove();
    });

    // Remove 'is-invalid' class from the form
    form.classList.remove('is-invalid');

    // If there are errors, add 'is-invalid' class to the form
    if (errors.length > 0) {
        form.classList.add('is-invalid'); // Add 'is-invalid' to the form
    }

    // Display errors near the corresponding form elements
    errors.forEach(function (error) {
        let input = error.element; // The form element that triggered the error
        let errorMessage = error.message;

        // Create an error message element
        let errorSpan = document.createElement('div');
        errorSpan.className = 'form__error';
        errorSpan.textContent = errorMessage;

        // Append the error message next to the form input
        input.parentNode.appendChild(errorSpan);
    });
};