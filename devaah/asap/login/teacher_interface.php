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
                    <li onclick="window.location.href='teacher_interface.php';">Dashboard</li>
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
            
            
            <!-- Banner Section -->
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


<script src="teacher.js"></script>

<script>
    function logout() {
            // Logout function
            if (confirm("Are you sure you want to log out?")) {
                window.location.href = "logout.php";
            }
        }
    document.addEventListener('DOMContentLoaded', function () {
    // Function to navigate to aptitude.php
    function navigateToPage(buttonId, dropdownId, subjectId) {
        document.getElementById(buttonId).addEventListener('click', function () {
            const semester = document.getElementById(dropdownId).value;
            if (semester) {
                console.log(`Navigating to aptitude.php with semester: ${semester} and subject_id: ${subjectId}`);
                window.location.href = `aptitude.php?semester=${encodeURIComponent(semester)}&subject_id=${encodeURIComponent(subjectId)}`;
            } else {
                alert("Please select a semester.");
            }
        });
    }

    // Set event listeners for each button
    navigateToPage('goToAptitudePage', 'aptitude-semester', 1); // Aptitude
    navigateToPage('goToVerbalPage', 'verbal-semester', 2); // Verbal
    navigateToPage('goTosoftskillPage', 'softskill-semester', 3); // Soft Skills
});

</script>
</body>
</html>



        
