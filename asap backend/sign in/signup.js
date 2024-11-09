// Regular expression patterns
const usernamePattern = /^[A-Za-z]+$/;
const registrationPatternStudent = /^KH\.EN\.U\d{1}[A-Z]{3}\d{5}$/i; // Student format
const registrationPatternTeacher = /^TH\.EN\.T\d{1}[A-Z]{3}\d{5}$/i; // Teacher format
const registrationPatternAdmin = /^AD\.EN\.A\d{1}[A-Z]{3}\d{5}$/i;   // Admin format
const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

document.addEventListener("DOMContentLoaded", function () {
    // Get form elements
    const usernameInput = document.getElementById('username');
    const registrationNumberInput = document.getElementById('user_id'); // Use 'user_id' instead of 'registration-number'
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const roleSelect = document.getElementById('role');

    // Add event listeners to form elements
    usernameInput.addEventListener('input', validateUsername);
    usernameInput.addEventListener('blur', validateUsername);
    registrationNumberInput.addEventListener('input', validateRegistrationNumber);
    registrationNumberInput.addEventListener('blur', validateRegistrationNumber);
    emailInput.addEventListener('input', validateEmail);
    emailInput.addEventListener('blur', validateEmail);
    passwordInput.addEventListener('input', validatePassword);
    passwordInput.addEventListener('blur', validatePassword);
    roleSelect.addEventListener('change', validateRoleSelection);
});

// Validate username
function validateUsername() {
    const usernameInput = document.getElementById('username');
    const username = usernameInput.value.trim();
    const usernameError = document.getElementById('usernameError');

    if (!username) {
        usernameInput.style.borderColor = 'red';
        usernameError.innerText = 'Username is required.';
    } else if (!usernamePattern.test(username)) {
        usernameInput.style.borderColor = 'red';
        usernameError.innerText = 'Username must only contain alphabets.';
    } else {
        usernameInput.style.borderColor = '';
        usernameError.innerText = '';
    }
}

// Validate role selection
function validateRoleSelection() {
    const roleSelect = document.getElementById('role');
    const roleError = document.getElementById('roleError');
    const role = roleSelect.value;

    if (!role) {
        roleSelect.style.borderColor = 'red';
        roleError.innerText = 'Please select a role.';
    } else {
        roleSelect.style.borderColor = '';
        roleError.innerText = '';
    }

    validateRegistrationNumber();
}

// Validate registration number based on selected role
function validateRegistrationNumber() {
    const registrationNumberInput = document.getElementById('user_id'); // Update to 'user_id'
    const registrationNumber = registrationNumberInput.value.trim();
    const registrationError = document.getElementById('registrationError');
    const roleSelect = document.getElementById('role');
    const role = roleSelect.value;

    registrationError.innerText = '';
    registrationNumberInput.style.borderColor = '';

    let isRegistrationValid = false;
    if (!role) {
        registrationError.innerText = 'Please select a role first.';
        roleSelect.style.borderColor = 'red';
        return;
    }

    switch (role) {
        case 'student':
            isRegistrationValid = registrationPatternStudent.test(registrationNumber);
            break;
        case 'teacher':
            isRegistrationValid = registrationPatternTeacher.test(registrationNumber);
            break;
        case 'admin':
            isRegistrationValid = registrationPatternAdmin.test(registrationNumber);
            break;
        default:
            registrationError.innerText = 'Please select a valid role.';
    }

    if (!isRegistrationValid) {
        registrationNumberInput.style.borderColor = 'red';
        registrationError.innerText = `Invalid format for ${role}.`;
    } else {
        registrationNumberInput.style.borderColor = '';
        registrationError.innerText = '';
    }
}

// Validate email
function validateEmail() {
    const emailInput = document.getElementById('email');
    const email = emailInput.value.trim();
    const emailError = document.getElementById('emailError');

    if (!emailPattern.test(email)) {
        emailInput.style.borderColor = 'red';
        emailError.innerText = 'Please enter a valid email address.';
    } else {
        emailInput.style.borderColor = '';
        emailError.innerText = '';
    }
}

// Validate password
function validatePassword() {
    const passwordInput = document.getElementById('password');
    const password = passwordInput.value.trim();
    const passwordError = document.getElementById('passwordError');

    if (!passwordPattern.test(password)) {
        passwordInput.style.borderColor = 'red';
        passwordError.innerText = 'Password must have at least 8 characters, including upper, lower, number, and special character.';
    } else {
        passwordInput.style.borderColor = '';
        passwordError.innerText = '';
    }
}
