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
    const registrationNumberInput = document.getElementById('registration-number');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const roleSelect = document.getElementById('role');
    const messageContainer = document.getElementById('messageContainer'); // Message display area

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

    if (username.length === 0) {
        usernameInput.style.borderColor = 'red';
        usernameError.innerText = 'Username is required.';
        usernameError.classList.add("error");
    } else if (!usernamePattern.test(username)) {
        usernameInput.style.borderColor = 'red';
        usernameError.innerText = 'Username must only contain alphabets.';
        usernameError.classList.add("error");
    } else {
        usernameInput.style.borderColor = '';
        usernameError.innerText = ''; // Clear error message
        usernameError.classList.remove("error");
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
        roleError.classList.add("error");
    } else {
        roleSelect.style.borderColor = '';
        roleError.innerText = ''; // Clear error message
        roleError.classList.remove("error");
    }

    // Trigger registration number validation if role changes
    validateRegistrationNumber();
}

// Validate registration number based on selected role
function validateRegistrationNumber() {
    const registrationNumberInput = document.getElementById('registration-number');
    const registrationNumber = registrationNumberInput.value.trim();
    const registrationError = document.getElementById('registrationError');
    const roleSelect = document.getElementById('role');
    const role = roleSelect.value;

    // Clear previous error messages and reset borders
    registrationError.innerText = '';
    registrationNumberInput.style.borderColor = '';

    // Validate registration number based on role
    let isRegistrationValid = false;
    if (!role) {
        registrationError.innerText = 'Please select a role first.';
        roleSelect.style.borderColor = 'red';
        return;
    }

    // Role-based registration validation
    switch (role) {
        case 'student':
            isRegistrationValid = registrationPatternStudent.test(registrationNumber);
            if (!isRegistrationValid) {
                registrationNumberInput.style.borderColor = 'red';
                registrationError.innerText = 'Invalid format. Must be like KH.EN.U###abcd12345.';
                registrationError.classList.add("error");
            }
            break;
        case 'teacher':
            isRegistrationValid = registrationPatternTeacher.test(registrationNumber);
            if (!isRegistrationValid) {
                registrationNumberInput.style.borderColor = 'red';
                registrationError.innerText = 'Invalid format. Must be like TH.EN.T###abcd12345.';
                registrationError.classList.add("error");
            }
            break;
        case 'admin':
            isRegistrationValid = registrationPatternAdmin.test(registrationNumber);
            if (!isRegistrationValid) {
                registrationNumberInput.style.borderColor = 'red';
                registrationError.innerText = 'Invalid format. Must be like AD.EN.A###abcd12345.';
                registrationError.classList.add("error");
            }
            break;
        default:
            registrationNumberInput.style.borderColor = 'red';
            registrationError.innerText = 'Please select a valid role.';
            registrationError.classList.add("error");
            break;
    }

    // Clear error message if the registration number is valid
    if (isRegistrationValid) {
        registrationNumberInput.style.borderColor = '';
        registrationError.innerText = ''; // Clear error message
        registrationError.classList.remove("error");
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
        emailError.classList.add("error");
    } else {
        emailInput.style.borderColor = '';
        emailError.innerText = ''; // Clear error message
        emailError.classList.remove("error");
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
        passwordError.classList.add("error");
    } else {
        passwordInput.style.borderColor = '';
        passwordError.innerText = ''; // Clear error message
        passwordError.classList.remove("error");
    }
}

// Validate form on submit
document.getElementById('signupBtn').addEventListener('click', function (event) {
   

    

    // If all validations pass, show success message and login button
    const messageContainer = document.getElementById('messageContainer');
    messageContainer.innerHTML = `
        <h3>Signed up successfully!</h3>
        <p>Please log in to continue.</p>
        <button id="loginBtn">Login</button>
    `;

    // Add click event for login button
    document.getElementById('loginBtn').addEventListener('click', function () {
        window.location.href = 'login.html'; // Replace with your actual login page
    });


});
