<?php  
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'C:/xampp/htdocs/asap backend/db.php';

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
    <title>Admin Dashboard</title>
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
                    <li onclick="navigateTo('home.html')">Home</li>
                    <li onclick="navigateTo('admin.php')">Dashboard</li>
                    <li onclick="navigateTo('manage.php')">Manage</li>
                    <li onclick="navigateTo('admarkview.php')">View Marks</li>
                    <li onclick="navigateTo('viewfeedback.php')">View Feedback</li>
                    <li onclick="navigateTo('history.php')">Session History</li>
                    <li onclick="logout()">Logout</li>
                </ul>
            </div>
        </div>

        <!-- Menu Toggle Icon -->
        <button id="menuToggle" class="menu-icon">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Top Right Icons -->
        <div class="top-right-icons">
            <div class="notification" onclick="toggleNotificationDropdown()">
                <i class="fas fa-bell"></i>
                <div class="notification-dropdown" id="notificationDropdown">
                    <ul>
                        <li>No new notifications</li>
                    </ul>
                </div>
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
                <p>We are excited to have you back!</p>
            </div>

            <!-- Counting Area -->
            <div class="counting-area">
                <div class="count-box">
                    <img src="student.jpg" alt="Total Students" class="panel-image">
                    <h2>Total Students</h2>
                    <p id="studentCount"><?php echo $studentCount; ?></p>
                </div>
                <div class="count-box">
                    <img src="teacher.jpeg" alt="Total Teachers" class="panel-image">
                    <h2>Total Teachers</h2>
                    <p id="teacherCount"><?php echo $teacherCount; ?></p>
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
    </script>

    <script src="admin.js"></script>
</body>
</html>
