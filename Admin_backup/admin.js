// admin.js
document.addEventListener('DOMContentLoaded', function () {
    const adminLoginForm = document.getElementById('adminLoginForm');
    const usernameError = document.getElementById('usernameError');
    const passwordError = document.getElementById('passwordError');

    adminLoginForm.addEventListener('submit', function (event) {
        let username = document.getElementById('username').value;
        let password = document.getElementById('password').value;
        let hasError = false;

        if (!username) {
            usernameError.textContent = 'Username is required.';
            hasError = true;
        } else {
            usernameError.textContent = '';
        }

        if (!password) {
            passwordError.textContent = 'Password is required.';
            hasError = true;
        } else {
            passwordError.textContent = '';
        }

        if (hasError) {
            event.preventDefault();
        }
    });
});