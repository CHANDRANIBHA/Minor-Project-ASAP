<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<h1 style='color: red;'>User ID is required to view the profile.</h1>";
    echo "<a href='teacher_interface.php' style='font-size: 20px; text-decoration: none; color: blue;'>Go back to Teacher Dashboard</a>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Database configuration
$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "asap";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch teacher data from users table
$sql = "SELECT user_name, email_id, role, profile_image FROM users WHERE user_id = ? AND role = 'teacher'";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userName = htmlspecialchars($user['user_name']);
        $emailId = htmlspecialchars($user['email_id']);
        $role = htmlspecialchars($user['role']);
        $profileImage = !empty($user['profile_image']) 
                        ? 'C:/Users/USER/Desktop/ASAP-FILES/Minor-Project-ASAP/devaah/asap/login/profile/' . htmlspecialchars($user['profile_image']) 
                        : 'C:/Users/USER/Desktop/ASAP-FILES/Minor-Project-ASAP/devaah/asap/login/profile/logo.jpg';
    } else {
        echo "No teacher found with the given user ID.";
        $stmt->close();
        $conn->close();
        exit;
    }
    $stmt->close();
} else {
    echo "Query preparation failed: " . $conn->error;
    $conn->close();
    exit;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="profile.css">
    <title>Teacher Profile</title>
</head>
<body>
    <button onclick="history.back()" class="back-button">&lt;&lt;</button>

    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-picture" onmouseover="showEditButton()" onmouseout="hideEditButton()">
                <img src="<?php echo $profileImage; ?>" alt="Profile Picture" class="profile-pic" id="profilePic">
                <button id="editImageButton" onclick="openModal()">EDIT</button>
            </div>

            <div id="profileModal" style="display: none;">
                <div class="modal-content">
                    <span onclick="closeModal()" class="close">&times;</span>
                    <h2>Edit Profile Picture</h2>
                    <form id="uploadForm" action="picture.php" method="post" enctype="multipart/form-data">
                        <input type="file" id="fileInput" name="profilePicture" onchange="changeProfilePicture(event)">
                        <button type="submit" name="submit">Upload</button>
                    </form>
                </div>
            </div>

            <h2 class="Teacher-name"><?php echo $userName; ?></h2>
        </div>
        <div class="profile-details">
            <p><strong>Email:</strong> <?php echo $emailId; ?></p>
            <p><strong>Role:</strong> <?php echo $role; ?></p>
        </div>
    </div>

    <script src="profile.js" defer></script>
</body>
</html>
