<?php
// Start the session
session_start();

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "asap";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the admin is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Fetch admin details from the session (assuming these values are stored in session upon login)
$adminID = $_SESSION['user_id'];
$adminName = $_SESSION['user_name'];

// Handle form submissions for adding classes, removing teachers, or updating semester
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = ''; // To store success or error message
    $isSuccess = false;

    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add_class') {
            // Add new class logic
            $className = $_POST['class_name'];
            $semester = $_POST['semester'];
            $additionalInfo = $_POST['additional_info'];

            $stmt = $conn->prepare("INSERT INTO class_tbl (class_name, semester, additional_info) VALUES (?, ?, ?)");
            $stmt->bind_param("sis", $className, $semester, $additionalInfo);
            if ($stmt->execute()) {
                $message = "Class added successfully!";
                $isSuccess = true;
            } else {
                $message = "Error adding class.";
            }
            $stmt->close();
        }
        elseif ($_POST['action'] == 'delete_class') {
            // Delete class logic (based on class name)
            $className = $_POST['class_name'];

            $stmt = $conn->prepare("DELETE FROM class_tbl WHERE class_name = ?");
            $stmt->bind_param("s", $className);
            if ($stmt->execute()) {
                $message = "Class deleted successfully!";
                $isSuccess = true;
            } else {
                $message = "Error deleting class.";
            }
            $stmt->close();
        }
        elseif ($_POST['action'] == 'remove_teacher') {
            // Remove teacher logic (based on teacher ID and teacher name)
            $teacherID = $_POST['teacher_id']; // Teacher ID (varchar)
            $teacherName = $_POST['teacher_name']; // Teacher Name
        
            // Find user_id based on teacher_name and teacher_id
            $stmt = $conn->prepare("SELECT user_id FROM teacher_tbl WHERE user_id = ? AND user_id IN (SELECT user_id FROM users WHERE user_name = ?)");
            $stmt->bind_param("ss", $teacherID, $teacherName);
            $stmt->execute();
            $stmt->bind_result($userId);
            
            if ($stmt->fetch()) {
                // Delete teacher from teacher_tbl
                $stmt->close();
                $stmt = $conn->prepare("DELETE FROM teacher_tbl WHERE user_id = ?");
                $stmt->bind_param("s", $teacherID);
                if ($stmt->execute()) {
                    // Delete teacher from users table
                    $stmt->close();
                    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
                    $stmt->bind_param("s", $userId);
                    if ($stmt->execute()) {
                        $message = "Teacher removed successfully!";
                        $isSuccess = true;
                    } else {
                        $message = "Error removing teacher from users table.";
                    }
                } else {
                    $message = "Error removing teacher from teacher_tbl.";
                }
                $stmt->close();
            } else {
                $message = "Teacher not found!";
            }
        }
        
        elseif ($_POST['action'] == 'update_semester') {
            // Update semester logic
            $stmt = $conn->prepare("UPDATE class_tbl SET semester = semester + 1");
            if ($stmt->execute()) {
                $message = "Semester updated successfully!";
                $isSuccess = true;
            } else {
                $message = "Error updating semester.";
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Manage</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background-color: #8d493a;
            color: white;
            padding-top: 20px;
            overflow-y: auto;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .sidebar .profile {
            text-align: center;
            padding: 20px;
            border-bottom: 2px solid #d0b8a8;
            margin-bottom: 30px;
        }

        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 15px;
        }

        .profile h3 {
            margin: 10px 0;
            font-size: 1.2em;
            font-weight: bold;
        }

        .profile p {
            color: #d0b8a8;
            font-size: 0.9em;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 15px 20px;
            cursor: pointer;
            transition: background 0.3s;
            text-align: left;
        }

        .sidebar ul li:hover {
            background-color: #d0b8a8;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
            font-size: 1.1em;
        }

        /* Banner and Content */
        .banner {
            background-color: #8d493a;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 24px;
            margin-left: 250px;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .panel {
            background-color: #ffffff;
            padding: 20px;
            margin: 10px;
            cursor: pointer;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .panel:hover {
            background-color: #e0e0e0;
        }

        /* Popup and Confirmation Styling */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        .confirmation-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            z-index: 2000;
            text-align: center;
        }

        .confirmation-popup.success {
            border: 2px solid #4CAF50;
        }

        .confirmation-popup.error {
            border: 2px solid #f44336;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
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

    <!-- Banner Section -->
    <div class="banner">
        <h1>Welcome Back, <span id="username"><?php echo htmlspecialchars($adminName); ?></span></h1>
        <p>We are excited to have you back!</p>
    </div>

    <!-- Main Content for Managing Classes -->
    <div class="main-content">
        <h1>Manage Admin Operations</h1>

        <!-- Panels for each operation -->
        <div class="panel" onclick="openPopup('addClassPopup')">Add New Class</div>
        <div class="panel" onclick="openPopup('removeTeacherPopup')">Remove Teacher</div>
        <div class="panel" onclick="openPopup('deleteClassPopup')">Delete Class</div>
        <div class="panel" onclick="openPopup('updateSemesterPopup')">Update Semester</div>

        <!-- Popups for adding/removing operations -->
        <!-- Add Class -->
        <div class="popup" id="addClassPopup">
            <h3>Add New Class</h3>
            <form method="post" action="">
                <input type="text" name="class_name" placeholder="Class Name" required>
                <input type="number" name="semester" placeholder="Semester" required>
                <textarea name="additional_info" placeholder="Additional Information" required></textarea>
                <input type="hidden" name="action" value="add_class">
                <button type="submit">Add Class</button>
            </form>
            <button onclick="closePopup('addClassPopup')">Close</button>
        </div>

        <!-- Remove Teacher -->
<div class="popup" id="removeTeacherPopup">
    <h3>Remove Teacher</h3>
    <form method="post" action="">
        <input type="text" name="teacher_id" placeholder="Teacher ID" required>
        <input type="text" name="teacher_name" placeholder="Teacher Name" required>
        <input type="hidden" name="action" value="remove_teacher">
        <button type="submit">Remove Teacher</button>
    </form>
    <button onclick="closePopup('removeTeacherPopup')">Close</button>
</div>

        <!-- Delete Class -->
        <div class="popup" id="deleteClassPopup">
            <h3>Delete Class</h3>
            <form method="post" action="">
                <input type="text" name="class_name" placeholder="Class Name" required>
                <input type="hidden" name="action" value="delete_class">
                <button type="submit">Delete Class</button>
            </form>
            <button onclick="closePopup('deleteClassPopup')">Close</button>
        </div>

        <!-- Update Semester -->
        <div class="popup" id="updateSemesterPopup">
            <h3>Update Semester</h3>
            <form method="post" action="">
                <input type="hidden" name="action" value="update_semester">
                <button type="submit">Update Semester</button>
            </form>
            <button onclick="closePopup('updateSemesterPopup')">Close</button>
        </div>

        <!-- Confirmation Popup -->
        <div class="confirmation-popup" id="confirmationPopup">
            <p id="confirmationMessage"></p>
            <button onclick="closeConfirmation()">Close</button>
        </div>

        <script>
            function openPopup(popupId) {
                document.getElementById(popupId).style.display = 'block';
            }

            function closePopup(popupId) {
                document.getElementById(popupId).style.display = 'none';
            }

            function openConfirmation(message, isSuccess) {
                const confirmationPopup = document.getElementById('confirmationPopup');
                const confirmationMessage = document.getElementById('confirmationMessage');
                confirmationMessage.textContent = message;
                confirmationPopup.className = isSuccess ? 'confirmation-popup success' : 'confirmation-popup error';
                confirmationPopup.style.display = 'block';
            }

            function closeConfirmation() {
                document.getElementById('confirmationPopup').style.display = 'none';
            }
        </script>
    </div>
</body>
</html>