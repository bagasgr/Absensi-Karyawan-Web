// JavaScript for handling any dynamic behavior (e.g., form validations, modals, etc.)

// Example: Prevent form submission with empty fields
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const inputFields = form.querySelectorAll('input[type="text"]');

    form.addEventListener('submit', function (event) {
        let isValid = true;
        
        // Loop through all input fields and check if they're empty
        inputFields.forEach(input => {
            if (input.value.trim() === '') {
                input.style.borderColor = 'red'; // Highlight invalid input
                isValid = false;
            } else {
                input.style.borderColor = '#ccc'; // Reset border color if valid
            }
        });

        // If form is not valid, prevent submission
        if (!isValid) {
            event.preventDefault();
            alert('Pastikan semua kolom diisi!');
        }
    });
});
