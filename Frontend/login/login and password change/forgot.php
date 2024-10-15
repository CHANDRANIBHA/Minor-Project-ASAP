<?php
include '"C:\xampp\htdocs\db.php"'; // Include the database connection file

// Start session to store user ID or registration number if needed
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = $_POST['new-password'];
    $confirmPassword = $_POST['confirm-password'];
    $userId = $_SESSION['user_id']; // Assuming you stored user ID in session during password reset request

    // Check if the new passwords match
    if ($newPassword !== $confirmPassword) {
        $error = "Passwords do not match!";
    } else {
        // Hash the new password for security
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Prepare a statement to update the password
        $stmt = $conn->prepare("UPDATE login_tbl SET password = ? WHERE user_id = ?");
        $stmt->bind_param("ss", $hashedPassword, $userId);

        if ($stmt->execute()) {
            // Password reset successfully
            $successMessage = "Password reset successfully! You can now log in.";
            // Optionally, you can redirect to the login page
            header("Location: trialpassword.php"); // Redirect to login page
            exit();
        } else {
            $error = "Error updating password. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <h2>Reset Your Password</h2>

        <form method="POST" action="forgot.php"> <!-- Update form action -->
            <label for="new-password">New Password</label>
            <input type="password" id="new-password" name="new-password" placeholder="Enter new password" required>

            <label for="confirm-password">Confirm Password</label>
            <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm new password" required>

            <button type="submit" id="resetPasswordBtn" class="login-btn">Confirm</button>

            <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?> <!-- Display error message -->
            <?php if (isset($successMessage)) { echo "<p style='color:green;'>$successMessage</p>"; } ?> <!-- Display success message -->
        </form>
    </div>

    <script src="forgot.js"></script> <!-- Keep this if you need real-time validation -->
</body>
</html>
