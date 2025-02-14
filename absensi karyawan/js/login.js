// Function to validate login form
function validateLoginForm(event) {
    event.preventDefault();

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const errorMessage = document.getElementById('error-message');

    if (username === '' || password === '') {
        errorMessage.textContent = 'Both fields are required!';
    } else {
        // In real scenario, you would send the data to the server for verification.
        // For now, we'll just log them to the console.
        console.log(`Username: ${username}, Password: ${password}`);

        // Reset error message on successful input
        errorMessage.textContent = '';
        
        // Simulate successful login and redirect (if applicable)
        alert('Login successful!');
        window.location.href = 'dashboard.php';  // Redirect to the dashboard page (example)
    }
}

// Attach the event listener to the form submit button
document.querySelector('form').addEventListener('submit', validateLoginForm);
