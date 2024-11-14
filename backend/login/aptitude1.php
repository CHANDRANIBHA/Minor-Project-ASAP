<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require 'db.php'; // Include your database connection file

// Get the selected semester from the URL parameter
$semester = isset($_GET['semester']) ? $_GET['semester'] : '';

// Optionally retrieve user information
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '12345678';

function getClassesForSemester($semester) {
    global $conn; // Ensure you can access the connection variable

    $stmt = $conn->prepare("SELECT class_name FROM class_tbl WHERE semester = ?");
    $stmt->bind_param('s', $semester);
    $stmt->execute();
    $result = $stmt->get_result();

    $classes = [];
    while ($row = $result->fetch_assoc()) {
        $classes[] = ['name' => $row['class_name']];
    }

    $stmt->close();

    return $classes;
}

$classes = getClassesForSemester($semester);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aptitude Classes</title>
    <link rel="stylesheet" href="teacher.css">
</head>
<body>
    <div class="dashboard">
        <h2>Aptitude Select Class (Semester: <?php echo htmlspecialchars($semester); ?>)</h2>
        <div class="class-panels">
            <?php if (empty($classes)): ?>
                <p>No classes found for semester: <?php echo htmlspecialchars($semester); ?></p>
            <?php else: ?>
                <?php foreach ($classes as $class): ?>
                    <div class="panel">
                        <h3><?php echo htmlspecialchars($class['name']); ?></h3>
                        <select class="class-dropdown">
                            <option value="view">View</option>
                            <option value="update">Update</option>
                        </select>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
