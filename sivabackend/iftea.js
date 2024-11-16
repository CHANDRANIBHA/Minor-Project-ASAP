// Form validation function
function validateForm() {
    // Get values from the form fields
    var userId = document.getElementById('user_id').value;
    var subject = document.getElementById('subject').value;
    var errorMessage = "";

    // Check if user_id is empty
    if (userId == "") {
        errorMessage += "User ID is required.\n";
    }

    // Check if subject is selected
    if (subject == "") {
        errorMessage += "Please select a subject.\n";
    }

    // If there are any error messages, show an alert and return false to prevent form submission
    if (errorMessage != "") {
        alert(errorMessage);
        return false;
    }

    // If no errors, return true to allow form submission
    return true;
}

// Optional: Add more validation for specific user_id format if needed
function isValidUserId(userId) {
    // Example: User ID should be numeric and between 1 and 999
    var regex = /^[0-9]{1,3}$/;
    return regex.test(userId);
}

// Optional: Event listener for user_id validation
document.getElementById('user_id').addEventListener('blur', function() {
    var userId = document.getElementById('user_id').value;
    if (!isValidUserId(userId)) {
        alert("Invalid User ID. Please enter a valid number between 1 and 999.");
        document.getElementById('user_id').focus();
    }
});
