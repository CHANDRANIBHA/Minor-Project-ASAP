<?php
// Start a session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "Admin";
$dbname = "asap"; // assuming 'asap' is your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['attachFile'])) {
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session after login
    $topic_id = $_POST['topic_id']; // Assume topic_id is passed through form
    $file = $_FILES['attachFile'];

    if ($file['error'] == 0) {
        $uploadDir = 'uploads/';
        $filePath = $uploadDir . basename($file['name']);

        // Move the uploaded file
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            // Insert into database
            $stmt = $conn->prepare("INSERT INTO resource_tbl (topic_id, user_id, resource_file) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $topic_id, $user_id, $filePath);
            $stmt->execute();
            $stmt->close();
            echo "File uploaded successfully.";
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "File error: " . $file['error'];
    }
}

// Fetch resources
$resources = [];
$sql = "SELECT * FROM resource_tbl WHERE topic_id = ?"; // assuming topic_id filters the resources for Verbal subject
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $topic_id);
$topic_id = 1; // Set your specific topic ID for Verbal, update as needed
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $resources[] = $row;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resource Page - Verbal</title>
    <link rel="stylesheet" href="resourcecss.css">
    <link rel="stylesheet" href="teacher.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar and other elements from your HTML here -->
        
        <!-- Main Content Area -->
        <div class="main-content">
            <h1>Verbal Resources</h1>
            
            <!-- Search Bar -->
            <input type="text" id="searchBar" placeholder="Search notes..." onkeyup="searchNotes()">

            <!-- Notes Section -->
            <div id="notes-section">
                <?php foreach ($resources as $resource): ?>
                    <div class="note-card" onclick="openFile('<?php echo $resource['resource_file']; ?>')">
                        <p class="note-title"><?php echo basename($resource['resource_file']); ?></p>
                        <span class="stylish-button" onclick="downloadFile('<?php echo $resource['resource_file']; ?>'); event.stopPropagation();">
                            <i class="fas fa-download"></i>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- File Upload Section (only for teachers) -->
            <?php if ($_SESSION['role'] == 'teacher'): ?>
                <div id="attach-section">
                    <form action="resoverbal.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="topic_id" value="1"> <!-- Set topic_id for Verbal resources -->
                        <input type="file" name="attachFile" id="attachFile" required>
                        <button type="submit">Attach File</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="resourcejs.js"></script>
</body>
</html>