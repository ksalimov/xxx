import netteForms from 'nette-forms';

netteForms.showFormErrors = function (form, errors) {
    // Clear any previous error messages
    form.querySelectorAll('.error-message').forEach(function (el) {
        el.remove();
    });

    // Display errors near the corresponding form elements
    errors.forEach(function (error) {
        let input = error.element; // The form element that triggered the error
        let errorMessage = error.message;

        // Create an error message element
        let errorSpan = document.createElement('span');
        errorSpan.className = 'error-message';
        errorSpan.textContent = errorMessage;

        // Append the error message next to the form input
        input.parentNode.appendChild(errorSpan);
    });
};