<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include database connection
    require('C:/xampp/htdocs/db.php');

    // Collect form data
    $class_name = $_POST['class_name'];
    $semester = $_POST['semester'];
    $midterm_quant = $_POST['midterm_quant'];
    $midterm_lr = $_POST['midterm_lr'];

    // You can add other inputs here (e.g., end-sem, assignments)

    // Insert or update marks in aptimark_tbl
    $query = "INSERT INTO aptimark_tbl (user_id, semester, subject, midterm_quant, midterm_lr) 
              VALUES (?, ?, ?, ?, ?)
              ON DUPLICATE KEY UPDATE midterm_quant = VALUES(midterm_quant), midterm_lr = VALUES(midterm_lr)";

    // Prepare statement
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issdd", $user_id, $semester, $class_name, $midterm_quant, $midterm_lr);

    if ($stmt->execute()) {
        echo "Marks saved successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<?php
session_start();
require('C:/xampp/htdocs/db.php'); // Database connection

// Get the selected semester from URL
$semester = isset($_GET['semester']) ? $_GET['semester'] : '';

// Optionally retrieve user info
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '12345678';

// Fetch classes for the selected semester
$classes = getClassesForSemester($semester);

// Function to get classes from the database
function getClassesForSemester($semester) {
    global $conn;
    $semester = ucfirst(trim($semester));
    $query = "SELECT class_name FROM class_tbl WHERE semester = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $semester);
    $stmt->execute();
    $result = $stmt->get_result();
    $classes = [];
    while ($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }
    $stmt->close();
    return $classes;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aptitude Marks Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="teacher.css">
    <script src="teacher.js"></script>
</head>

<body>
    <div class="dashboard">
        <div class="sidebar" id="sidebar">
            <div class="profile">
                <img src="profile-pic.png" alt="Profile Image" class="profile-img">
                <h3><?php echo htmlspecialchars($user_name); ?></h3>
                <p>Reg No: <?php echo htmlspecialchars($user_id); ?></p>
            </div>
            <div class="menu">
                <ul>
                    <li onclick="navigateTo('teacher.html')">Home</li>
                    <li>Resources</li>
                    <li>Chat</li>
                    <li>My History</li>
                    <li>FAQ</li>
                </ul>
            </div>
        </div>

        <button id="menuToggle" class="menu-icon">
            <i class="fas fa-bars"></i>
        </button>

        <div class="main-content">
            <h2>Select Class (Semester: <?php echo htmlspecialchars($semester); ?>)</h2>
            <div class="class-panels">
                <!-- Show class panels for each class -->
                <?php foreach ($classes as $class): ?>
                    <div class="panel">
                        <h3><?php echo htmlspecialchars($class['class_name']); ?></h3>
                        <select class="class-dropdown">
                            <option value="view">View</option>
                            <option value="update">Update</option>
                        </select>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Form to enter marks, initially hidden -->
            <div id="marksForm" style="display: none;">
                <h3>Enter Marks for <span id="className"></span></h3>
                <form action="aptimarks.php" method="POST">
                    <input type="hidden" name="class_name" id="classInput">
                    <input type="hidden" name="semester" value="<?php echo htmlspecialchars($semester); ?>">

                    <!-- Input fields for marks -->
                    <label for="midterm_quant">Midterm Quantitative:</label>
                    <input type="number" name="midterm_quant" id="midterm_quant" required><br>

                    <label for="midterm_lr">Midterm LR:</label>
                    <input type="number" name="midterm_lr" id="midterm_lr" required><br>

                    <!-- Repeat for other marks fields (assignments, end-sem, etc.) -->

                    <button type="submit">Save Marks</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.class-dropdown').forEach(select => {
            select.addEventListener('change', function() {
                const action = this.value;
                const className = this.closest('.panel').querySelector('h3').innerText;

                if (action === 'update') {
                    document.getElementById('marksForm').style.display = 'block';
                    document.getElementById('className').innerText = className;
                    document.getElementById('classInput').value = className;
                } else {
                    document.getElementById('marksForm').style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
