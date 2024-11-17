document.addEventListener('DOMContentLoaded', function () {
    const newPasswordInput = document.getElementById('new-password');
    const confirmPasswordInput = document.getElementById('confirm-password');
    const passwordMessage = document.getElementById('password-message');
    const resetPasswordBtn = document.getElementById('resetPasswordBtn');

    // Regular expression for password validation
    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    // Function to validate the password fields
    function validatePasswords() {
        const newPassword = newPasswordInput.value.trim();
        const confirmPassword = confirmPasswordInput.value.trim();

        passwordMessage.innerText = ''; // Clear previous messages

        // Check if new password is valid
        if (!passwordPattern.test(newPassword)) {
            passwordMessage.innerText = 'New password must have at least 8 characters, including upper, lower, number, and special character.';
            resetPasswordBtn.disabled = true; // Disable button
            return;
        }

        // Check if passwords match
        if (newPassword !== confirmPassword) {
            passwordMessage.innerText = 'Passwords do not match!';
            resetPasswordBtn.disabled = true; // Disable button
            return;
        }

        // If both checks pass, enable the button
        resetPasswordBtn.disabled = false;
        passwordMessage.innerText = ''; // Clear any messages
    }

    // Add event listeners for real-time validation
    newPasswordInput.addEventListener('input', validatePasswords);
    confirmPasswordInput.addEventListener('input', validatePasswords);

    // Handle password reset form submission
    resetPasswordBtn.addEventListener('click', function() {
        const newPassword = newPasswordInput.value.trim();
        const confirmPassword = confirmPasswordInput.value.trim();

        // If validation passes, show success message and redirect
        passwordMessage.innerText = 'Password reset successfully! Redirecting...';
        setTimeout(() => {
            window.location.href = 'trialpassword.html';  // Redirect to login page after success
        }, 1500);
    });
});
