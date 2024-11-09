<?php
session_start();

// Include the db.php file using the correct relative path
require_once __DIR__ . '/../db.php';

// Retrieve user information from the session
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'N/A'; // Default to 'N/A' if not set

// Fetch students if required (this can be replaced with a database query if needed)
$students = [
    ['name' => "John Doe (Roll No: 001)"],
    ['name' => "Jane Smith (Roll No: 002)"],
    ['name' => "Michael Johnson (Roll No: 003)"],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student Marks</title>
    <link rel="stylesheet" href="teacher.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="dashboard">
        <div class="sidebar" id="sidebar">
            <div class="profile">
                <img src="profile-pic.png" alt="Profile Image" class="profile-img">
                
                <h3 id="username"><?php echo htmlspecialchars($user_name); ?></h3>
                <p id="reg-number">Reg No: <?php echo htmlspecialchars($user_id); ?></p>
            </div>
            <div id="menu" class="menu">
                <ul>
                    <li>Home</li>
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

        <div class="main-content">
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

            <div class="main-content">
                <h2>Update Students' Marks: Aptitude <span id="class-name"></span></h2>
                
                <div class="student-panels" id="student-panels-container">
                    <!-- Student panels will be inserted here dynamically -->
                </div>
            </div>
            <script src="teacher.js"></script>
            
            <script>
                function studentClicked(studentName) {
                    const mode = "update";
                    window.location.href = `aptimarks2.php?student=${studentName}&mode=${mode}`;
                }

                const students = <?php echo json_encode($students); ?>;
                const container = document.getElementById('student-panels-container');

                students.forEach(student => {
                    const panel = document.createElement('div');
                    panel.classList.add('panel');
                    panel.innerHTML = `<h3 onclick="studentClicked('${student.name}')">${student.name}</h3>`;
                    container.appendChild(panel);
                });
            </script>
        </div>
    </div>
</body>
</html>
