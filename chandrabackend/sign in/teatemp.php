<?php
session_start(); // Start the session

// Check if the user is logged in and has the role of teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    // Redirect to login or show error if user is not authorized
    header("Location:../ login.php"); // Change to your login page or error page
    exit();
}

// Database connection
include "C:/xampp/htdocs/asap backend/db.php"; // Update the path if necessary

// Fetch user ID from session
$user_id = $_SESSION['user_id'];
$subject = "";
$errors = [];

// Fetch subjects from subject_tbl to populate the dropdown
$subjects = [];
$query = "SELECT subject_id, subject_name FROM subject_tbl";
$result = $conn->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch and sanitize input
    $subject_name = $_POST['subject'] ?? '';

    // Server-side validations
    if (empty($subject_name)) $errors['subject'] = "Please select a subject.";

    // Proceed if no errors
    if (count($errors) === 0) {
        // Get subject_id based on subject_name
        $stmt = $conn->prepare("SELECT subject_id FROM subject_tbl WHERE subject_name = ?");
        $stmt->bind_param("s", $subject_name);
        $stmt->execute();
        $stmt->bind_result($subject_id);
        $stmt->fetch();
        $stmt->close();

        // Insert into teacher_tbl, linking to users_tbl with user_id and subject_id
        $stmt = $conn->prepare("INSERT INTO teacher_tbl (user_id, subject_id) VALUES (?, ?)");
        $stmt->bind_param("si", $user_id, $subject_id); // subject_id is an integer
        if ($stmt->execute()) {
            // Successfully inserted into teacher_tbl
            echo "<script>alert('Details saved successfully!'); window.location.href = 'profile.php';</script>";
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
    <title>Teacher Details</title>
    <link rel="stylesheet" href="iftea.css">
</head>
<body>
<div class="modal">
    <h2>Enter Your Details</h2>

    <!-- Error message box -->
    <?php if (!empty($errors)): ?>
        <div id="error-message" class="error-message">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Form for entering additional details -->
    <form action="iftea.php" method="POST">
        <div class="form-group">
            <label for="user_id">User ID</label>
            <input type="text" id="user_id" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>" readonly required>
        </div>

        <div class="form-group">
            <label for="subject">Subject</label>
            <select id="subject" name="subject" required>
                <option value="" disabled selected>Select Subject</option>
                <?php foreach ($subjects as $subject_option): ?>
                    <option value="<?php echo htmlspecialchars($subject_option['subject_name']); ?>" 
                        <?php echo ($subject === $subject_option['subject_name']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($subject_option['subject_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit">Save</button>
    </form>
</div>
</body>
</html>

