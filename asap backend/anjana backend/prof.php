<?php
// Set content type to text/plain for debugging purposes
header('Content-Type: text/plain');

// Database connection
$host = 'localhost';
$username = 'root';
$password = 'Rocky@2021';
$database = 'asap';
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Ensure all required fields exist
$requiredFields = ['activity', 'semester', 'studentCount', 'sessionTime', 'classes'];
$missingFields = array_filter($requiredFields, function($field) {
    return empty($_POST[$field]);
});

if (!empty($missingFields)) {
    echo "Error: Missing required fields - " . implode(', ', $missingFields);
    exit;
}

// Sanitize and process POST data
$activity = htmlspecialchars($_POST['activity']);  // Sanitize activity input
$semester = intval($_POST['semester']);
$studentCount = intval($_POST['studentCount']);
$sessionTime = $_POST['sessionTime'];
$sessionLink = $_POST['sessionLink'] ?? null;  // Optional
$description = $_POST['description'] ?? null;  // Optional

// Sanitize and handle classes
$classes = isset($_POST['classes']) ? array_map('intval', $_POST['classes']) : []; // Ensure classes are integers
if (empty($classes)) {
    echo "Error: No classes selected.";
    exit;
}

// Create the comma-separated string for SQL query
$classIds = implode(',', $classes);

// Fetch students randomly
$query = "
    SELECT users.email_id 
    FROM users 
    JOIN students_tbl ON users.user_id = students_tbl.user_id 
    WHERE students_tbl.sem = ? AND students_tbl.class_id IN ($classIds) 
    ORDER BY RAND() LIMIT ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $semester, $studentCount);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows < $studentCount) {
    echo "Error: Not enough students available.";
    exit;
}

$students = $result->fetch_all(MYSQLI_ASSOC);

// Insert into prof_tbl
$insertQuery = "INSERT INTO prof_tbl (activity_type, semester, class_ids, student_count, session_time, session_link, description) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($insertQuery);
$stmt->bind_param("sisiiss", $activity, $semester, $classIds, $studentCount, $sessionTime, $sessionLink, $description);

// Execute insertion and check if successful
if (!$stmt->execute()) {
    echo "Error: Could not insert training session.";
    exit;
}

// Send emails to selected students
foreach ($students as $student) {
    $emailSubject = "Training Session Invitation";
    $emailMessage = "You are selected for $activity on $sessionTime. Link: $sessionLink";

    // Send email and check for success
    if (!mail($student['email_id'], $emailSubject, $emailMessage)) {
        echo "Error: Failed to send email to " . $student['email_id'];
        exit;
    }
}

echo "Training session created successfully!";

$conn->close();
?>
