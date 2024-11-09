<?php
// Include database connection file
include "C:/xampp/htdocs/db.php"; // Update the path as needed

// Initialize variables for form data
$username = $role = $user_id = $email = $password = $confirm_password = ""; // Initialize confirm_password
$errors = [];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'] ?? '';
    $role = $_POST['role'] ?? '';
    $user_id = $_POST['user_id'] ?? ''; 
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Perform server-side validation
    if (empty($username)) {
        $errors['username'] = "Username is required.";
    }
    if (empty($role)) {
        $errors['role'] = "Role is required.";
    }
    if (empty($user_id)) {
        $errors['user_id'] = "User ID is required.";
    }
    if (empty($email)) {
        $errors['email'] = "Email is required.";
    }
    if (empty($password)) {
        $errors['password'] = "Password is required.";
    } elseif ($password !== $confirm_password) {
        $errors['confirm_password'] = "Passwords do not match.";
    } else {
        // Hash the password for security
        $password = password_hash($password, PASSWORD_DEFAULT);
    }

    // If there are no validation errors, check for existing username and user_id
    if (count($errors) === 0) {
        // Prepare a statement to check if a user with the same username and user_id already exists
        $checkStmt = $conn->prepare("SELECT * FROM users WHERE user_name = ? AND user_id = ?");
        $checkStmt->bind_param("ss", $username, $user_id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        // If a matching record is found, set an error message
        if ($result->num_rows > 0) {
            $errors['duplicate'] = "A user with this username and user ID already exists.";
        } else {
            // Prepare and bind the statement to insert a new user
            $stmt = $conn->prepare("INSERT INTO users (user_id, user_name, email_id, password, role) VALUES (?, ?, ?, ?, ?)");
            
            if ($stmt === false) {
                die("Prepare failed: " . htmlspecialchars($conn->error));
            }

            // Bind parameters
            $stmt->bind_param("sssss", $user_id, $username, $email, $password, $role);

            // Execute the query
            if ($stmt->execute()) {
                echo "<script>alert('Signup successful! Redirecting to login page.'); window.location.href = '../login/login.php';</script>";
            } else {
                echo "Error executing statement: " . htmlspecialchars($stmt->error);
            }

            // Close the statement
            $stmt->close();
        }
        // Close the check statement
        $checkStmt->close();
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
            <p id="usernameError" class="error"><?php echo isset($errors['username']) ? $errors['username'] : ''; ?></p>

            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="" disabled <?php echo $role == '' ? 'selected' : ''; ?>>Select your role</option>
                <option value="student" <?php echo $role == 'student' ? 'selected' : ''; ?>>Student</option>
                <option value="teacher" <?php echo $role == 'teacher' ? 'selected' : ''; ?>>Teacher</option>
                <option value="admin" <?php echo $role == 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select>
            <p id="roleError" class="error"><?php echo isset($errors['role']) ? $errors['role'] : ''; ?></p>

            <label for="user_id">User ID</label>
            <input type="text" id="user_id" name="user_id" placeholder="Enter your user ID" value="<?php echo htmlspecialchars($user_id); ?>" required>
            <p id="registrationError" class="error"><?php echo isset($errors['user_id']) ? $errors['user_id'] : ''; ?></p>

            <label for="email">Email ID</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email); ?>" required>
            <p id="emailError" class="error"><?php echo isset($errors['email']) ? $errors['email'] : ''; ?></p>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <p id="passwordError" class="error"><?php echo isset($errors['password']) ? $errors['password'] : ''; ?></p>

            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
            <p id="confirmPasswordError" class="error"><?php echo isset($errors['confirm_password']) ? $errors['confirm_password'] : ''; ?></p>

            <p class="error"><?php echo isset($errors['duplicate']) ? $errors['duplicate'] : ''; ?></p> <!-- Display duplicate error message -->

            <button type="submit" id="signupBtn" class="signup-btn">Signup</button>
        </form>
    </div>

    <script src="signup1.js"></script> <!-- Link to your JavaScript file -->
</body>
</html>
