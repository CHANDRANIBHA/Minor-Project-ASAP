<?php
include "C:/xampp/htdocs/db.php"; // Include the database connection file
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = $_POST['new-password'];
    $confirmPassword = $_POST['confirm-password'];
    $token = $_POST['token']; // Token received from the email

    // Check if the new passwords match
    if ($newPassword !== $confirmPassword) {
        $error = "Passwords do not match!";
    } else {
        // Hash the new password for security
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Prepare a statement to update the password in the login_table
        $stmt = $conn->prepare("UPDATE login_table SET password = ? WHERE user_id = (SELECT user_id FROM password_resets WHERE token = ? LIMIT 1)");
        $stmt->bind_param("ss", $hashedPassword, $token);

        if ($stmt->execute()) {
            // Password reset successfully, delete the token from the database
            $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();

            $successMessage = "Password reset successfully! You can now log in.";
            // Optionally, redirect to the login page
            header("Location: trialpassword.php");
            exit();
        } else {
            $error = "Error updating password. Please try again.";
        }
    }
}

// Check if the token is valid (you may want to check its expiration time)
$token = $_GET['token'] ?? '';
$stmt = $conn->prepare("SELECT user_id FROM password_resets WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    $error = "Invalid or expired token.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="login.css"> <!-- Include your CSS file -->
</head>
<body>
    <div class="login-container">
        <h2>Reset Your Password</h2>

        <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?> <!-- Display error message -->
        <?php if (isset($successMessage)) { echo "<p style='color:green;'>$successMessage</p>"; } ?> <!-- Display success message -->

        <form method="POST" action="reset_password.php"> <!-- Update form action -->
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>"> <!-- Pass the token -->
            
            <label for="new-password">New Password</label>
            <input type="password" id="new-password" name="new-password" placeholder="Enter new password" required>

            <label for="confirm-password">Confirm Password</label>
            <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm new password" required>

            <button type="submit" class="login-btn">Confirm</button>
        </form>
    </div>

    <script src="forgot.js"></script> <!-- Include your JavaScript file -->
</body>
</html>
