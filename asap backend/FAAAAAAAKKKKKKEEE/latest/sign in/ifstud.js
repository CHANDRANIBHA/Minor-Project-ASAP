function submitForm() {
    const classId = document.getElementById("class_id").value.trim(); // Class ID from the dropdown
    const semester = parseInt(document.getElementById("semester").value);
    const yearOfJoining = parseInt(document.getElementById("year_of_joining").value);
    const currentYear = new Date().getFullYear();
    let errorMessage = "";

    // Clear any previous error messages
    const errorBox = document.getElementById("error-message");
    errorBox.style.display = "none";
    errorBox.innerHTML = "";

    // Class selection validation
    if (!classId) {
        errorMessage += "Please select a class.<br>";
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
        errorBox.style.display = "block";
        return false; // Prevent form submission if there are errors
    }

    // Redirect to login page after validation passes
    window.location.href = "../login/login.php";
    return true;
}
