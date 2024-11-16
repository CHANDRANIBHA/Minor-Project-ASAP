<?php
session_start(); // Start the session

// Check if the user is logged in and has the role of student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    // Redirect to login or show error if user is not authorized
    header("Location: login.php"); // Change to your login page or error page
    exit();
}

// Database connection
include "C:/xampp/htdocs/asap backend/db.php"; // Update the path if necessary

// Fetch user ID from session
$user_id = $_SESSION['user_id'];
$batch = $semester = $year_of_joining = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch and sanitize input
    $batch = trim($_POST['batch'] ?? '');
    $semester = $_POST['sem'] ?? '';
    $year_of_joining = $_POST['year_of_joining'] ?? '';
    $current_year = date("Y");

    // Server-side validations
    if (empty($batch)) $errors['batch'] = "Please enter the batch.";
    if (empty($semester) || $semester < 1 || $semester > 12) $errors['semester'] = "Semester must be between 1 and 12.";
    if (empty($year_of_joining) || $year_of_joining >= $current_year) $errors['year_of_joining'] = "Year of joining must be a valid past year.";
   
    // Proceed if no errors
    if (count($errors) === 0) {
        // Insert into students_tbl, linking to users_tbl with user_id
        $stmt = $conn->prepare("INSERT INTO students_tbl (user_id, batch, sem, year_of_joining) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $user_id, $batch, $semester, $year_of_joining);

        if ($stmt->execute()) {
            // Successfully inserted into students_tbl
            echo "<script>alert('Details saved successfully!'); window.location.href = 'student_interface.php';</script>";
        } else {
            echo "<p>Error: " . htmlspecialchars($stmt->error) . "</p>";
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <link rel="stylesheet" href="ifstud.css">
</head>
<body>
<div class="modal">
    <h2>Enter Your Details</h2>

    <!-- Error message box -->
    <?php if (count($errors) > 0): ?>
        <div id="error-message" class="error-message">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Form for entering additional details -->
    <form action="ifstud.php" method="POST">
        <label for="batch">Batch</label>
        <input type="text" id="batch" name="batch" value="<?php echo htmlspecialchars($batch); ?>" required>
        
        <label for="sem">Semester</label>
        <input type="number" id="sem" name="sem" value="<?php echo htmlspecialchars($semester); ?>" required min="1" max="12">
        
        <label for="year_of_joining">Year of Joining</label>
        <input type="number" id="year_of_joining" name="year_of_joining" value="<?php echo htmlspecialchars($year_of_joining); ?>" required min="2000" max="<?php echo date('Y'); ?>">

        <button type="submit">Save</button>
    </form>
</div>
</body>
</html>
