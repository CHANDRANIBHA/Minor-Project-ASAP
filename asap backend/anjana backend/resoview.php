<?php
// Directory containing uploaded files
$uploadDir = __DIR__ . "/uploads";

// Fetch the list of files
$files = [];
if (is_dir($uploadDir)) {
    if ($handle = opendir($uploadDir)) {
        while (($file = readdir($handle)) !== false) {
            if ($file !== '.' && $file !== '..') {
                $files[] = $file;
            }
        }
        closedir($handle);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Viewer</title>
    <link rel="stylesheet" href="resoview.css">
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
    <div class="container">
        <header>
            <h1>Uploaded Files</h1>
        </header>
        <main>
            <?php if (!empty($files)): ?>
                <div class="file-list">
                    <?php foreach ($files as $file): ?>
                        <div class="file-card">
                            <p class="file-title"><?php echo htmlspecialchars(pathinfo($file, PATHINFO_FILENAME)); ?></p>
                            <a href="uploads/<?php echo urlencode($file); ?>" download class="btn-download">Download</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No files available in the uploads directory.</p>
            <?php endif; ?>
        </main>
    </div>
    <script src="resoview.js"></script>

</body>
</html>
