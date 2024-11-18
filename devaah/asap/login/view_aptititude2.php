<?php
session_start();

// Include the db.php file
require_once __DIR__ . '/../db.php';

// Retrieve the logged-in user's ID and the requested user ID
$logged_in_user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$requested_user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
$semester = isset($_GET['semester']) ? (int)$_GET['semester'] : 0;
$subject_id = isset($_GET['subject_id']) ? (int)$_GET['subject_id'] : 0;

// Check if the logged-in user is the same as the user_id passed in the URL
if ($logged_in_user_id !== $requested_user_id) {
    // Redirect to a safe page (e.g., the dashboard or an error page)
    header('Location: student_interface.php');
    exit();
}

// Fetch the student's marks for the selected semester and subject
$marks = [];

// Example query to fetch marks for the student
if ($semester && $subject_id && $requested_user_id) {
    $stmt = $conn->prepare("SELECT marks FROM marks_tbl WHERE semester = ? AND subject_id = ? AND user_id = ?");
    $stmt->bind_param("iii", $semester, $subject_id, $requested_user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $marks = $row['marks']; // Assuming 'marks' column contains the student's marks
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Marks</title>
    <link rel="stylesheet" href="student.css">
</head>
<body>
    <div class="dashboard">
        <div class="main-content">
            <h2>Your Marks for Subject ID: <?php echo htmlspecialchars($subject_id); ?>, Semester: <?php echo htmlspecialchars($semester); ?></h2>

            <?php if (!empty($marks)): ?>
                <p>Your marks: <?php echo htmlspecialchars($marks); ?></p>
            <?php else: ?>
                <p>No marks found for this subject and semester.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
