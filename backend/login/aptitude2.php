<?php
session_start(); // Start the session to access session variables

// Include the database connection
require('C:/xampp/htdocs/db.php'); // Corrected path for db.php

// Get the selected semester from the URL parameter
$semester = isset($_GET['semester']) ? $_GET['semester'] : '';

// Optionally retrieve user information
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '12345678'; // Default to a placeholder if not set

// Fetch classes based on the semester from the database
$classes = getClassesForSemester($semester); // This function needs to be defined in your PHP code

// function getClassesForSemester($semester) {
//     global $conn; // Use the database connection defined in db.php

//     // Prepare the SQL query to fetch classes based on the semester
//     $query = "SELECT class_name FROM class_tbl WHERE semester = ?";
//     $stmt = $conn->prepare($query);
//     $stmt->bind_param("s", $semester); // Bind the semester parameter
//     $stmt->execute();
//     $result = $stmt->get_result();

//     // Fetch the classes into an array
//     $classes = [];
//     while ($row = $result->fetch_assoc()) {
//         $classes[] = $row;
//     }
//     $stmt->close();
    
//     return $classes; // Return the array of classes
// }



function getClassesForSemester($semester) {
    global $conn;

    // Normalize the semester input
    $semester = ucfirst(trim($semester)); // Ensure it's in the correct format

    // Debug: Print the semester being used
    echo "Searching for classes in semester: " . htmlspecialchars($semester) . "<br>";

    $query = "SELECT class_name FROM class_tbl WHERE semester = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $semester);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the classes into an array
    $classes = [];
    while ($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }
    $stmt->close();

    // Debug: Print the classes fetched
    echo "Classes found: <pre>" . print_r($classes, true) . "</pre>";

    return $classes;
}



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
                        <h3><?php echo htmlspecialchars($class['class_name']); ?></h3>
                        <select class="class-dropdown">
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
                        window.location.href = `view-aptitude.php?class=${encodeURIComponent(className)}`;
                    } else if (action === 'update') {
                        window.location.href = `update-aptitude.php?class=${encodeURIComponent(className)}`;
                    }
                }
            });
        });
    </script>
</body>
</html>
