<?php
// Start session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "Rocky@2021";
$dbname = "asap";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

// Retrieve username and user ID
$user_name = $_SESSION['user_name'] ?? 'Guest';
$user_id = $_SESSION['user_id'] ?? '12345678';
$query = isset($_POST['query']) ? "%" . strtolower(trim($_POST['query'])) . "%" : '%';

// Handle file search
$stmt = $conn->prepare("
    SELECT resource_title, resource_file 
    FROM resource_tbl 
    WHERE user_id = ? 
    AND (LOWER(resource_title) LIKE LOWER(?) OR LOWER(resource_file) LIKE LOWER(?))
");
$stmt->bind_param("sss", $user_id, $query, $query);
$stmt->execute();
$result = $stmt->get_result();

// File upload handling
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["file-upload"])) {
    $uploadDir = __DIR__ . "/uploads/";
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileTitle = $conn->real_escape_string($_POST["file-title"]);
    $fileName = basename($_FILES["file-upload"]["name"]);
    $fileTmpName = $_FILES["file-upload"]["tmp_name"];

    if ($_FILES["file-upload"]["error"] === UPLOAD_ERR_OK) {
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($fileTmpName, $filePath)) {
            $stmt = $conn->prepare("INSERT INTO resource_tbl (user_id, resource_file, resource_title) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $user_id, $fileName, $fileTitle);

            if ($stmt->execute()) {
                echo "<p class='success'>File uploaded successfully!</p>";
            } else {
                echo "<p class='error'>Database error: " . $stmt->error . "</p>";
            }
            $stmt->close();
        } else {
            echo "<p class='error'>Failed to move uploaded file.</p>";
        }
    } else {
        echo "<p class='error'>File upload error: " . $_FILES["file-upload"]["error"] . "</p>";
    }
}

// File deletion handling
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete-resource"])) {
    $resourceId = intval($_POST["resource-id"]);
    $fileName = $_POST["resource-file"];
    $filePath = __DIR__ . "/uploads/" . $fileName;

    if (file_exists($filePath)) {
        unlink($filePath);
    }

    $stmt = $conn->prepare("DELETE FROM resource_tbl WHERE resource_id = ? AND user_id = ?");
    $stmt->bind_param("is", $resourceId, $user_id);

    if ($stmt->execute()) {
        echo "<p class='success'>Resource deleted successfully!</p>";
    } else {
        echo "<p class='error'>Failed to delete resource: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resource Management</title>
    <link rel="stylesheet" href="reso.css">
</head>
<body>
<div class="dashboard">
    <div class="sidebar" id="sidebar">
        <div class="profile">
            <img src="profile-pic.png" alt="Profile Image" class="profile-img">
            <h3 id="user_name"><?php echo htmlspecialchars($user_name); ?></h3>
            <p id="reg-number">Reg No: <?php echo htmlspecialchars($user_id); ?></p>
        </div>
        <div id="menu" class="menu">
            <ul>
                <li onclick="navigateTo('teacher_interface.php')">Home</li>
                <li onclick="navigateTo('reso.php')">Resource</li>
                <li onclick="navigateTo('chat.php')">Chat</li>
                <li onclick="navigateTo('sessionform.html')">Session</li>
                <li onclick="navigateTo('feedback.php')">Feedback</li>
                <li onclick="navigateTo('logout.php')">Logout</li>
            </ul>
        </div>
    </div>
    <div class="main-content">
        <header>
            <h1>Resource Management</h1>
        </header>
        <main>
            <div id="teacher-actions" class="upload-section">
                <form id="upload-form" method="POST" enctype="multipart/form-data">
                    <input type="text" name="file-title" class="text-input" placeholder="Enter file title" required>
                    <input type="file" name="file-upload" class="file-input" required>
                    <button type="submit" class="btn-upload">Upload</button>
                </form>
            </div>
            <div class="search-bar">
                <input type="text" id="search-bar" placeholder="Search by title or file name...">
            </div>
            <div id="file-list" class="file-list">
                <?php include 'file_list_fetch.php'; ?>
            </div>
        </main>
    </div>
</div>
<script src="reso.js"></script>
</body>
</html>