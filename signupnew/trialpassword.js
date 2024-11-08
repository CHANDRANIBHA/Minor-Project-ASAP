// Show modal when 'Forgot Password?' is clicked
document.getElementById('forgot-password-link').addEventListener('click', function() {
    document.getElementById('modal').style.display = 'block';
});

// Close modal when 'x' is clicked
document.querySelector('.close').addEventListener('click', function() {
    document.getElementById('modal').style.display = 'none';
});

// Handle sending reset link
document.getElementById('sendResetLink').addEventListener('click', function() {
    const email = document.getElementById('email').value.trim();
    const registrationNumber = document.getElementById('registration-number').value.trim();

    // Validate email and registration number
    if (validateEmail(email) && validateRegistrationNumber(registrationNumber)) {
        // Simulate sending the reset email
        document.getElementById('message').innerText = 'Reset link sent! Check your email.';
        setTimeout(() => {
            window.location.href = 'new_password.html';  // Redirect to password reset page
        }, 1500);  // Delay to simulate email sending
    } else {
        document.getElementById('message').innerText = 'Please enter a valid email and registration number.';
    }
});

// Email validation function
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
}

// Registration number validation function
function validateRegistrationNumber(registrationNumber) {
    const regexStudent = /^KH\.EN\.U\d{1}[A-Z]{3}\d{5}$/i;// Format for students
    const regexTeacher = /^TH\.EN\.T\d{1}[A-Z]{3}\d{5}$/i; // Format for teachers
    const regexAdmin = /^AD\.EN\.A\d{1}[A-Z]{3}\d{5}$/i; // Format for admins

    // You can add your logic to check the role here if needed
    const role = document.getElementById('role').value; // Assuming you have a dropdown for role selection

    if (role === 'student') {
        return regexStudent.test(registrationNumber);
    } else if (role === 'teacher') {
        return regexTeacher.test(registrationNumber);
    } else if (role === 'admin') {
        return regexAdmin.test(registrationNumber);
    }
    return false; // Return false if the role is not recognized
}
