<?php
session_start(); // Start the session

// Include database connection file
include "C:/XAMPP/htdocs/asap/db.php"; // Adjust the path as needed

// Initialize variables for form data
$username = $role = $user_id = $email = $password = $confirm_password = "";
$errors = [];

// Check if database connection is successful
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch and sanitize input
    $username = strtoupper(trim($_POST['username'] ?? ''));
    $role = $_POST['role'] ?? '';
    $user_id = strtoupper($_POST['user_id'] ?? ''); 
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Perform server-side validation
    if (!preg_match("/^[A-Za-z ]*$/", $username)) $errors['username'] = "Username can contain only letters and spaces.";
    if (empty($role)) $errors['role'] = "Role is required.";
    if ($role === "student" && !preg_match("/^KH\.EN\.U3CDS\d{5}$/i", $user_id)) $errors['user_id'] = "Student ID format is 'KH.EN.U3CDSxxxxx'.";
    if ($role === "teacher" && !preg_match("/^TH\.\S*$/", $user_id)) $errors['user_id'] = "Teacher ID must start with 'TH'.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Invalid email format.";
    if (!preg_match("/^(?=.*[A-Z])(?=.*\W).{8,}$/", $password)) $errors['password'] = "Password must be 8+ chars with 1 uppercase and 1 special char.";
    elseif ($password !== $confirm_password) $errors['confirm_password'] = "Passwords do not match.";
    else $password = password_hash($password, PASSWORD_DEFAULT);

    // If there are no validation errors, proceed with checking the user_id
    if (count($errors) === 0) {
        // Check if the user_id already exists
        $checkStmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
        $checkStmt->bind_param("s", $user_id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            // User ID already exists, set error message
            $errors['duplicate'] = "The user ID already exists. Please use a different one.";
        } else {
            // Proceed with inserting the new user
            $stmt = $conn->prepare("INSERT INTO users (user_id, user_name, email_id, password, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $user_id, $username, $email, $password, $role);

            if ($stmt->execute()) {
                // Set session variables
                $_SESSION['user_id'] = $user_id;
                $_SESSION['role'] = $role;

                // Redirect to ifstud.php for additional details if role is student
                if ($role === "student") {
                    echo "<script>alert('Signup successful! Redirecting to details page.'); window.location.href = 'ifstud.php';</script>";
                } else {
                    // Redirect to the login page for teachers
                    echo "<script>alert('Signup successful! Redirecting to login page.'); window.location.href = 'iftea.php';</script>";
                }
            } else {
                echo "Error inserting user details: " . $stmt->error;
            }
            $stmt->close();
        }
        $checkStmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>
    <link rel="stylesheet" href="temp.css"> <!-- Correct link to your CSS file -->
</head>
<body>
    <div class="area">
        <ul class="circles">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div>
    <div class="signup-container">
        <h2>Signup</h2>
        <form action="signup.php" method="POST">
            <label for="username">User Name</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" value="<?php echo htmlspecialchars($username); ?>" required>
            <p id="usernameError" class="error"><?php echo $errors['username'] ?? ''; ?></p>

            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="" disabled <?php echo $role == '' ? 'selected' : ''; ?>>Select your role</option>
                <option value="student" <?php echo $role == 'student' ? 'selected' : ''; ?>>Student</option>
                <option value="teacher" <?php echo $role == 'teacher' ? 'selected' : ''; ?>>Teacher</option>
            </select>
            <p id="roleError" class="error"><?php echo $errors['role'] ?? ''; ?></p>

            <label for="user_id">User ID</label>
            <input type="text" id="user_id" name="user_id" placeholder="Enter your user ID" value="<?php echo htmlspecialchars($user_id); ?>" required>
            <p id="registrationError" class="error"><?php echo $errors['user_id'] ?? ''; ?></p>

            <label for="email">Email ID</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email); ?>" required>
            <p id="emailError" class="error"><?php echo $errors['email'] ?? ''; ?></p>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <p id="passwordError" class="error"><?php echo $errors['password'] ?? ''; ?></p>

            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
            <p id="confirmPasswordError" class="error"><?php echo $errors['confirm_password'] ?? ''; ?></p>
            <p class="error"><?php echo $errors['duplicate'] ?? ''; ?></p>

            <button type="submit" class="signup-btn">Signup</button>
        </form>
    </div>
<script src="temp.js"></script> <!-- Link to your JavaScript file -->
</body>
</html>
