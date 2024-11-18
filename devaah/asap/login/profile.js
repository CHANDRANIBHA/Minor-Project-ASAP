// Show/Hide Edit Button
function showEditButton() {
    document.getElementById('editImageButton').style.display = 'block';
}

function hideEditButton() {
    document.getElementById('editImageButton').style.display = 'none';
}

// Open/Close Modal
function openModal() {
    document.getElementById('profileModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('profileModal').style.display = 'none';
}

// Trigger file input for changing profile picture
function triggerFileInput() {
    document.getElementById('fileInput').click();
}

// Display selected profile picture and show the upload button
function changeProfilePicture(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePic').src = e.target.result;
        };
        reader.readAsDataURL(file);

        // Display the UPLOAD button after selecting the file
        document.getElementById('uploadButton').style.display = 'inline-block';
    }
}

// Redirect to the Teacher Dashboard
function goBack() {
    window.location.href = 'teacher_interface.php';
}
