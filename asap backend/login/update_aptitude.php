<?php
session_start();

// Include the db.php file using the correct relative path
require_once __DIR__ . '/../db.php';

// Retrieve user information from the session
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'N/A'; // Default to 'N/A' if not set

// Retrieve class_id and semester from the URL
$class_id = isset($_GET['class_id']) ? (int)$_GET['class_id'] : 0;
$semester = isset($_GET['semester']) ? (int)$_GET['semester'] : 0;

// echo "Class ID: " . $class_id . " | Semester: " . $semester;

// Initialize an empty array to hold the student data
$students = [];

// Check if class_id and semester are valid
if ($class_id && $semester) {
    // Prepare the SQL query
    $stmt = $conn->prepare("SELECT u.user_id, u.user_name 
                            FROM students_tbl s
                            JOIN class_tbl c ON s.class_id = c.class_id
                            JOIN users u ON s.user_id = u.user_id
                            WHERE s.class_id = ? AND c.sem = ?");

    if ($stmt === false) {
        // Query preparation failed, show error
        echo "Error preparing the query: " . $conn->error;
    } else {
        // Bind the parameters and execute the query
        $stmt->bind_param("ii", $class_id, $semester);
        $stmt->execute();

        // Check for execution errors
        if ($stmt->error) {
            echo "Error executing query: " . $stmt->error;
        } else {
            // Get the result and fetch students
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $students[] = $row;
            }
        }

        // Close the prepared statement
        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student Marks</title>
    <link rel="stylesheet" href="teacher.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="dashboard">
        <div class="sidebar" id="sidebar">
            <div class="profile">
                <img src="profile-pic.png" alt="Profile Image" class="profile-img">
                <h3 id="username"><?php echo htmlspecialchars($user_name); ?></h3>
                <p id="reg-number">Reg No: <?php echo htmlspecialchars($user_id); ?></p>
            </div>
            <div id="menu" class="menu">
                <ul>
                    <li>Home</li>
                    <li>Resources</li>
                    <li>Chat</li>
                    <li>My History</li>
                    <li>FAQ</li>
                </ul>
            </div>
        </div>

        <button id="menuToggle" class="menu-icon">
            <i class="fas fa-bars"></i>
        </button>

        <div class="main-content">
            <h2>Update Students' Marks: Aptitude (Class: <?php echo htmlspecialchars($class_id); ?>, Semester: <?php echo htmlspecialchars($semester); ?>)</h2>

            <div class="student-panels" id="student-panels-container">
                <!-- Student panels will be inserted here dynamically -->
                <?php if (count($students) > 0): ?>
                    <?php foreach ($students as $student): ?>
                        <div class="panel">
                            <h3 onclick="studentClicked('<?php echo htmlspecialchars($student['user_name']); ?>')">
                                <?php echo htmlspecialchars($student['user_name']); ?> (Roll No: <?php echo htmlspecialchars($student['user_id']); ?>)
                            </h3>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No students found for this class and semester.</p>
                <?php endif; ?>
            </div>
        </div>

        <script src="teacher.js"></script>

        <script>
            function studentClicked(studentName) {
                const mode = "update";
                window.location.href = `aptimarks2.php?student=${studentName}&mode=${mode}`;
            }
        </script>
    </div>
</body>
</html>
