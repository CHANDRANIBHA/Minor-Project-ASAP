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

    <style>
        /* Media query for responsiveness */
        @media (max-width: 480px) {
            .signup-container {
                width: 90%;
                padding: 20px;
            }
            h2 {
                font-size: 1.5em;
            }
            button {
                padding: 10px;
                font-size: 0.95em;
            }
        }

        .area {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        .circles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .circles li {
            position: absolute;
            display: block;
            list-style: none;
            color: #8d493a;
            width: 20px;
            height: 20px;
            background: #8d493a;
            animation: animate 25s linear infinite;
            bottom: -150px;
        }

        .circles li:nth-child(1) { left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
        .circles li:nth-child(2) { left: 10%; width: 20px; height: 20px; animation-delay: 2s; animation-duration: 12s; }
        .circles li:nth-child(3) { left: 70%; width: 20px; height: 20px; animation-delay: 4s; }
        .circles li:nth-child(4) { left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s; }
        .circles li:nth-child(5) { left: 65%; width: 20px; height: 20px; animation-delay: 0s; }
        .circles li:nth-child(6) { left: 75%; width: 110px; height: 110px; animation-delay: 3s; }
        .circles li:nth-child(7) { left: 35%; width: 150px; height: 150px; animation-delay: 7s; }
        .circles li:nth-child(8) { left: 50%; width: 25px; height: 25px; animation-delay: 15s; animation-duration: 45s; }
        .circles li:nth-child(9) { left: 20%; width: 15px; height: 15px; animation-delay: 2s; animation-duration: 35s; }
        .circles li:nth-child(10) { left: 85%; width: 150px; height: 150px; animation-delay: 0s; animation-duration: 11s; }

        @keyframes animate {
            0% { transform: translateY(0) rotate(0deg); opacity: 1; border-radius: 0; }
            100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; border-radius: 50%; }
        }
        
        .signup-container {
            position: relative;
            z-index: 1;
            padding: 20px;
            background: #fff;
            width: 400px;
            margin: auto;
            top: 100px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }
        
        .signup-btn {
            background-color: #8d493a;
            color: #fff;
            padding: 10px;
            border: none;
            cursor: pointer;
        }
        
        .error {
            color: red;
            font-size: 0.9em;
        }
    </style>

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
                <!-- <option value="admin" <?php echo $role == 'admin' ? 'selected' : ''; ?>>Admin</option> -->
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

            <!-- <label for="confirm_password"> Confirm Password</label>
            <input type="confirm_password" id="confirm_password" name="confirm_password" placeholder="Enter your password" required>
            <p class="error"><?php echo isset($errors['password']) ? $errors['password'] : ''; ?></p> -->

            <p class="error"><?php echo isset($errors['duplicate']) ? $errors['duplicate'] : ''; ?></p> <!-- Display duplicate error message -->

            <button type="submit" id="signupBtn" class="signup-btn">Signup</button>
        </form>
    </div>

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

    <script src="signup1.js"></script> <!-- Link to your JavaScript file -->
</body>
</html>
