// Regular expression patterns
const usernamePattern = /^[A-Za-z]+$/;
const registrationPatternStudent = /^KH\.EN\.U\d{1}[A-Z]{3}\d{5}$/i; // Student format
const registrationPatternTeacher = /^TH\.EN\.T\d{1}[A-Z]{3}\d{5}$/i; // Teacher format
const registrationPatternAdmin = /^AD\.EN\.A\d{1}[A-Z]{3}\d{5}$/i;   // Admin format
const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

// DOM elements
const usernameInput = document.getElementById('username');
const registrationNumberInput = document.getElementById('registration-number');
const emailInput = document.getElementById('email');
const passwordInput = document.getElementById('password');
const roleSelect = document.getElementById('role');
const messageContainer = document.getElementById('messageContainer');
const signupBtn = document.getElementById('signupBtn');
const passwordToggleBtn = document.getElementById('passwordToggleBtn');

// Initialize the application
document.addEventListener("DOMContentLoaded", function () {
    // Add event listeners to form elements
    usernameInput.addEventListener('input', validateUsername);
    registrationNumberInput.addEventListener('input', validateRegistrationNumber);
    emailInput.addEventListener('input', validateEmail);
    passwordInput.addEventListener('input', validatePassword);
    roleSelect.addEventListener('change', validateRoleSelection);
    
    signupBtn.addEventListener('click', handleSignup);
    
   
});

// Handle the signup button click
function handleSignup(event) {
    event.preventDefault(); // Prevent default form submission
    validateForm();

    // Check if the form is valid
    if (isFormValid()) {
        // Hide the signup form
        document.querySelector('.signup-container').style.display = 'none';

        // Create a success message
        messageContainer.innerHTML = ''; // Clear any existing content
        messageContainer.style.display = 'flex';

        const successMessage = document.createElement('p');
        successMessage.innerText = 'Signup successful! Please login below.';
        successMessage.style.fontSize = '20px';
        successMessage.style.marginBottom = '20px';

        const loginButton = document.createElement('button');
        loginButton.innerText = 'Login';
        loginButton.style.padding = '10px 10px';
        loginButton.style.fontSize = '16px';
        loginButton.style.cursor = 'pointer';
        loginButton.addEventListener('click', function () {
            // Redirect to login page or any other action
            window.location.href = 'trialpassword.html'; // Replace with your login page URL
        });

        // Add the success message and login button to the message container
        messageContainer.appendChild(successMessage);
        messageContainer.appendChild(loginButton);
    }
}

// Validation functions
function validateUsername() {
    const username = usernameInput.value.trim();
    const usernameError = document.getElementById('usernameError');

    if (username.length === 0) {
        usernameInput.style.borderColor = 'red';
        usernameError.innerText = 'Username is required.';
    } else if (!usernamePattern.test(username)) {
        usernameInput.style.borderColor = 'red';
        usernameError.innerText = 'Username must only contain alphabets.';
    } else {
        usernameInput.style.borderColor = '';
        usernameError.innerText = ''; // Clear error message
    }
}

function validateRoleSelection() {
    const roleError = document.getElementById('roleError');
    const role = roleSelect.value;

    if (!role) {
        roleSelect.style.borderColor = 'red';
        roleError.innerText = 'Please select a role.';
    } else {
        roleSelect.style.borderColor = '';
        roleError.innerText = ''; // Clear error message
    }

    validateRegistrationNumber(); // Revalidate registration number based on selected role
}

function validateRegistrationNumber() {
    const registrationNumber = registrationNumberInput.value.trim();
    const registrationError = document.getElementById('registrationError');
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
            if (!isRegistrationValid) {
                registrationNumberInput.style.borderColor = 'red';
                registrationError.innerText = 'Invalid format. Must be like KH.EN.U###abcd12345.';
            }
            break;
        case 'teacher':
            isRegistrationValid = registrationPatternTeacher.test(registrationNumber);
            if (!isRegistrationValid) {
                registrationNumberInput.style.borderColor = 'red';
                registrationError.innerText = 'Invalid format. Must be like TH.EN.T###abcd12345.';
            }
            break;
        case 'admin':
            isRegistrationValid = registrationPatternAdmin.test(registrationNumber);
            if (!isRegistrationValid) {
                registrationNumberInput.style.borderColor = 'red';
                registrationError.innerText = 'Invalid format. Must be like AD.EN.A###abcd12345.';
            }
            break;
        default:
            registrationNumberInput.style.borderColor = 'red';
            registrationError.innerText = 'Please select a valid role.';
            break;
    }

    if (isRegistrationValid) {
        registrationNumberInput.style.borderColor = '';
        registrationError.innerText = ''; // Clear error message
    }
}

function validateEmail() {
    const email = emailInput.value.trim();
    const emailError = document.getElementById('emailError');

    if (!emailPattern.test(email)) {
        emailInput.style.borderColor = 'red';
        emailError.innerText = 'Please enter a valid email address.';
    } else {
        emailInput.style.borderColor = '';
        emailError.innerText = ''; // Clear error message
    }
}

function validatePassword() {
    const password = passwordInput.value.trim();
    const passwordError = document.getElementById('passwordError');

    if (!passwordPattern.test(password)) {
        passwordInput.style.borderColor = 'red';
        passwordError.innerText = 'Password must have at least 8 characters, including upper, lower, number, and special character.';
    } else {
        passwordInput.style.borderColor = '';
        passwordError.innerText = ''; // Clear error message
    }
}

function validateForm() {
    validateUsername();
    validateRoleSelection();
    validateRegistrationNumber();
    validateEmail();
    validatePassword();
}

function isFormValid() {
    const roleError = document.getElementById('roleError').innerText;
    const registrationError = document.getElementById('registrationError').innerText;
    const emailError = document.getElementById('emailError').innerText;
    const passwordError = document.getElementById('passwordError').innerText;

    return !(roleError || registrationError || emailError || passwordError);
}
