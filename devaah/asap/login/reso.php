<?php
// Start session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "asap";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

// Retrieve the username and user_id from the session
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest'; // Default to 'Guest' if not set
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '12345678'; // Default to a placeholder if not set

$user_id = $_SESSION['user_id'];
$query = isset($_POST['query']) ? "%" . strtolower(trim($_POST['query'])) . "%" : '%';

// Fetch matching files from the database
$stmt = $conn->prepare("
    SELECT resource_title, resource_file 
    FROM resource_tbl 
    WHERE user_id = ? 
    AND (LOWER(resource_title) LIKE ? OR LOWER(resource_file) LIKE ?)
");
$stmt->bind_param("sss", $user_id, $query, $query);
$stmt->execute();
$result = $stmt->get_result();

$files = [];
while ($row = $result->fetch_assoc()) {
    $files[] = $row;
}

echo json_encode($files);

$stmt->close();

// Handle file upload
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


$allowedTitles = ['aptitude', 'verbal', 'softskills', 'professional training'];

// Max file size in bytes (e.g., 10MB)
$maxFileSize = 10 * 1024 * 1024;
$allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];


// Handle file deletion
// Handle file deletion
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete-resource"])){
    $resourceId = intval($_POST["resource-id"]);
    $fileName = $_POST["resource-file"];

    // Use __DIR__ and fix the file path (use forward slashes for portability)
    $filePath = __DIR__ . "/uploads/" . $fileName;
    if (file_exists($filePath)) {
        unlink($filePath);  // Delete the file
    }

    // Delete record from the database
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
        <!-- Left Sidebar -->
        <div class="sidebar" id="sidebar">
        <div class="profile">
                <!-- <a href="teaprofile.php"> -->
                <img src="profile-pic.png" alt="Profile Image" class="profile-img">
                <h3 id="user_name"><?php echo htmlspecialchars($user_name); ?></h3> <!-- Display dynamic username -->
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

        <button id="menuToggle" class="menu-icon">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
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
    <div class="container">
    <header>
    <h1>Resource Management</h1>
    <!-- Search bar
    <form>
        <input type="text" size="30" onkeyup="showResult(this.value)">
        <div id="livesearch"></div>
    </form> -->
</header>

        <main>

            <!-- Upload Section -->
            <div id="teacher-actions" class="upload-section">
                <form id="upload-form" method="POST" enctype="multipart/form-data">
                    <input type="text" name="file-title" class="text-input" placeholder="Enter file title" required>
                    <input type="file" name="file-upload" class="file-input" required>
                    <button type="submit" class="btn-upload">Upload</button>
                </form>
            </div>

            <div class="search-bar">
        <input type="text" id="search-bar" placeholder="Search by title or file name..." />
    </div>
            <!-- File List -->
            <div id="file-list" class="file-list">
        
   
    <?php
    // Fetch files
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = "SELECT * FROM resource_tbl WHERE user_id = ? ORDER BY time_stamp DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='file-card'>";
            echo "<h3>" . htmlspecialchars($row["resource_title"]) . "</h3>";
            echo "<p>File Name: " . htmlspecialchars($row["resource_file"]) . "</p>";
            echo "<p>Uploaded on: " . htmlspecialchars($row["time_stamp"]) . "</p>";
            echo "<a href='uploads/" . urlencode($row["resource_file"]) . "' download>Download</a>";
            echo "<form method='POST' style='display:inline;'>";
            echo "<input type='hidden' name='resource-id' value='" . htmlspecialchars($row["resource_id"]) . "'>";
            echo "<input type='hidden' name='resource-file' value='" . htmlspecialchars($row["resource_file"]) . "'>";
            echo "<button type='submit' name='delete-resource' class='btn-delete'>Delete</button>";
            echo "</form>";
            echo "</div>";
        }
    } else {
        echo "<p>No files available.</p>";
    }
    $stmt->close();
    ?>
</div>

        </main>
    </div>
</div>
<script src="reso.js"></script>
</body>
</html>
