<?php
header('Content-Type: application/json');

// Database connection
$host = 'localhost';
$username = 'root';
$password = 'Rocky@2021';
$database = 'asap';
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Check if the semester parameter is provided and valid
if (isset($_GET['semester']) && is_numeric($_GET['semester'])) {
    $semester = intval($_GET['semester']);

    // Prepare and execute the query to fetch classes based on the semester
    $query = "SELECT class_id, class_name FROM class_tbl WHERE sem = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $semester);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if classes were found for the given semester
        if ($result->num_rows > 0) {
            $classes = [];
            while ($row = $result->fetch_assoc()) {
                $classes[] = $row;
            }

            // Return the classes as a JSON response
            echo json_encode($classes);
        } else {
            // No classes found for the given semester
            echo json_encode(["error" => "No classes found for the selected semester."]);
        }

        $stmt->close();
    } else {
        // Query preparation failed
        echo json_encode(["error" => "Error preparing the query."]);
    }
} else {
    // Invalid or missing semester parameter
    echo json_encode(["error" => "Invalid or missing semester parameter."]);
}

// Close the database connection
$conn->close();
?>
