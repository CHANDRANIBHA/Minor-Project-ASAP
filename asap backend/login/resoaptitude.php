<?php
include "C:/xampp/htdocs/db.php"; // Update the path as needed

session_start();

// Retrieve the username and user_id from the session
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest'; // Default to 'Guest' if not set
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '12345678'; // Default to a placeholder if not set

// Logout function (redirect to login page after session destruction)
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: home.php");
    exit();
}

// Display the username and registration number if set
if (isset($_SESSION['username'])) {
    echo $_SESSION['username'];
} else {
    echo "Username not set";
}

if (isset($_SESSION['user_id'])) {
    echo $_SESSION['user_id'];
} else {
    echo "Registration number not set";
}

header('Content-Type: application/json');
include "C:/xampp/htdocs/db.php"; // Include database connection

// Retrieve resource files from the database
$query = "SELECT * FROM resource_tbl"; // Query to get resources
$result = $conn->query($query);

$resources = [];
while ($row = $result->fetch_assoc()) {
    $resources[] = [
        'title' => $row['resource_file'], // Resource file (assuming it's stored in 'resource_file')
        'fileUrl' => $row['resource_file'] // Assuming the file path is stored in 'resource_file'
    ];
}

echo json_encode($resources);

// Handle file upload (for teachers)
if ($_FILES['file']) {
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $uploadDir = 'uploads/'; // Directory to store uploaded files
    $fileUrl = $uploadDir . basename($fileName);

    // Move the uploaded file to the upload directory
    if (move_uploaded_file($fileTmpName, $fileUrl)) {
        // Insert file information into the database with the topic_id and user_id
        $stmt = $conn->prepare("INSERT INTO resource_tbl (topic_id, user_id, resource_file) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $topic_id, $user_id, $fileUrl); // Adjust based on the data types in the table

        // You may need to get $topic_id dynamically based on the user's input or session
        $topic_id = 1; // Example topic ID (this should be retrieved from your form input or session)

        $stmt->execute();

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'File upload failed']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No file uploaded']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resource Page</title>
    <link rel="stylesheet" href="resourcecss.css">
    <link rel="stylesheet" href="teacher.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome for icons -->
</head>
<body>
    <div class="dashboard">
        <!-- Left Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="profile">
                <img src="profilepic.jpeg" alt="Profile Image" class="profile-img">
                <h3 id="username"><?php echo htmlspecialchars($_SESSION['username']); ?></h3>
                <p id="reg-number">Reg No: <?php echo htmlspecialchars($user_id); ?></p>
            </div>
            <div id="menu" class="menu">
                <ul>
                    <li onclick="navigateTo('teacher_interface.php')">Home</li>
                    <li>
                        <div class="dropdown">
                            <span onclick="toggleDropdown()">Resources</span>
                            <div class="dropdown-content" id="resources-dropdown">
                                <a href="#" onclick="navigateTo('resoaptitude.php')">Aptitude</a>
                                <a href="#" onclick="navigateTo('resoverbal.php')">Verbal</a>
                                <a href="#" onclick="navigateTo('resosoftskills.php')">Soft Skills</a>
                                <a href="#" onclick="navigateTo('resotraining.php')">Professional Training</a>
                            </div>
                        </div>
                    </li>
                    <li onclick="navigateTo('chattr.php')">Chat</li>
                    <li onclick="navigateTo('sessionform.php')">Session</li>
                    <li onclick="navigateTo('history.php')">My History</li>
                    <li>
                        <form method="post" style="display:inline;">
                            <button type="submit" name="logout" class="logout-button">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Menu Toggle Button -->
        <button id="menuToggle" class="menu-icon">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Main Content Area -->
        <div class="main-content">
            <span class="back-arrow" onclick="goBack()">&#8592; </span>

            <!-- Top Bar -->
            <div class="top-bar">
                <div class="search-container">
                    <input type="text" placeholder="Search...">
                    <i class="fas fa-search"></i>
                </div>
                <div class="top-icons">
                    <div class="notification" id="notification">
                        <i class="fas fa-bell"></i>
                        <div class="notification-dropdown" id="notificationDropdown">
                            <ul>
                                <li>No new notifications</li>
                            </ul>
                        </div>
                    </div>
                    <div class="chat" id="chat">
                        <i class="fas fa-comments"></i>
                    </div>
                </div>
            </div>
        
        <!-- Resource Container -->
        <div class="resource-container">
            <h1 style="text-align: left; margin-bottom: 20px;font-family: 'Roboto', sans-serif;">APTITUDE</h1>
            <!-- Search Bar -->
            <input type="text" id="searchBar" placeholder="Search notes..." onkeyup="searchNotes()">

            <!-- Notes Section -->
            <div id="notes-section">
              <!-- Notes will be dynamically inserted here -->
            </div>

            <!-- Attach File Button (only for teachers) -->
            <div id="attach-section">
                <input type="file" id="attachFile" style="display:none" onchange="uploadFile()">
                <button id="attachButton" onclick="document.getElementById('attachFile').click()">Attach File</button>
            </div>
            
            <div id="notes-section">
                <!-- Notes will be dynamically inserted here -->
            </div>
            
        </div>
    </div>

  <script src="resourcejs.js"></script>
  <script src="teacher.js"></script>
</body>
</html>