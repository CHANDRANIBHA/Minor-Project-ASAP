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
                <a href="teaprofile.php">
                    <img src="profile-pic.png" alt="Profile Image" class="profile-img">
                    <h3 id="user_name"><?php echo htmlspecialchars($user_name); ?></h3> <!-- Display dynamic username -->
                    <p id="reg-number">Reg No: <?php echo htmlspecialchars($user_id); ?></p>
                </a>
            </div>
            
            <div id="menu" class="menu">
                <ul>
                    <li onclick="navigateTo('teacher_interface.php')">Home</li>
                    <li>
                        <div class="dropdown">
                            <span onclick="toggleDropdown()">Resources</span>
                            <div class="dropdown-content" id="resources-dropdown">
                                <a href="#" onclick="navigateTo('resoaptitude.php')">Aptitude</a>
                                <a href="#" onclick="navigateTo('resoverbal.php')">Verbal</a>
                                <a href="#" onclick="navigateTo('resosoftskills.php')">Soft Skills</a>
                                <a href="#" onclick="navigateTo('resotraining.php')">Professional Training</a>
                            </div>
                        </div>
                    </li>
                    <li onclick="navigateTo('chat.php')">Chat</li>
                    <li onclick="navigateTo('sessionform.html')">Session</li>
                    <li onclick="navigateTo('feedback/feedback_form.php')">Feedback</li>
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
                <h1>Welcome, <span id="user_name"><?php echo htmlspecialchars($user_name); ?></span>!!!</h1> <!-- Dynamic welcome message -->
                <p>We are excited to have you back!</p>
                <div class="confetti right"></div>
            </div>

            <!-- Panels Section -->
            <div class="panels-section">
                <!-- Panel for Aptitude -->
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

                <!-- Panel for Verbal -->
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

                <!-- Panel for Soft Skill -->
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

        // Call this function with the actual user's name
        setUserName('<?php echo htmlspecialchars($user_name); ?>'); // Using PHP variable for the user's name

        // Handle the Go button click for Aptitude
        document.getElementById('goToAptitudePage').addEventListener('click', function() {
            const semester = document.getElementById('aptitude-semester').value;
            if (semester) {
                window.location.href = `aptitude.php?semester=${encodeURIComponent(semester)}`; // Redirect to aptitude.php with the selected semester
            } else {
                alert("Please select a semester.");
            }
        });

        // Handle the Go button click for Verbal
        document.getElementById('goToVerbalPage').addEventListener('click', function() {
            const semester = document.getElementById('verbal-semester').value;
            if (semester) {
                window.location.href = `verbal.php?semester=${encodeURIComponent(semester)}`; // Redirect to verbal.php with the selected semester
            } else {
                alert("Please select a semester.");
            }
        });

        // Handle the Go button click for Soft Skill
        document.getElementById('goTosoftskillPage').addEventListener('click', function() {
            const semester = document.getElementById('softskills-semester').value;
            if (semester) {
                window.location.href = `softskills.php?semester=${encodeURIComponent(semester)}`; // Redirect to softskills.php with the selected semester
            } else {
                alert("Please select a semester.");
            }
        });
    });
    </script>
</body>
</html>
