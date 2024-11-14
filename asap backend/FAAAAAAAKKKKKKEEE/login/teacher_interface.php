<?php
session_start(); // Start the session to access session variables

// Retrieve the username and user_id from the session
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest'; // Default to 'Guest' if not set
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '12345678'; // Default to a placeholder if not set


?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="teacher.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome for icons -->
</head>
<body>
    <div class="dashboard">
        <!-- Left Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="profile">
                <img src="profile-pic.png" alt="Profile Image" class="profile-img">
                <h3 id="user_name"><?php echo htmlspecialchars($user_name); ?></h3> <!-- Display dynamic username -->
                
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
                                <a href="#" onclick="navigateTo('resoverbal.html')">Verbal</a>
                                <a href="#" onclick="navigateTo('resosoftskills.html')">Soft Skills</a>
                                <a href="#" onclick="navigateTo('resotraining.html')">Professional Training</a>
                            </div>
                        </div>
                    </li>
                    <li onclick="navigateTo('chattr.html')">Chat</li>
                    <li onclick="navigateTo('sessionform.html')">Session</li>
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
            
            <!-- Banner Section -->
            <div class="banner">
                <div class="confetti left"></div>
                <h1>Welcome Back, <span id="user_name"><?php echo htmlspecialchars($user_name); ?></span>!!!</h1> <!-- Dynamic welcome message -->
                <p>We are excited to have you back!</p>
                <div class="confetti right"></div>
            </div>

           <!-- Panels Section -->
<div class="panels-section">
    <div class="panel" id="aptitude">
        <h4 class="panel-title">Aptitude</h4>
        <button class="select-sem-btn">Select Semester</button>
        <div class="semester-dropdown" id="sem-dropdown-aptitude">
            <select id="aptitude-semester">
                <option value="3">Sem 3</option>
                <option value="4">Sem 4</option>
                <option value="5">Sem 5</option>
            </select>
            <button id="goToAptitudePage">Go</button>
        </div>
    </div>

    <div class="panel" id="verbal">
        <h4 class="panel-title">Verbal</h4>
        <button class="select-sem-btn">Select Semester</button>
        <div class="semester-dropdown" id="sem-dropdown-verbal">
            <select id="verbal-semester">
                <option value="3">Sem 3</option>
                <option value="4">Sem 4</option>
                <option value="5">Sem 5</option>
            </select>
            <button id="goToVerbalPage">Go</button>
        </div>
    </div>

    <div class="panel" id="softskill">
        <h4 class="panel-title">Soft Skill</h4>
        <button class="select-sem-btn">Select Semester</button>
        <div class="semester-dropdown" id="sem-dropdown-softskill">
            <select id="softskills-semester">
                <option value="3">Sem 3</option>
                <option value="4">Sem 4</option>
                <option value="5">Sem 5</option>
            </select>
            <button id="goTosoftskillPage">Go</button>
        </div>
    </div>

    <div class="panel" id="training">
        <h4 class="panel-title">Professional Training</h4>
        <button class="select-training-btn">Select Training</button>
        <div class="training-options" id="training-dropdown" style="display: none;">
            <select>
                <option value="gd">Group Discussion</option>
                <option value="mock">Mock Interview</option>
                <option value="public-speaking">Public Speaking</option>
            </select>
        </div>
    </div>
</div>

<script src="teacher.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Function to set the user's name
        function setUserName(name) {
            document.getElementById('user_name').innerText = name;
        }

        // Call this function with the actual user's name (assumes a PHP variable)
        setUserName('<?php echo htmlspecialchars($user_name); ?>'); // Using PHP variable for the user's name

        // Navigate to aptitude.php when the "Go" button is clicked for Aptitude
        document.getElementById('goToAptitudePage').addEventListener('click', function() {
            const semester = document.getElementById('aptitude-semester').value;
            const subject_id = 1; // Assuming 1 is the ID for "Aptitude" (subject_id from subject_tbl)
            if (semester) {
                console.log(`Navigating to aptitude.php with semester: ${semester} and subject_id: ${subject_id}`);
                window.location.href = `aptitude.php?semester=${encodeURIComponent(semester)}&subject_id=${encodeURIComponent(subject_id)}`;
            } else {
                alert("Please select a semester.");
            }
        });

        // Similar functionality for Verbal
        document.getElementById('goToVerbalPage').addEventListener('click', function() {
            const semester = document.getElementById('verbal-semester').value;
            const subject_id = 2; // Assuming 2 is the ID for "Verbal" (subject_id from subject_tbl)
            if (semester) {
                console.log(`Navigating to verbal.php with semester: ${semester} and subject_id: ${subject_id}`);
                window.location.href = `verbal.php?semester=${encodeURIComponent(semester)}&subject_id=${encodeURIComponent(subject_id)}`;
            } else {
                alert("Please select a semester.");
            }
        });

        // Similar functionality for Soft Skills
        document.getElementById('goTosoftskillPage').addEventListener('click', function() {
            const semester = document.getElementById('softskills-semester').value;
            const subject_id = 3; // Assuming 3 is the ID for "Soft Skills" (subject_id from subject_tbl)
            if (semester) {
                console.log(`Navigating to softskills.php with semester: ${semester} and subject_id: ${subject_id}`);
                window.location.href = `softskills.php?semester=${encodeURIComponent(semester)}&subject_id=${encodeURIComponent(subject_id)}`;
            } else {
                alert("Please select a semester.");
            }
        });
    });
</script>



        