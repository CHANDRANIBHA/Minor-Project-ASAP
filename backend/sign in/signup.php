<?php
// Include database connection file
include "C:/xampp/htdocs/db.php"; // Update the path as needed

// Initialize variables for form data
$username = $role = $user_id = $email = $password = ""; // Change registrationNumber to user_id
$errors = [];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'] ?? '';
    $role = $_POST['role'] ?? '';
    $user_id = $_POST['user_id'] ?? ''; // Change to user_id
    $email = $_POST['email'] ?? '';
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password for security

    // Perform validation
    if (empty($username)) {
        $errors['username'] = "Username is required.";
    }
    if (empty($user_id)) { // Validate user_id
        $errors['user_id'] = "User ID is required.";
    }
    if (empty($email)) {
        $errors['email'] = "Email is required.";
    }
    if (empty($_POST['password'])) {
        $errors['password'] = "Password is required.";
    }

    // If there are no validation errors, proceed to check for existing username and user_id
    if (count($errors) == 0) {
        // Prepare a statement to check if a user with the same username and user_id already exists
        $checkStmt = $conn->prepare("SELECT * FROM users WHERE user_name = ? AND user_id = ?"); // Update query
        $checkStmt->bind_param("ss", $username, $user_id); // Update bind parameters
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        // If a matching record is found, set an error message
        if ($result->num_rows > 0) {
            $errors['duplicate'] = "A user with this username and user ID already exists.";
        } else {
            // Prepare and bind the statement to insert new user
            $stmt = $conn->prepare("INSERT INTO users (user_id, user_name, email_id, password, role) VALUES (?, ?, ?, ?, ?)"); // Update query
            
            // Check if prepare() was successful
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
            <p class="error"><?php echo isset($errors['username']) ? $errors['username'] : ''; ?></p>

            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="" disabled <?php echo $role == '' ? 'selected' : ''; ?>>Select your role</option>
                <option value="student" <?php echo $role == 'student' ? 'selected' : ''; ?>>Student</option>
                <option value="teacher" <?php echo $role == 'teacher' ? 'selected' : ''; ?>>Teacher</option>
            </select>
            <p class="error"><?php echo isset($errors['role']) ? $errors['role'] : ''; ?></p>

            <label for="user_id">User ID</label> <!-- Update label -->
            <input type="text" id="user_id" name="user_id" placeholder="Enter your user ID" value="<?php echo htmlspecialchars($user_id); ?>" required> <!-- Update input field -->
            <p class="error"><?php echo isset($errors['user_id']) ? $errors['user_id'] : ''; ?></p> <!-- Update error display -->

            <label for="email">Email ID</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email); ?>" required>
            <p class="error"><?php echo isset($errors['email']) ? $errors['email'] : ''; ?></p>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <p class="error"><?php echo isset($errors['password']) ? $errors['password'] : ''; ?></p>

            <p class="error"><?php echo isset($errors['duplicate']) ? $errors['duplicate'] : ''; ?></p> <!-- Display duplicate error message -->

            <button type="submit" id="signupBtn" class="signup-btn">Signup</button>
        </form>
    </div>

    <script src="signup.js"></script> <!-- Link to your JavaScript file -->
</body>
</html>
