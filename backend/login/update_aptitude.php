
<?php
session_start();

// Include the db.php file using the correct relative path
require_once __DIR__ . '/../db.php'; // Moves one directory up to access db.php

// Rest of your code...


// Fetch students or any other required data from the database if needed
// Example: You might want to fetch the student list dynamically
$students = [
    ['name' => "John Doe (Roll No: 001)"],
    ['name' => "Jane Smith (Roll No: 002)"],
    ['name' => "Michael Johnson (Roll No: 003)"],
    // You can fetch this data from the database instead
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student Marks</title>
    <link rel="stylesheet" href="teacher.css"> <!-- Same CSS for consistency -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome for icons -->
</head>
<body>
    <div class="dashboard">
        <!-- Left Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="profile">
                <img src="profile-pic.png" alt="Profile Image" class="profile-img">
                <h3 id="username">John Doe</h3>
                <p id="reg-number">Reg No: 12345678</p>
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

        <!-- Main Content Area -->
        <div class="main-content">
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

            <div class="main-content">
                <h2>Update Students' Marks: Aptitude <span id="class-name"></span></h2>
                
                <div class="student-panels" id="student-panels-container">
                    <!-- Student panels will be inserted here dynamically -->
                </div>
            </div>
            <script src="teacher.js"></script>
            
            <script>
                // Function to handle student selection and navigate to marks.html
                function studentClicked(studentName) {
                    const mode = "update"; // For updating, pass 'update'
                    window.location.href = `aptimarks2.php?student=${studentName}&mode=${mode}`;
                }

                // Define an array of students with their names (this could also be populated dynamically from PHP)
                const students = <?php echo json_encode($students); ?>;

                // Get the container to insert student panels
                const container = document.getElementById('student-panels-container');

                // Create student panels dynamically
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
