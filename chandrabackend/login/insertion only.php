<?php
// Include database connection
include "C:/xampp/htdocs/db.php";
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Ensure required parameters are set
if (!isset($_GET['class_id'], $_GET['semester'], $_GET['subject_id'], $_GET['user_id'], $_GET['mode'])) {
    echo "Missing required parameters.";
    exit;
}

// Extract parameters from GET request
$class_id = $_GET['class_id'];
$semester = $_GET['semester'];
$subject_id = $_GET['subject_id'];
$std_id = $_GET['user_id'];
$mode = $_GET['mode'];

// Restrict to subject_id = 1
if ($subject_id != 1) {
    echo "This page only displays data for Subject ID 1.";
    exit;
}

// Get user data from session
$user_name = $_SESSION['user_name'] ?? 'Guest';
$user_id = $_SESSION['user_id'] ?? 'N/A';

// Fetch student information
$student_query = "SELECT u.user_name, u.user_id, s.std_id 
                  FROM students_tbl s 
                  JOIN users u ON s.user_id = u.user_id 
                  WHERE u.user_id = ?";
$stmt = $conn->prepare($student_query);
$stmt->bind_param("s", $std_id);
$stmt->execute();
$student_result = $stmt->get_result();
$student = $student_result->fetch_assoc();
$stmt->close();

if (!$student) {
    echo "Student not found or invalid student ID.";
    exit;
}

// Fetch topic names dynamically
$topic_query = "SELECT topic_id, topic_name FROM topic_tbl WHERE topic_name IN ('quantitative', 'lr')";
$result = $conn->query($topic_query);
$topics = [];
while ($row = $result->fetch_assoc()) {
    $topics[$row['topic_name']] = $row['topic_id'];
}
$quantitative_topic_id = $topics['quantitative'] ?? null;
$lr_topic_id = $topics['lr'] ?? null;

