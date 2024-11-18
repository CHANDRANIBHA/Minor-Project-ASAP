<?php
// Ensure that the user is logged in and is an admin
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'C:/XAMPP/htdocs/asap/db.php';

// Fetch admin details from session
$adminName = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Marks</title>
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
                <p id="reg-number">Reg No: <?php echo htmlspecialchars($_SESSION['user_id']); ?></p>
            </div>
            <div id="menu" class="menu">
                <ul>
                    <li onclick="window.location.href='../home page/homepage.php';">Home</li>
                    <li onclick="navigateTo('manage.php')">Manage</li>
                    <li onclick="navigateTo('view_marks.php')">View Marks</li>
                    <li onclick="window.location.href='../feedback/view_feedback.php';">View Feedback</li>
                    <li onclick="navigateTo('history.php')">Session History</li>
                    <li onclick="logout()">Logout</li>
                </ul>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="main-content">
            <div class="top-bar">
                <div class="search-container">
                    <input type="text" placeholder="Search...">
                    <i class="fas fa-search"></i>
                </div>
            </div>
            
            <!-- Banner Section -->
            <div class="banner">
                <h1>Welcome Back, <span id="username"><?php echo htmlspecialchars($adminName); ?></span></h1>
                <p>Select the semester for each subject below.</p>
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

        // Optionally, handle the dropdowns and show/hide functionality for semester/training selection
        const selectTrainingButton = document.querySelector('.select-training-btn');
        selectTrainingButton.addEventListener('click', function () {
            const trainingDropdown = document.getElementById('training-dropdown');
            trainingDropdown.style.display = trainingDropdown.style.display === 'none' ? 'block' : 'none';
        });
    </script>

    <script src="admin.js"></script>
</body>
</html>
