<?php
// fetch_profile_picture.php
session_start();

// Database configuration
$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "asap";

// Check if user_id is set in session
if (!isset($_SESSION['user_id'])) {
    // Return the default image if the user is not logged in
    echo 'profile/logo.jpg'; // Ensure this path matches your directory structure
    exit;
}

$user_id = $_SESSION['user_id'];

// Create connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check database connection
if ($conn->connect_error) {
    echo 'profile/logo.jpg'; // Return default image in case of a connection error
    exit;
}

// Prepare and execute the SQL query
$sql = "SELECT profile_image FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $row = $result->fetch_assoc()) {
        // Use the profile image if set, or fallback to default
        $profilePicturePath = !empty($row['profile_image']) ? htmlspecialchars($row['profile_image']) : 'profile/logo.jpg';
    } else {
        // No results found, use the default image
        $profilePicturePath = 'profile/logo.jpg';
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Output the profile picture path
    echo $profilePicturePath;
} else {
    // Handle SQL statement preparation failure
    echo 'profile/logo.jpg'; // Default image on failure
    $conn->close();
    exit;
}
?>
