<?php
session_start(); // Start the session to access session variables

// Include database connection
require_once 'C:\XAMPP\htdocs\asap\db.php'; // Update the path as needed

// Initialize an empty error message
$error = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $user_id = trim($_POST['user_id']);
    $password = $_POST['password'];

    // Check if user_id and password are provided
    if (empty($user_id) || empty($password)) {
        $error = "Please enter both User ID and Password.";
    } else {
        // Determine if it's a regular user or admin by checking if the user_id starts with "AD"
        if (substr($user_id, 0, 2) === "AD") {
            // Admin login logic (password is not hashed)
            $stmt = $conn->prepare("SELECT admin_id, password, admin_name FROM admin_tbl WHERE admin_id = ?");
            if ($stmt === false) {
                die("Error preparing the statement: " . htmlspecialchars($conn->error));
            }

            // Bind the user_id parameter
            $stmt->bind_param("s", $user_id);
            $stmt->execute();
            $stmt->store_result();

            // Check if an admin with the provided user_id exists
            if ($stmt->num_rows > 0) {
                // Bind the result variables
                $stmt->bind_result($fetched_admin_id, $stored_password, $fetched_admin_name);
                $stmt->fetch();

                // Directly compare the password for admin (since it's stored as plain text)
                if ($password === $stored_password) {
                    // Set session variables for the admin
                    $_SESSION['user_id'] = $fetched_admin_id;
                    $_SESSION['role'] = 'admin';
                    $_SESSION['user_name'] = $fetched_admin_name;

                    // Redirect to admin interface
                    header("Location: admin_interface.php");
                    exit(); // Terminate the script after redirection
                } else {
                    // Invalid admin password
                    $error = "Invalid admin password!";
                }
            } else {
                // Admin ID does not exist
                $error = "Invalid admin credentials!";
            }

            // Close the statement
            $stmt->close();
        } else {
            // Regular user login logic (password is hashed)
            $stmt = $conn->prepare("SELECT user_id, password, role, user_name FROM users WHERE user_id = ?");
            if ($stmt === false) {
                die("Error preparing the statement: " . htmlspecialchars($conn->error));
            }

            // Bind the user_id parameter
            $stmt->bind_param("s", $user_id);
            $stmt->execute();
            $stmt->store_result();

            // Check if a user with the provided user_id exists
            if ($stmt->num_rows > 0) {
                // Bind the result variables
                $stmt->bind_result($fetched_user_id, $hashed_password, $role, $fetched_user_name);
                $stmt->fetch();

                // Verify the password for user (using password_verify for hashed passwords)
                if (password_verify($password, $hashed_password)) {
                    // Password is correct, set session variables
                    $_SESSION['user_id'] = $fetched_user_id;
                    $_SESSION['role'] = $role;
                    $_SESSION['user_name'] = $fetched_user_name;

                    // Redirect based on the user's role
                    if ($role == 'student') {
                        header("Location: student_interface.php");
                    } elseif ($role == 'teacher') {
                        header("Location: teacher_interface.php");
                    } elseif ($role == 'admin') {
                        header("Location: admin_interface.php");
                    } else {
                        // If role is not recognized, redirect to a default page
                        header("Location: default_interface.php");
                    }
                    exit(); // Terminate the script after redirection
                } else {
                    // Invalid password for user
                    $error = "Invalid password!";
                }
            } else {
                // User ID does not exist
                $error = "Invalid login credentials!";
            }

            // Close the statement
            $stmt->close();
        }
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="login1.css">  <!-- CSS file for styling -->
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
