<?php
include "C:/xampp/htdocs/db.php"; // Correct include statement

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $registerNumber = $_POST['register-number'];
    $password = $_POST['password'];

    // Prepare a statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT password, role FROM login_tbl WHERE user_id = ?");
    $stmt->bind_param("s", $registerNumber);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashedPassword, $role);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            // User is authenticated
            // Redirect based on role or display success message
            header("Location: dashboard.php"); // Redirect to dashboard or another page
            exit();
        } else {
            $error = "Invalid login credentials!";
        }
    } else {
        $error = "Invalid login credentials!";
    }

    // Close statement
    $stmt->close();
}
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
            <label for="register-number">Register Number</label>
            <input type="text" id="register-number" name="register-number" placeholder="Enter Register Number" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter Password" required>

            <button type="submit" class="login-btn">Login</button>
            <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?> <!-- Display error message -->
            <a href="#" id="forgot-password-link">Forgot Password?</a>  <!-- Trigger for modal -->
        </form>
    </div>

    <!-- Modal for forgot password -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Password Reset</h2>
            <label for="email">Enter your email</label>
            <input type="email" id="email" placeholder="Enter your email" required>
            <button id="sendResetLink" class="reset-btn">Send Reset Link</button>
            <p id="message"></p> <!-- Message to show after sending reset link -->
        </div>
    </div>

    <script src="login.js"></script>  <!-- JavaScript file -->
</body>
</html>
