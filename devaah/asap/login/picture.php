<?php 
session_start();

$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "asap";

// Check if user is authenticated
if (!isset($_SESSION['user_id'])) {
    die("User not authenticated.");
}

$user_id = $_SESSION['user_id'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the directory for profile pictures
$target_dir = "profile/"; // Relative to the web app root
$uploadOk = 1;  

if (isset($_POST["submit"]) && isset($_FILES["profilePicture"])) {
    // Sanitize and set the target file path
    $original_filename = basename($_FILES["profilePicture"]["name"]);
    $sanitized_filename = preg_replace("/[^a-zA-Z0-9._-]/", "", $original_filename);
    $imageFileType = strtolower(pathinfo($sanitized_filename, PATHINFO_EXTENSION));

    // Check if the file is an actual image
    $check = getimagesize($_FILES["profilePicture"]["tmp_name"]);
    if ($check === false) {
        echo "File is not a valid image.";
        $uploadOk = 0;
    }

    // Check file size (limit to 500KB)
    if ($_FILES["profilePicture"]["size"] > 500000) {
        echo "File is too large. Maximum allowed size is 500KB.";
        $uploadOk = 0;
    }

    // Allow specific image file types
    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        echo "Only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // If no errors so far, try to upload the file
    if ($uploadOk == 1) {
        // Generate a unique filename to avoid conflicts
        $new_filename = "profile_" . $user_id . "." . $imageFileType;
        $target_file = $target_dir . $new_filename;

        // Delete existing profile picture if any
        if (file_exists($target_file)) {
            unlink($target_file);
        }

        // Move uploaded file to the target directory
        if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $target_file)) {
            // Update the user's profile image path in the database
            $stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE user_id = ?");
            $stmt->bind_param("ss", $target_file, $user_id);

            if ($stmt->execute()) {
                echo "Profile picture updated successfully.<br>";
                echo "Image path saved as: " . $target_file;
            } else {
                echo "Error updating profile picture in database: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error uploading your file. Please check file permissions for the directory and try again.";
        }
    }
} else {
    echo "Please select a profile picture to upload.";
}

// Close the database connection
$conn->close();
?>
