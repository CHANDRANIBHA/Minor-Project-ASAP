<?php
session_start(); // Start the session to access session variables

// Include database connection
require_once 'C:/xampp/htdocs/asap backend/db.php'; // Update the path as needed

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
        // Check if the user is an admin first
        $stmt = $conn->prepare("SELECT admin_id, password, admin_name FROM admin_tbl WHERE admin_id = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Admin login
            $stmt->bind_result($fetched_admin_id, $hashed_password, $fetched_admin_name);
            $stmt->fetch();

            // Verify the password for admin
            if (password_verify($password, $hashed_password)) { // Use password_verify()
                // Set session variables for the admin
                $_SESSION['user_id'] = $fetched_admin_id;
                $_SESSION['role'] = 'admin';
                $_SESSION['user_name'] = $fetched_admin_name;

                // Redirect to admin interface
                header("Location: admin_interface.php");
                exit();
            } else {
                $error = "Invalid admin password!";
            }
        } else {
            // If not admin, check for student or teacher credentials in the users table
            $stmt = $conn->prepare("SELECT user_id, password, role, user_name FROM users WHERE user_id = ?");
            $stmt->bind_param("s", $user_id);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // User exists, check the password
                $stmt->bind_result($fetched_user_id, $hashed_password, $role, $fetched_user_name);
                $stmt->fetch();

                // Verify the password for student/teacher
                if (password_verify($password, $hashed_password)) { // Use password_verify()
                    // Set session variables based on user role
                    $_SESSION['user_id'] = $fetched_user_id;
                    $_SESSION['role'] = $role;
                    $_SESSION['user_name'] = $fetched_user_name;

                    // Redirect based on user role
                    if ($role == 'student') {
                        header("Location: student_interface.php");
                        exit();
                    } elseif ($role == 'teacher') {
                        header("Location: teacher_interface.php");
                        exit();
                    }
                } else {
                    $error = "Invalid password!";
                }
            } else {
                $error = "Invalid login credentials!";
            }
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST" action="login.php">
            <label for="user_id">User ID</label>
            <input type="text" id="user_id" name="user_id" placeholder="Enter User ID" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter Password" required>

            <button type="submit" class="login-btn">Login</button>
            <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
            <a href="#" id="forgot-password-link">Forgot Password?</a>
        </form>
    </div>
</body>
</html>
