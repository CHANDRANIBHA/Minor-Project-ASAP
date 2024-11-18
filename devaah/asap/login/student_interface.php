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
    <link rel="stylesheet" href="teacher.css">
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
                    <li onclick="window.location.href='../home page/homepage.php';">Home</li>
                    <li onclick="window.location.href='student_interface.php';">Dashboard</li>
                    <li onclick="window.location.href='reso.php';">Resources</li>
                    <li onclick="window.location.href='../feedback/feedback_form.php';">Feedback</li>
                    <li onclick="logout()">Logout</li>

                </ul>
            </div>
        </div>

        <button id="menuToggle" class="menu-icon">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Top Bar -->
            

            <!-- Graph Section -->
            <div class="banner">
                <div class="confetti left"></div>
                <h1>Welcome , <span id="user_name"><?php echo htmlspecialchars($user_name); ?></span>!!!</h1> <!-- Dynamic welcome message -->
                <p>We are excited to have you here!</p>
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
        <div class="semester-dropdown" id="sem-dropdown-verbal">
            <select id="softskill-semester">
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
            </div>
        </div>
    </div>

    <script src="stud.js"></script>
</body>
</html>
