<?php
include "C:/xampp/htdocs/db.php";
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Ensure parameters are set
if (!isset($_GET['class_id'], $_GET['semester'], $_GET['subject_id'], $_GET['user_id'], $_GET['mode'])) {
    echo "Missing required parameters.";
    exit;
}

// Extract parameters
$class_id = $_GET['class_id'];
$semester = $_GET['semester'];
$subject_id = $_GET['subject_id'];
$std_id = $_GET['user_id'];
$mode = $_GET['mode'];

// Check if subject_id is 1
if ($subject_id != 1) {
    echo "This page only displays data for Subject ID 1.";
    exit;
}

// Get the user data from session
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'N/A';

// Fetch student information
$student_query = "SELECT u.user_name, u.user_id, s.std_id FROM students_tbl s JOIN users u ON s.user_id = u.user_id WHERE u.user_id = ?";
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

// Fetch topic names based on topic IDs
$query = "SELECT topic_id, topic_name FROM topic_tbl WHERE topic_id IN (1, 2)";
$result = mysqli_query($conn, $query);
$topics = [];
while ($row = mysqli_fetch_assoc($result)) {
    $topics[$row['topic_id']] = $row['topic_name'];
}

// Set default values if topics are not found
$quantitative_topic = isset($topics[1]) ? $topics[1] : 'Quantitative';
$lr_topic = isset($topics[2]) ? $topics[2] : 'LR';
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
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody id="marks-body">
                    <tr data-evaluation-id="1">
                        <td>Mid Term</td>
                        <td><input type="number" class="marks-input" id="midterm-quant" value="0" /></td>
                        <td><input type="number" class="marks-input" id="midterm-lr" value="0" /></td>
                        <td id="midterm-total">0</td>
                        <td><input type="text" id="midterm-remarks" /></td>
                    </tr>
                    <tr data-evaluation-id="2">
                        <td>End Sem Mark</td>
                        <td><input type="number" class="marks-input" id="endsem-quant" value="0" /></td>
                        <td><input type="number" class="marks-input" id="endsem-lr" value="0" /></td>
                        <td id="endsem-total">0</td>
                        <td><input type="text" id="endsem-remarks" /></td>
                    </tr>
                    <tr data-evaluation-id="3">
                        <td>Assignment 1</td>
                        <td><input type="number" class="marks-input" id="assign1-quant" value="0" /></td>
                        <td><input type="number" class="marks-input" id="assign1-lr" value="0" /></td>
                        <td id="assign1-total">0</td>
                        <td><input type="text" id="assign1-remarks" /></td>
                    </tr>
                    <tr data-evaluation-id="4">
                        <td>Assignment 2</td>
                        <td><input type="number" class="marks-input" id="assign2-quant" value="0" /></td>
                        <td><input type="number" class="marks-input" id="assign2-lr" value="0" /></td>
                        <td id="assign2-total">0</td>
                        <td><input type="text" id="assign2-remarks" /></td>
                    </tr>
                    <tr data-evaluation-id="5">
                        <td>Extra 1</td>
                        <td><input type="number" class="marks-input" id="extra1-quant" value="0" /></td>
                        <td><input type="number" class="marks-input" id="extra1-lr" value="0" /></td>
                        <td id="extra1-total">0</td>
                        <td><input type="text" id="extra1-remarks" /></td>
                    </tr>
                    <tr data-evaluation-id="6">
                        <td>Extra 2</td>
                        <td><input type="number" class="marks-input" id="extra2-quant" value="0" /></td>
                        <td><input type="number" class="marks-input" id="extra2-lr" value="0" /></td>
                        <td id="extra2-total">0</td>
                        <td><input type="text" id="extra2-remarks" /></td>
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
        </div>
    </div>
</body>
</html>
