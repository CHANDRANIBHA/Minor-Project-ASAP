// Set the minimum date for the datetime-local input
function setMinDateTime() {
    const sessionDateTime = document.getElementById('sessionDateTime');
    const now = new Date();

    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');

    sessionDateTime.min = `${year}-${month}-${day}T${hours}:${minutes}`;
}
window.onload = setMinDateTime;

// Validate the form
function validateForm() {
    const activity = document.getElementById('activity').value;
    const semester = document.getElementById('semester').value;
    const classes = Array.from(document.querySelectorAll('input[name="class"]:checked')).map(el => el.value).join(', ');
    const numStudents = document.getElementById('numStudents').value;
    const sessionDateTime = document.getElementById('sessionDateTime').value;
    // const sessionLink = document.getElementById('sessionLink').value;

    let errorMessage = '';

    if (!activity) errorMessage += 'Please select an activity.<br>';
    if (!semester) errorMessage += 'Please select a semester.<br>';
    if (!classes) errorMessage += 'Please select at least one class.<br>';
    if (!numStudents) {
        errorMessage += "<p>Please enter the number of students.</p>";
    } else if (parseInt(numStudents) > 50) {
        // Check if the number of students is more than 50
        errorMessage += "<p>Number of students cannot exceed 50.</p>";
    }
     // Validate session date and time to ensure it’s not in the past
     if (!sessionDateTime) {
        errorMessage += "<p>Please select a session date and time.</p>";
    } else {
        const selectedDate = new Date(sessionDateTime);
        const currentDate = new Date();
        if (selectedDate < currentDate) {
            errorMessage += "<p>Session date and time cannot be in the past.</p>";
        }
    }    if (!sessionLink) errorMessage += 'Please enter a valid session link.<br>';

    // Display error messages if validation fails
    if (errorMessage) {
        document.getElementById('error-message').innerHTML = errorMessage;
        return false; // Form is not valid
    } else {
        document.getElementById('error-message').innerHTML = ""; // Clear errors if validation passes
        return true; // Form is valid
    }
}

// Show the confirmation popup
function showConfirmation() {
    if (validateForm()) {
        const activity = document.getElementById('activity').value;
        const semester = document.getElementById('semester').value;
        const classes = Array.from(document.querySelectorAll('input[name="class"]:checked')).map(el => el.value).join(', ');
        const numStudents = document.getElementById('numStudents').value;
        const sessionDateTime = document.getElementById('sessionDateTime').value;
        const sessionLink = document.getElementById('sessionLink').value;
        const description = document.getElementById('description').value || 'N/A';

        document.getElementById('confirmation-details').innerHTML = `
            <table style="width: 100%; border-collapse: collapse;">
            <tr><td><strong>Activity:</strong></td><td>${activity}</td></tr>
            <tr><td><strong>Semester:</strong></td><td>${semester}</td></tr>
            <tr><td><strong>Classes:</strong></td><td>${classes}</td></tr>
            <tr><td><strong>Number of Students:</strong></td><td>${numStudents}</td></tr>
            <tr><td><strong>Session Date and Time:</strong></td><td>${sessionDateTime}</td></tr>
            <tr><td><strong>Session Link:</strong></td><td>${sessionLink}</td></tr>
            <tr><td><strong>Description:</strong></td><td>${description}</td></tr>
            </table>
        `;

        document.getElementById('confirmation-popup').style.display = 'block';
}
}