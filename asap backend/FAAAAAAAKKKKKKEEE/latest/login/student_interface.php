<?php
session_start(); // Start the session

// Retrieve the user_name and reg_number from the session or default to placeholders
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
$reg_number = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '12345678'; // Example registration number

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="stud.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome for icons -->
</head>
<body>
    <div class="dashboard">
        <!-- Left Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="profile">
                <img src="asap.jpg" alt="Profile Image" class="profile-img">
                <h3 id="username"><?php echo htmlspecialchars($user_name); ?></h3> <!-- PHP to display the user's name -->
                <p id="reg-number">Reg No: <?php echo htmlspecialchars($reg_number); ?></p> <!-- PHP to display the reg number -->
            </div>
            <div id="menu" class="menu">
                <ul>
                    <li onclick="navigateTo('teacher html.html')">Home</li>
                    <li>
                        <div class="dropdown">
                            <span onclick="toggleDropdown()">Resources</span>
                            <div class="dropdown-content" id="resources-dropdown">
                                <a href="#" onclick="navigateTo('resoaptitude.html')">Aptitude</a>
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

            <!-- Graph Section -->
            <div class="graph-section">
                <div class="select-criteria-dropdown">
                    <select id="select-criteria">
                        <option value="" disabled selected>Select Criteria</option>
                        <option value="subject">Subject</option>
                        <option value="semester">Semester</option>
                    </select>
                </div>
                <div id="graph-placeholder">
                    <h3>Graph will appear here</h3>
                    <canvas id="randomGraph"></canvas> <!-- Placeholder for the graph -->
                </div>
            </div>

            <!-- Panels Section -->
            <div class="panels-section">
                <div class="panel" id="aptitude">
                    <h4 class="panel-title">Aptitude</h4>
                    <button class="select-sem-btn">Select Semester</button>
                    <div class="semester-dropdown" id="sem-dropdown-aptitude">
                        <select>
                            <option value="" disabled selected>Select Semester</option>
                            <option value="sem3">Sem 3</option>
                            <option value="sem4">Sem 4</option>
                            <option value="sem5">Sem 5</option>
                        </select>
                    </div>
                </div>

                <div class="panel" id="verbal">
                    <h4 class="panel-title">Verbal</h4>
                    <button class="select-sem-btn">Select Semester</button>
                    <div class="semester-dropdown" id="sem-dropdown-verbal">
                        <select>
                            <option value="" disabled selected>Select Semester</option>
                            <option value="sem3">Sem 3</option>
                            <option value="sem4">Sem 4</option>
                            <option value="sem5">Sem 5</option>
                        </select>
                    </div>
                </div>

                <div class="panel" id="softskill">
                    <h4 class="panel-title">Soft Skill</h4>
                    <button class="select-sem-btn">Select Semester</button>
                    <div class="semester-dropdown" id="sem-dropdown-softskill">
                        <select>
                            <option value="" disabled selected>Select Semester</option>
                            <option value="sem3">Sem 3</option>
                            <option value="sem4">Sem 4</option>
                            <option value="sem5">Sem 5</option>
                        </select>
                    </div>
                </div>

                <div class="panel" id="training">
                    <h4 class="panel-title">Professional Training</h4>
                    <button class="select-training-btn">Select Training</button>
                    <div class="training-options" id="training-dropdown" style="display: none;">
                        <select>
                            <option value="" disabled selected>Select Training</option>
                            <option value="gd">Group Discussion</option>
                            <option value="mock">Mock Interview</option>
                            <option value="public-speaking">Public Speaking</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="stud.js"></script>
</body>
</html>
