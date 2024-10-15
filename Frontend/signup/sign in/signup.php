<?php
// Include database connection file
include "C:/xampp/htdocs/db.php"; // Update the path as needed

// Initialize variables for form data
$username = $role = $registrationNumber = $email = $password = "";
$errors = [];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'] ?? ''; // Use null coalescing to avoid undefined index notices
    $role = $_POST['role'] ?? '';
    $registrationNumber = $_POST['registration-number'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password for security

    // Perform validation
    if (empty($username)) {
        $errors['username'] = "Username is required.";
    }
    if (empty($registrationNumber)) {
        $errors['registration-number'] = "Registration number is required.";
    }
    if (empty($email)) {
        $errors['email'] = "Email is required.";
    }
    if (empty($_POST['password'])) {
        $errors['password'] = "Password is required.";
    }

    // If there are no validation errors, proceed to insert into the database
    if (count($errors) == 0) {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO users (reg_num, user_name, email_id, password, role) VALUES (?, ?, ?, ?, ?)");
        
        if ($stmt === false) {
            die("Prepare failed: " . htmlspecialchars($conn->error));
        }

        $stmt->bind_param("sssss", $registrationNumber, $username, $email, $password, $role);

        // Execute the query
        if ($stmt->execute()) {
            echo "<script>alert('Signup successful! Redirecting to login page.'); window.location.href = 'login.php';</script>";
        } else {
            echo "Error: " . htmlspecialchars($stmt->error);
        }

        // Close the statement
        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>
    <link rel="stylesheet" href="signup_css.css"> <!-- Correct link to your CSS file -->
</head>
<body>
    <div class="signup-container">
        <h2>Signup</h2>
        <form id="signupForm" action="signup.php" method="POST"> <!-- Change action to submit to itself -->
            <label for="username">User Name</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" value="<?php echo htmlspecialchars($username); ?>" required>
            <p class="error"><?php echo isset($errors['username']) ? $errors['username'] : ''; ?></p>

            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="" disabled <?php echo $role == '' ? 'selected' : ''; ?>>Select your role</option>
                <option value="student" <?php echo $role == 'student' ? 'selected' : ''; ?>>Student</option>
                <option value="teacher" <?php echo $role == 'teacher' ? 'selected' : ''; ?>>Teacher</option>
                <option value="admin" <?php echo $role == 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select>
            <p class="error"><?php echo isset($errors['role']) ? $errors['role'] : ''; ?></p>

            <label for="registration-number">Registration Number</label>
            <input type="text" id="registration-number" name="registration-number" placeholder="Enter your registration number" value="<?php echo htmlspecialchars($registrationNumber); ?>" required>
            <p class="error"><?php echo isset($errors['registration-number']) ? $errors['registration-number'] : ''; ?></p>

            <label for="email">Email ID</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email); ?>" required>
            <p class="error"><?php echo isset($errors['email']) ? $errors['email'] : ''; ?></p>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <p class="error"><?php echo isset($errors['password']) ? $errors['password'] : ''; ?></p>

            <button type="submit" id="signupBtn" class="signup-btn">Signup</button>
        </form>
    </div>

    <script src="signup.js"></script> <!-- Link to your JavaScript file -->
</body>
</html>
