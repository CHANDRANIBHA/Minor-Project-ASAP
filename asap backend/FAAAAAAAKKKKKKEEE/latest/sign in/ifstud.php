<?php
session_start(); // Start the session

// Database connection
include "C:/xampp/htdocs/db.php"; // Update the path if necessary

$year_of_joining = "";
$errors = [];

// Retrieve user_id from session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    echo "No user_id found in session.";
    exit; // Stops execution if there's no user_id in the session
}

// Query to fetch classes for dropdown
$class_options = [];
$query = "SELECT class_id, class_name, semester FROM class_tbl";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $class_options[] = $row;
    }
} else {
    echo "No classes found.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch and sanitize input
    $class_id = $_POST['class_id'] ?? '';
    $year_of_joining = $_POST['year_of_joining'] ?? '';
    $current_year = date("Y");

    // Server-side validations
    if (empty($class_id)) $errors['class_id'] = "Please select a class.";
    if (empty($year_of_joining) || $year_of_joining >= $current_year) $errors['year_of_joining'] = "Year of joining must be a valid past year.";

    // Proceed if no errors
    if (count($errors) === 0) {
        // Fetch the semester for the selected class_id
        $stmt = $conn->prepare("SELECT semester FROM class_tbl WHERE class_id = ?");
        $stmt->bind_param("i", $class_id);
        $stmt->execute();
        $stmt->bind_result($semester);
        $stmt->fetch();
        $stmt->close();

        // Insert or update details in students_tbl where user_id matches
        $stmt = $conn->prepare("INSERT INTO students_tbl (class_id, sem, year_of_joining, user_id) 
                                VALUES (?, ?, ?, ?)
                                ON DUPLICATE KEY UPDATE 
                                class_id = VALUES(class_id), 
                                sem = VALUES(sem), 
                                year_of_joining = VALUES(year_of_joining)");

        $stmt->bind_param("iiis", $class_id, $semester, $year_of_joining, $user_id);

        if ($stmt->execute()) {
            echo "<script>window.location.href = '../login/login.php';</script>";
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
            <label for="user_id">User ID</label>
            <input type="text" id="user_id" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>" readonly>
        </div>

        <div class="form-group">
            <label for="batch">Class</label>
            <!-- Class Dropdown -->
            <!-- Class Dropdown -->
<select name="class_id" id="class_id" required>
    <option value="">Select a class</option>
    <?php foreach ($class_options as $class): ?>
        <option value="<?php echo htmlspecialchars($class['class_id']); ?>">
            <?php echo htmlspecialchars($class['class_name']); ?>
        </option>
    <?php endforeach; ?>
</select>

<!-- Year of Joining -->
<div class="form-group">
    <label for="year_of_joining">Year of Joining</label>
    <input type="number" id="year_of_joining" name="year_of_joining" placeholder="Enter year of joining" required>
</div>


        <button type="submit">Submit</button>
    </form>
</div>
<script src="ifstud.js"></script>
</body>
</html>
