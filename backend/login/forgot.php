<?php
include "C:/xampp/htdocs/db.php"; // Include the database connection file

// Start session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Prepare a statement to check if the email exists
    $stmt = $conn->prepare("SELECT user_id FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if email exists
    if ($stmt->num_rows > 0) {
        // Fetch user_id
        $stmt->bind_result($user_id);
        $stmt->fetch();

        // Generate a password reset token (you can use a better method for token generation)
        $token = bin2hex(random_bytes(50)); // Generate a secure random token

        // Store token in the database or a temporary table (not shown here)
        // Example: INSERT INTO password_resets (user_id, token, created_at) VALUES (?, ?, NOW())

        // Create a reset link (make sure to replace `yourdomain.com` with your actual domain)
        $resetLink = "http://yourdomain.com/reset_password.php?token=" . $token;

        // Send email (Make sure to configure your mail server or use a mailing library like PHPMailer)
        $subject = "Password Reset Request";
        $message = "Click on the following link to reset your password: " . $resetLink;
        $headers = "From: no-reply@yourdomain.com\r\n";

        if (mail($email, $subject, $message, $headers)) {
            $successMessage = "Password reset email sent! Please check your inbox.";
        } else {
            $error = "Failed to send email. Please try again.";
        }
    } else {
        $error = "No account found with that email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <h2>Forgot Password</h2>

        <form method="POST" action="forgot.php"> <!-- Form action to the same file -->
            <label for="email">Enter your email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <button type="submit" class="login-btn">Send Reset Link</button>

            <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?> <!-- Display error message -->
            <?php if (isset($successMessage)) { echo "<p style='color:green;'>$successMessage</p>"; } ?> <!-- Display success message -->
        </form>
    </div>

    <script src="forgot.js"></script> <!-- Keep this if you need real-time validation -->
</body>
</html>