// Ensure both topics are available
if (!$quantitative_topic_id || !$lr_topic_id) {
    die("Required topics 'Quantitative' or 'LR' not found in the database.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetching the marks for each evaluation
    $midterm_quant = (float)$_POST['midterm-quant'] ?? 0;
    $midterm_lr = (float)$_POST['midterm-lr'] ?? 0;
    $endsem_quant = (float)$_POST['endsem-quant'] ?? 0;
    $endsem_lr = (float)$_POST['endsem-lr'] ?? 0;
    $assign1_quant = (float)$_POST['assign1-quant'] ?? 0;
    $assign1_lr = (float)$_POST['assign1-lr'] ?? 0;
    $assign2_quant = (float)$_POST['assign2-quant'] ?? 0;
    $assign2_lr = (float)$_POST['assign2-lr'] ?? 0;
    $extra1_quant = (float)$_POST['extra1-quant'] ?? 0;
    $extra1_lr = (float)$_POST['extra1-lr'] ?? 0;
    $extra2_quant = (float)$_POST['extra2-quant'] ?? 0;
    $extra2_lr = (float)$_POST['extra2-lr'] ?? 0;

    // Fetching the remarks
    $remark_quant = $_POST['remark-quant'] ?? '';
    $remark_lr = $_POST['remark-lr'] ?? '';

    // Calculate totals
    $total_quant = $midterm_quant + $endsem_quant + $assign1_quant + $assign2_quant + $extra1_quant + $extra2_quant;
    $total_lr = $midterm_lr + $endsem_lr + $assign1_lr + $assign2_lr + $extra1_lr + $extra2_lr;

    // Validate total values to ensure they fit the database column range
    if ($total_quant < 0 || $total_quant > 9999 || $total_lr < 0 || $total_lr > 9999) {
        die("Calculated totals exceed the allowed database column size.");
    }

    // Insert into mark_tbl
    $query = "INSERT INTO mark_tbl 
              (user_id, subject_id, topic_id, `Mid term`, `End Sem Mark`, `Assignment 1`, `Assignment 2`, `Extra 1`, `Extra 2`, sem, mark, Remark) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?), (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Initialize prepared statement
    $stmt = $conn->prepare($query);

    // Check if the preparation is successful
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind parameters to the placeholders
    $stmt->bind_param(
        "siidddddiidssiidddddiids", 
        // Quantitative
        $std_id, 
        $subject_id, 
        $quantitative_topic_id, 
        $midterm_quant, 
        $endsem_quant, 
        $assign1_quant, 
        $assign2_quant, 
        $extra1_quant, 
        $extra2_quant, 
        $semester, 
        $total_quant, 
        $remark_quant,
        // LR
        $std_id, 
        $subject_id, 
        $lr_topic_id, 
        $midterm_lr, 
        $endsem_lr, 
        $assign1_lr, 
        $assign2_lr, 
        $extra1_lr, 
        $extra2_lr, 
        $semester, 
        $total_lr, 
        $remark_lr
    );

    // Execute the statement
    if ($stmt->execute()) {
        echo "Marks inserted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();



   
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marks Management</title>
    <link rel="stylesheet" href="aptimarks.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="aptimarks2.js" defer></script>
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="profile">
                <img src="profile-pic.png" alt="Profile Image" class="profile-img">
                <h3 id="user_name"><?php echo htmlspecialchars($user_name); ?></h3> 
                <p id="reg-number">Reg No: <?php echo htmlspecialchars($user_id); ?></p>
            </div>
            <div id="menu" class="menu">
                <ul>
                    <li onclick="navigateTo('teacher_interface.php')">Home</li>
                    <li>
                        <div class="dropdown">
                            <span onclick="toggleDropdown()">Resources</span>
                            <div class="dropdown-content" id="resources-dropdown">
                                <a href="#" onclick="navigateTo('resoaptitude.php')">Aptitude</a>
                                <a href="#" onclick="navigateTo('resoverbal.html')">Verbal</a>
                                <a href="#" onclick="navigateTo('resosoftskills.html')">Soft Skills</a>
                                <a href="#" onclick="navigateTo('resotraining.html')">Professional Training</a>
                            </div>
                        </div>
                    </li>
                    <li onclick="navigateTo('chattr.html')">Chat</li>
                    <li onclick="navigateTo('sessionform.html')">Session</li>
                    <li onclick="navigateTo('history')">My History</li>
                    <li onclick="navigateTo('faq')">FAQ</li>
                </ul>
            </div>
        </div>
        <button id="menuToggle" class="menu-icon"><i class="fas fa-bars"></i></button>

        <!-- Main Content -->
        <div class="main-content">
            <h2>Update Marks for Student: <?php echo htmlspecialchars($student['user_name']); ?></h2>
            <div>
                <h3 id="semester-display">Semester: <?php echo htmlspecialchars($semester); ?></h3>
                <form id="marks-form" method="POST" action="">
                <label for="semester-select">Select Semester:</label>
                <select id="semester-select">
                    <option value="3" <?php echo $semester == 3 ? 'selected' : ''; ?>>Sem 3</option>
                    <option value="4" <?php echo $semester == 4 ? 'selected' : ''; ?>>Sem 4</option>
                    <option value="5" <?php echo $semester == 5 ? 'selected' : ''; ?>>Sem 5</option>
                </select>
            </div>

            <!-- Marks Table -->
            
            <table id="marks-table" border="1">
                <thead>
                    <tr>
                        <th>Evaluation</th>
                        <th><?php echo $quantitative_topic; ?></th>
                        <th><?php echo $lr_topic; ?></th>
                        <th>Total</th>
                       
                    </tr>
                </thead>
                <tbody id="marks-body">
    <tr>
        <td>Mid Term</td>
        <td><input type="number" class="marks-input" id="midterm-quant" name="midterm-quant" value="0" /></td>
        <td><input type="number" class="marks-input" id="midterm-lr" name="midterm-lr" value="0" /></td>
        <td id="midterm-total">0</td>
        
    </tr>
    <tr >
        <td>End Sem Mark</td>
        <td><input type="number" class="marks-input" id="endsem-quant" name="endsem-quant" value="0" /></td>
        <td><input type="number" class="marks-input" id="endsem-lr" name="endsem-lr" value="0" /></td>
        <td id="endsem-total">0</td>
        
    </tr>
    <tr >
        <td>Assignment 1</td>
        <td><input type="number" class="marks-input" id="assign1-quant" name="assign1-quant" value="0" /></td>
        <td><input type="number" class="marks-input" id="assign1-lr" name="assign1-lr" value="0" /></td>
        <td id="assign1-total">0</td>
       
    </tr>
    <tr >
        <td>Assignment 2</td>
        <td><input type="number" class="marks-input" id="assign2-quant" name="assign2-quant" value="0" /></td>
        <td><input type="number" class="marks-input" id="assign2-lr" name="assign2-lr" value="0" /></td>
        <td id="assign2-total">0</td>
        
    </tr>
    <tr >
        <td>Extra 1</td>
        <td><input type="number" class="marks-input" id="extra1-quant" name="extra1-quant" value="0" /></td>
        <td><input type="number" class="marks-input" id="extra1-lr" name="extra1-lr" value="0" /></td>
        <td id="extra1-total">0</td>
        
    </tr>
    <tr >
        <td>Extra 2</td>
        <td><input type="number" class="marks-input" id="extra2-quant" name="extra2-quant" value="0" /></td>
        <td><input type="number" class="marks-input" id="extra2-lr" name="extra2-lr" value="0" /></td>
        <td id="extra2-total">0</td>
        
    </tr>
    <tr>
        <td>Remark</td>
        <td><input type="text" class="marks-text" id="remark-quant" name="remark-quant"  /></td>
        <td><input type="text" class="marks-text" id="remark-lr" name="remark-lr"  /></td>
        
    </tr>
</tbody>

                <tfoot>
                    <tr>
                        <td>Grand Total</td>
                        <td id="quant-grand-total">0</td>
                        <td id="lr-grand-total">0</td>
                        <td id="grand-total">0</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="1" style="text-align: left;">
                            <button id="save-button">Save Marks</button>
                        </td>
                    </tr>
                </tfoot>
            </table>
</form>
        </div>
    </div>
</body>
</html>
