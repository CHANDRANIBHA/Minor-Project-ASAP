document.addEventListener("DOMContentLoaded", function () {
    const usernameInput = document.getElementById("username");
    const userIdInput = document.getElementById("user_id");
    const emailInput = document.getElementById("email");
    const roleInput = document.getElementById("role");
    const passwordInput = document.getElementById("password");
    const confirmPasswordInput = document.getElementById("confirm_password");

    const usernameError = document.getElementById("usernameError");
    const userIdError = document.getElementById("registrationError");
    const emailError = document.getElementById("emailError");
    const passwordError = document.getElementById("passwordError");
    const confirmPasswordError = document.getElementById("confirmPasswordError");
    
    // Initialize email format update for pre-selected role
    updateEmailFormat();

    // Event listeners for role change to update email placeholder
    roleInput.addEventListener("change", updateEmailFormat);

    // Convert username and userId input to uppercase and validate on input
    usernameInput.addEventListener("input", () => {
        const value = usernameInput.value.toUpperCase().replace(/[^A-Z ]/g, "");
        usernameInput.value = value;
        validateUsername(value);
    });

    userIdInput.addEventListener("input", () => {
        const value = userIdInput.value.toUpperCase();
        userIdInput.value = value;
        validateUserId(value);
    });

    passwordInput.addEventListener("input", () => {
        const value = passwordInput.value;
        validatePassword(value);
    });

    confirmPasswordInput.addEventListener("input", () => {
        const value = confirmPasswordInput.value;
        validateConfirmPassword(value);
    });

    emailInput.addEventListener("input", () => {
        const value = emailInput.value;
        validateEmail(value);
    });

    // Update email placeholder based on role
    function updateEmailFormat() {
        const role = roleInput.value;
        if (role === "student") {
            emailInput.placeholder = "e.g., USER_ID@kh.students.amrita.edu";
        } else if (role === "teacher") {
            emailInput.placeholder = "e.g., USER_ID@th.teacher.amrita.edu";
        }
    }

    // Validation Functions
    function validateUsername(value) {
        if (!/^[A-Za-z ]*$/.test(value)) {
            usernameError.textContent = "Username can contain only letters and spaces.";
        } else {
            usernameError.textContent = "";
        }
    }

    function validateUserId(value) {
    const role = roleInput.value; // Get the current selected role

    // For Student (KH.EN)
    if (role === "student" && !/^KH\.EN/i.test(value)) {
        userIdError.textContent = "User ID must start with 'KH.EN' for students.";
    }
    // For Teacher (TH.EN)
    else if (role === "teacher" && !/^TH\.EN/i.test(value)) {
        userIdError.textContent = "User ID must start with 'TH.EN' for teachers.";
    }
    // Clear error if user ID is valid for the respective role
    else {
        userIdError.textContent = "";
    }
}


    function validateEmail(value) {
        const userId = userIdInput.value;
        if (roleInput.value === "student" && !new RegExp(`^${userId}@kh\\.students\\.amrita\\.edu$`, 'i').test(value)) {
            emailError.textContent = "Email should be in the format 'USER_ID@kh.students.amrita.edu' for students.";
        } else if (roleInput.value === "teacher" && !new RegExp(`^${userId}@th\\.teacher\\.amrita\\.edu$`, 'i').test(value)) {
            emailError.textContent = "Email should be in the format 'USER_ID@th.teacher.amrita.edu' for teachers.";
        } else {
            emailError.textContent = "";
        }
    }

    function validatePassword(value) {
        if (!/^(?=.*[A-Z])(?=.*\W).{8,}$/.test(value)) {
            passwordError.textContent = "Password must be 8+ chars with 1 uppercase and 1 special char.";
        } else {
            passwordError.textContent = "";
        }
    }

    function validateConfirmPassword(value) {
        if (value !== passwordInput.value) {
            confirmPasswordError.textContent = "Passwords do not match.";
        } else {
            confirmPasswordError.textContent = "";
        }
    }

    // Form submission validation
    document.querySelector("form").addEventListener("submit", function (e) {
        // Validate fields before submitting
        validateUsername(usernameInput.value);
        validateUserId(userIdInput.value);
        validateEmail(emailInput.value);
        validatePassword(passwordInput.value);
        validateConfirmPassword(confirmPasswordInput.value);

        // Check if any error messages exist, if so, prevent form submission
        const errorMessages = [usernameError, userIdError, emailError, passwordError, confirmPasswordError];
        const hasErrors = errorMessages.some(error => error.textContent.trim() !== "");
        if (hasErrors) {
            e.preventDefault();
            alert("Please fix the errors before submitting.");
        } 
    });
});
