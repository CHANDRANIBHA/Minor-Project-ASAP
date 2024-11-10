// Regular expression patterns
// const usernamePattern = /^[A-Za-z]+$/;
const usernamePattern = /^[A-Za-z]+( [A-Za-z]+)?$/;
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

// Validate role selections
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

// Updated validateEmail function
function validateEmail() {
    const emailInput = document.getElementById('email');
    const email = emailInput.value.trim();
    const emailError = document.getElementById('emailError');
    const roleSelect = document.getElementById('role');
    const role = roleSelect.value;

    // Reset error messages and border color
    emailInput.style.borderColor = '';
    emailError.innerText = '';

    let isEmailValid = emailPattern.test(email);

    // Check the email prefix based on the role
    if (isEmailValid) {
        if (role === 'student' && !email.toLowerCase().startsWith('kh')) {
            isEmailValid = false;
            emailError.innerText = 'Student email should start with "kh".';
        } else if (role === 'teacher' && !email.toLowerCase().startsWith('th')) {
            isEmailValid = false;
            emailError.innerText = 'Teacher email should start with "th".';
        } 
    } else {
        emailError.innerText = 'Please enter a valid email address.';
    }

    // Apply border color based on validity
    if (!isEmailValid) {
        emailInput.style.borderColor = 'red';
    }
}


// Validate password
function validatePassword() {
    const passwordInput = document.getElementById('password');
    const password = passwordInput.value.trim();
    const passwordError = document.getElementById('passwordError');
    console.log("hi");

    if (!passwordPattern.test(password)) {
        passwordInput.style.borderColor = 'red';
        passwordError.innerText = 'Password must have at least 8 characters, including upper, lower, number, and special character.';
    } else {
        passwordInput.style.borderColor = '';
        passwordError.innerText = '';
    }
}

function showModal(message) {
    const modal = document.getElementById("customAlert");
    const alertMessage = document.getElementById("alertMessage");
    alertMessage.innerText = message;
    modal.style.display = "block";

    // Close the modal when the user clicks the close button
    document.querySelector(".close-btn").onclick = function() {
        modal.style.display = "none";
    };

    // Close the modal when the user clicks outside the modal content
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
}

// Call the function where you would normally use `alert()`
showModal("Signup successful! Redirecting to login page.");

