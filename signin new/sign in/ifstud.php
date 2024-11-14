<?php
session_start(); // Start the session

// Database connection
include "C:/xampp/htdocs/asap backend/db.php"; // Update the path if necessary

$batch = $semester = $year_of_joining = "";
$errors = [];

// Retrieve user_id from session
$user_id = $_SESSION['user_id'] ?? ''; // Get user_id from the session

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
        // Update details in students_tbl where user_id matches
        $stmt = $conn->prepare("UPDATE students_tbl SET batch = ?, sem = ?, year_of_joining = ? WHERE user_id = ?");
        $stmt->bind_param("siis", $batch, $semester, $year_of_joining, $user_id);

        if ($stmt->execute()) {
            echo "<script>alert('Details updated successfully! Redirecting to profile page.'); window.location.href = 'profile.php';</script>";
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
    <title>Details Page</title>
    <link rel="stylesheet" href="ifstud.css">
</head>
<body>
<div class="modal">
    <h2>Enter Details</h2>

    <!-- Error message box -->
    <?php if (count($errors) > 0): ?>
        <div id="error-message" class="error-message">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="ifstud.php" method="POST">
        <div class="form-group">
            <label for="batch">Batch</label>
            <input type="text" id="batch" name="batch" placeholder="Enter batch" required>
        </div>

        <div class="form-group">
            <label for="semester">Semester</label>
            <input type="number" id="semester" name="sem" placeholder="Enter semester" min="1" max="12" required>
        </div>

        <div class="form-group">
            <label for="year_of_joining">Year of Joining</label>
            <input type="number" id="year_of_joining" name="year_of_joining" placeholder="Enter year of joining" required>
        </div>

        <button type="submit">Submit</button>
    </form>
</div>
</body>
</html>
