// Updated JavaScript in ifstud.js

function submitForm() {
    const batch = document.getElementById("batch").value.trim();
    const semester = parseInt(document.getElementById("semester").value);
    const yearOfJoining = parseInt(document.getElementById("year_of_joining").value);
    const passingOutYear = parseInt(document.getElementById("passing_out_year").value);
    const currentYear = new Date().getFullYear();
    let errorMessage = "";

    // Clear any previous error messages
    const errorBox = document.getElementById("error-message");
    errorBox.style.display = "none"; // Hide by default
    errorBox.innerHTML = ""; // Clear any previous messages

    // Batch validation
    if (!batch) {
        errorMessage += "Please enter the batch.<br>";
    }

    // Semester validation
    if (isNaN(semester) || semester < 1 || semester > 12) {
        errorMessage += "Semester must be between 1 and 12.<br>";
    }

    // Year of joining validation
    if (isNaN(yearOfJoining) || yearOfJoining > currentYear) {
        errorMessage += "Year of joining must be a valid past year.<br>";
    }

    

    // Display the error message box if there are any errors
    if (errorMessage) {
        errorBox.innerHTML = errorMessage;
        errorBox.style.display = "block"; // Show the message box
        return false; // Prevent form submission
    } else {
        console.log("Validation passed. Redirecting to login.");
        window.location.href = "login.html";
    }
}
