<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "Admin";
$dbname = "asap";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $reg_num = $_POST['registration-number'];
    $student_name = $_POST['username'];
    $email_id = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Hash the password for security
    $role = $_POST['role'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (reg_num, student_name, email_id, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $reg_num, $student_name, $email_id, $password, $role);

    // Execute the query
    if ($stmt->execute()) {
        echo "<script>alert('Signup successful! Redirecting to login page.'); window.location.href = 'login.html';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>