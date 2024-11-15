<?php
session_start();

// Include the db.php file using the correct relative path
require_once __DIR__ . '/../db.php'; // Moves one directory up to access db.php

// Rest of your code...

// Example student data - this can be fetched from the database as needed
$students = [
    ['name' => "John Doe (Roll No: 001)"],
    ['name' => "Jane Smith (Roll No: 002)"],
    ['name' => "Michael Johnson (Roll No: 003)"],
];

// Retrieve class name from the URL parameter
$class_name = isset($_GET['class']) ? htmlspecialchars($_GET['class']) : '';

// Fetch students data from the database if needed
// You can uncomment and modify the following code to fetch from the database
/*
$query = "SELECT name FROM students WHERE class_name = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $class_name);
$stmt->execute();
$result = $stmt->get_result();
$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row; // Populate $students with data from the database
}
$stmt->close();
*/

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details - Aptitude</title>
    <link rel="stylesheet" href="teacher.css"> <!-- Same CSS for consistency -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome for icons -->
    <style>
        /* Panel Style */
        .student-panel {
            background-color: #f5f5f5;
            border: 2px solid #ccc;
            border-radius: 8px;
            margin: 10px;
            padding: 20px;
            text-align: center;
            width: 200px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none; /* Hide by default */
            position: absolute;
            background-color: #d0b8a8;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #8d493a;
        }

        .student-panel:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .student-panel h3 {
            margin-bottom: 10px;
            font-size: 18px;
            cursor: pointer;
        }

        .view-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .view-button:hover {
            background-color: #45a049;
        }

        .student-panels {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }
    </style>
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
                    <li onclick="navigateTo('home')">Home</li>
                    <li>
                        <div class="dropdown">
                            <span onclick="toggleDropdown()">Resources</span>
                            <div class="dropdown-content" id="resources-dropdown">
                                <a href="#" onclick="navigateTo('aptitude')">Aptitude</a>
                                <a href="#" onclick="navigateTo('verbal')">Verbal</a>
                                <a href="#" onclick="navigateTo('softskills')">Soft Skills</a>
                                <a href="#" onclick="navigateTo('training')">Professional Training</a>
                            </div>
                        </div>
                    </li>
                    <li onclick="navigateTo('chat')">Chat</li>
                    <li onclick="navigateTo('history')">My History</li>
                    <li onclick="navigateTo('faq')">FAQ</li>
                </ul>
            </div>
        </div>

        <button id="menuToggle" class="menu-icon">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Main Content Area -->
        <div class="main-content">
            <div class="top-bar">
                <div class="search-container">
                    <input type="text" placeholder="Search...">
                    <i class="fas fa-search"></i>
                </div>
            </div>

            <h2>View Students' Marks: Aptitude <span id="class-name"><?php echo $class_name; ?></span></h2>

            <div class="student-panels" id="student-panels-container">
                <!-- Panels will be inserted dynamically -->
            </div>
        </div>
    </div>

    <script src="teacher.js"></script>
    <script>
        // Retrieve class name from URL
        const params = new URLSearchParams(window.location.search);
        const className = params.get('class');
        document.getElementById('class-name').innerText = className;

        // List of students
        const students = [
            { name: "John Doe (Roll No: 001)" },
            { name: "Jane Smith (Roll No: 002)" },
            { name: "Michael Johnson (Roll No: 003)" }
        ];

        // Function to create student panels dynamically
        const container = document.getElementById('student-panels-container');
        students.forEach(student => {
            const panel = document.createElement('div');
            panel.classList.add('student-panel');
            
            // Create the student name clickable element
            const studentName = document.createElement('h3');
            studentName.textContent = student.name;
            studentName.onclick = function() {
                studentClicked(student.name);
            };
            panel.appendChild(studentName);
            
            // Create the View Marks button
            const viewButton = document.createElement('button');
            viewButton.textContent = 'View Marks';
            viewButton.classList.add('view-button');
            viewButton.onclick = function() {
                studentClicked(student.name);
            };
            panel.appendChild(viewButton);

            container.appendChild(panel);
        });

        // Function to handle student click (navigates to mark6.html in view mode)
        function studentClicked(studentName) {
            const mode = "view";
            window.location.href = `aptimarks2.php?student=${encodeURIComponent(studentName)}&mode=${mode}&class=${encodeURIComponent(className)}`;
        }
        // Function to toggle the dropdown visibility
function toggleDropdown() {
    const dropdown = document.getElementById('resources-dropdown');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}
 
// Function to handle navigation
function navigateTo(page) {
    if (page === 'home') {
        window.location.href = 'teacher html.html'; // Redirect to Teacher Page
    } else if (page === 'aptitude') {
        window.location.href = 'aptitude html.html'; // Redirect to Aptitude Resources
    } else if (page === 'verbal') {
        window.location.href = 'verbal html.html'; // Redirect to Verbal Resources
    } else if (page === 'softskills') {
        window.location.href = 'softskills html.html'; // Redirect to Soft Skills Resources
    } else if (page === 'training') {
        window.location.href = 'training html.html'; // Redirect to Personal Training Resources
    }
}
 
// Close dropdown when clicking outside
window.onclick = function(event) {
    if (!event.target.matches('.dropdown span')) {
        const dropdowns = document.getElementsByClassName("dropdown-content");
        for (let i = 0; i < dropdowns.length; i++) {
            const openDropdown = dropdowns[i];
            if (openDropdown.style.display === 'block') {
                openDropdown.style.display = 'none';
            }
        }
    }
}
 
    </script>
</body>
</html>
