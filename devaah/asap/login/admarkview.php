<?php  
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'C:/XAMPP/htdocs/asap/db.php';

$studentCount = 0;
$teacherCount = 0;

// Fetch admin details directly from the session
$adminName = $_SESSION['user_name'];
$adminID = $_SESSION['user_id'];

// Fetch total student count
$studentQuery = "SELECT COUNT(*) as total FROM students_tbl";
if ($studentResult = $conn->query($studentQuery)) {
    $studentRow = $studentResult->fetch_assoc();
    $studentCount = $studentRow['total'];
}

// Fetch total teacher count
$teacherQuery = "SELECT COUNT(*) as total FROM teacher_tbl";
if ($teacherResult = $conn->query($teacherQuery)) {
    $teacherRow = $teacherResult->fetch_assoc();
    $teacherCount = $teacherRow['total'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - View Marks</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="dashboard">
        <!-- Left Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="profile">
                <img src="profilepic.jpeg" alt="Profile Image" class="profile-img">
                <h3 id="username"><?php echo htmlspecialchars($adminName); ?></h3>
                <p id="reg-number">Reg No: <?php echo htmlspecialchars($adminID); ?></p>
            </div>
            <div id="menu" class="menu">
                <ul>
                    <li onclick="window.location.href='../home page/homepage.php';">Home</li>
                    <li onclick="window.location.href='admin_interface.php';">Dashboard</li>
                    <li onclick="navigateTo('manage.php')">Manage</li>
                    <li onclick="window.location.href='admarkview.php';">View Marks</li> <!-- Active link -->
                    <li onclick="window.location.href='../feedback/view_feedback.php';">View Feedback</li>
                    <li onclick="logout()">Logout</li>
                </ul>
            </div>
        </div>

        <!-- Menu Toggle Icon -->
        <button id="menuToggle" class="menu-icon">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Top Bar -->
            
            
            <!-- Banner Section -->
            <div class="banner">
                <h1>Welcome , <span id="username"><?php echo htmlspecialchars($adminName); ?></span></h1>
                <p>We are excited to have you here!</p>
            </div>

          
            

            <!-- Panels Section for Marks View -->
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
                        <button id="goToAptitudePage">View Class</button>
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
                        <button id="goToVerbalPage">View Class</button>
                    </div>
                </div>

                <div class="panel" id="softskill">
                    <h4 class="panel-title">Soft Skill</h4>
                    <button class="select-sem-btn">Select Semester</button>
                    <div class="semester-dropdown" id="sem-dropdown-softskill">
                        <select id="softskill-semester">
                            <option value="3">Sem 3</option>
                            <option value="4">Sem 4</option>
                            <option value="5">Sem 5</option>
                        </select>
                        <button id="goTosoftskillPage">View Class</button>
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

    <script>
        function navigateTo(url) {
            window.location.href = url;
        }

        function logout() {
            // Logout function
            if (confirm("Are you sure you want to log out?")) {
                window.location.href = "logout.php";
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Add your JavaScript functionality for handling semester and marks view navigation
            function navigateToPage(buttonId, dropdownId, subjectId) {
                document.getElementById(buttonId).addEventListener('click', function () {
                    const semester = document.getElementById(dropdownId).value;
                    if (semester) {
                        console.log(`Navigating to marks view page with semester: ${semester} and subject_id: ${subjectId}`);
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

    <script src="admin.js"></script>
</body>
</html>
