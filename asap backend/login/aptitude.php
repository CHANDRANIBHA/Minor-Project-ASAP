
<?php
session_start(); // Start the session to access session variables

// Include database connection
require_once __DIR__ . '/../db.php';

// Function to fetch classes based on the semester from the database
function getClassesForSemester($semester) {
    global $conn; // Use the database connection from db.php

    $classes = [];
    $stmt = $conn->prepare("SELECT class_name FROM class_tbl WHERE semester = ?");
    $stmt->bind_param("i", $semester);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $classes[] = ['name' => $row['class_name']];
    }

    $stmt->close();
    return $classes;
}

// Get the selected semester from the URL parameter
$semester = isset($_GET['semester']) ? (int)$_GET['semester'] : 0;

// Optionally retrieve user information from session
$user_name = $_SESSION['user_name'] ?? 'Guest';
$user_id = $_SESSION['user_id'] ?? '12345678'; // Default to a placeholder if not set

// Fetch classes for the selected semester
$classes = getClassesForSemester($semester);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aptitude Classes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="teacher.css"> <!-- Use the same CSS file -->
    <script src="teacher.js"></script> <!-- Use the same JS file -->
</head>

<body>
    <div class="dashboard">
        <!-- Left Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="profile">
                <img src="profile-pic.png" alt="Profile Image" class="profile-img">
                <h3 id="username"><?php echo htmlspecialchars($user_name); ?></h3>
                <p id="reg-number">Reg No: <?php echo htmlspecialchars($user_id); ?></p>
            </div>
            <div id="menu" class="menu">
                <ul>
                    <li onclick="navigateTo('teacher.html')">Home</li>
                    <li>Resources</li>
                    <li>Chat</li>
                    <li>My History</li>
                    <li>FAQ</li>
                </ul>
            </div>
        </div>

        <button id="menuToggle" class="menu-icon">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Main Content Area -->
        <div class="main-content">
            <h2>Aptitude Select Class (Semester: <?php echo htmlspecialchars($semester); ?>)</h2>
            <div class="class-panels">
                <!-- Panels for each class -->
                <?php foreach ($classes as $class): ?>
                    <div class="panel">
                        <h3><?php echo htmlspecialchars($class['name']); ?></h3>
                        <select class="class-dropdown">
                        <option value="">Choose Action</option>
                            <option value="view">View</option>
                            <option value="update">Update</option>
                        </select>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.class-dropdown').forEach(select => {
            select.addEventListener('change', function() {
                const action = this.value; // Get the selected action (view or update)
                const className = this.closest('.panel').querySelector('h3').innerText; // Get the class name from the panel's <h3>

                if (action) {
                    // Redirect based on the selected action
                    if (action === 'view') {
                        window.location.href = `view_aptitude.php?class=${encodeURIComponent(className)}`;
                    } else if (action === 'update') {
                        window.location.href = `update_aptitude.php?class=${encodeURIComponent(className)}`;
                    }
                }
            });
        });
    </script>
</body>
</html>
