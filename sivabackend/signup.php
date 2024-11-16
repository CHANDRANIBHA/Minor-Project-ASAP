<?php
session_start();

// Include PHPMailer classes at the top of the file
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/asap backend/PHPMailer-master/src/Exception.php'; 
require 'C:/xampp/htdocs/asap backend/PHPMailer-master/src/PHPMailer.php'; 
require 'C:/xampp/htdocs/asap backend/PHPMailer-master/src/SMTP.php'; // Database connection
include "C:/xampp/htdocs/asap backend/db.php"; // Update the path as needed

// Variables to hold form input data
$username = $role = $user_id = $email = $password = $confirm_password = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch and sanitize input
    $username = strtoupper(trim($_POST['username'] ?? ''));
    $role = $_POST['role'] ?? '';
    $user_id = strtoupper($_POST['user_id'] ?? ''); 
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Server-side validations
    if (!preg_match("/^[A-Za-z ]*$/", $username)) $errors['username'] = "Username can contain only letters and spaces.";
    if (empty($role)) $errors['role'] = "Role is required.";
    if ($role === "student" && !preg_match("/^KH\.EN\.U3CDS\d{5}$/i", $user_id)) $errors['user_id'] = "Student ID format is 'KH.EN.U3CDSxxxxx'.";
    if ($role === "teacher" && !preg_match("/^TH\.\S*$/", $user_id)) $errors['user_id'] = "Teacher ID must start with 'TH'.";

    // Check email format as per role
    if (empty($email)) {
        $errors['email'] = "Email is required.";
    } else {
        // Set the expected email format based on the role
        if ($role === 'teacher') {
            $expected_email = strtolower($username . '@kh.amrita.edu'); // For teachers, use user_name
        } elseif ($role === 'student') {
            $expected_email = strtolower($user_id . '@kh.students.amrita.edu'); // For students, use user_id
        } else {
            $expected_email = ''; // If the role is not recognized, leave expected_email blank
        }

        // Validate the email format based on role
        if (empty($expected_email) || strtolower($email) !== $expected_email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format. Expected format: $expected_email";
        }
    }

    // Validate password
    if (!preg_match("/^(?=.*[A-Z])(?=.*\W).{8,}$/", $password)) $errors['password'] = "Password must be 8+ chars with 1 uppercase and 1 special char.";
    elseif ($password !== $confirm_password) $errors['confirm_password'] = "Passwords do not match.";
    else $password = password_hash($password, PASSWORD_DEFAULT);

    // Proceed if no errors
    if (count($errors) === 0) {
        // **CHECK IF THE USER ID ALREADY EXISTS - Before INSERTING**
        $checkStmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
        $checkStmt->bind_param("s", $user_id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        // If user_id already exists, show an error and stop further execution
        if ($result->num_rows > 0) {
            $errors['duplicate'] = "User ID already exists. Use a different one.";
        } else {
            // **Insert the user data into the database only if no duplicate found**
            $stmt = $conn->prepare("INSERT INTO users (user_id, user_name, email_id, password, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $user_id, $username, $email, $password, $role);

            if ($stmt->execute()) {
                // Set session variables
                $_SESSION['user_id'] = $user_id;
                $_SESSION['role'] = $role;

                // Send welcome email to user's email
                sendWelcomeEmail($email, $username);

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

    $conn->close();
}

// Function to send welcome email using PHPMailer
function sendWelcomeEmail($email, $username) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Set the SMTP server for Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'asap2k24project@gmail.com'; // Gmail email address
        $mail->Password = 'kh.en.u3cdsminor'; 
        // Gmail password or App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipient
        $mail->setFrom('asap2k24project@gmail.com', 'ASAP'); // Sender's Gmail address
        $mail->addAddress($email); // Recipient (user's Outlook email)

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Welcome to the Student Assessment Portal';
        $mail->Body    = 'Hello, ' . htmlspecialchars($username) . '!<br>Welcome to our Student Assessment Portal.';

        // Send email
        $mail->send();
        echo 'Signup email sent.';
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }
}


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>
    <link rel="stylesheet" href="signup.css">
    <script src="signup1.js" defer></script>
</head>
<body>
    <div class="signup-container">
        <h2>Signup</h2>
        <form action="signup.php" method="POST">
            <label for="username">User Name</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" value="<?php echo htmlspecialchars($username); ?>" required>
            <p class="error"><?php echo $errors['username'] ?? ''; ?></p>

            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="" disabled <?php echo $role == '' ? 'selected' : ''; ?>>Select your role</option>
                <option value="student" <?php echo $role == 'student' ? 'selected' : ''; ?>>Student</option>
                <option value="teacher" <?php echo $role == 'teacher' ? 'selected' : ''; ?>>Teacher</option>
            </select>
            <p class="error"><?php echo $errors['role'] ?? ''; ?></p>

            <label for="user_id">User ID</label>
            <input type="text" id="user_id" name="user_id" placeholder="Enter your user ID" value="<?php echo htmlspecialchars($user_id); ?>" required>
            <p class="error"><?php echo $errors['user_id'] ?? ''; ?></p>

            <label for="email">Email ID</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email); ?>" required>
            <p class="error"><?php echo $errors['email'] ?? ''; ?></p>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <p class="error"><?php echo $errors['password'] ?? ''; ?></p>

            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
            <p class="error"><?php echo $errors['confirm_password'] ?? ''; ?></p>
            <p class="error"><?php echo $errors['duplicate'] ?? ''; ?></p>

            <button type="submit" class="signup-btn">Signup</button>
        </form>
    </div>
</body>
</html>
