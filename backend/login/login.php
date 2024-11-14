<?php
session_start();
include "C:/xampp/htdocs/db.php"; // Ensure this path is correct

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = trim($_POST['user_id']); // Trim whitespace
    $password = $_POST['password'];

    echo "User ID entered: " . htmlspecialchars($user_id) . "<br>";
    echo "Password entered: " . htmlspecialchars($password) . "<br>";

    // Prepare the statement
    $stmt = $conn->prepare("SELECT user_id, password, role, user_name FROM users WHERE user_id = ?");
    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Fetch user data
        $stmt->bind_result($fetched_user_id, $hashed_password, $role, $fetched_user_name);  // Add $fetched_user_name
        $stmt->fetch();

        // Debugging output
        echo "Fetched user ID: " . htmlspecialchars($fetched_user_id) . "<br>";
        echo "Fetched hashed password: " . htmlspecialchars($hashed_password) . "<br>";
        echo "Fetched user name: " . htmlspecialchars($fetched_user_name) . "<br>";  // Debugging for user name

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Password is valid
            $_SESSION['user_id'] = $fetched_user_id;
            $_SESSION['role'] = $role;
            $_SESSION['user_name'] = $fetched_user_name;  // Use the correct variable

            // Redirect based on role
            if ($role == 'student') {
                header("Location: student_interface.php");
            } elseif ($role == 'teacher') {
                header("Location: teacher_interface.php");
            } elseif ($role == 'admin') {
                header("Location: admin_interface.php");
            }
            exit();
        } else {
            // Invalid password
            echo "Invalid password!"; // This means the hashed password did not match
        }
    } else {
        echo "Invalid login credentials!"; // Error message for invalid login
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="login.css">  <!-- CSS file for styling -->
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST" action="login.php"> <!-- Update form action -->
            <label for="user_id">User ID</label>
            <input type="text" id="user_id" name="user_id" placeholder="Enter User ID" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter Password" required>

            <button type="submit" class="login-btn">Login</button>
            <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?> <!-- Display error message -->
            <a href="#" id="forgot-password-link">Forgot Password?</a>
        </form>
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Password Reset</h2>
            <label for="email">Enter your email</label>
            <input type="email" id="email" placeholder="Enter your email" required>
            <button id="sendResetLink" class="reset-btn">Send Reset Link</button>
            <p id="message"></p>
        </div>
    </div>

    <script src="login.js"></script>
</body>
</html>
