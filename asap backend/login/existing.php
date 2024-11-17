<?php
// Include database connection
include "C:/xampp/htdocs/db.php";
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Ensure required parameters are set
// Check both GET and POST to ensure parameters are available
if (!isset($_GET['class_id'], $_GET['semester'], $_GET['subject_id'], $_GET['user_id'], $_GET['mode']) &&
    !isset($_POST['class_id'], $_POST['semester'], $_POST['subject_id'], $_POST['user_id'], $_POST['mode'])) {
    die("Missing required parameters.");
}

// Extract parameters from GET if available, fallback to POST
$class_id = (int)($_GET['class_id'] ?? $_POST['class_id']);
$semester = (int)($_GET['semester'] ?? $_POST['semester']);
$subject_id = (int)($_GET['subject_id'] ?? $_POST['subject_id']);
$std_id = $_GET['user_id'] ?? $_POST['user_id'];
$mode = $_GET['mode'] ?? $_POST['mode'];

// Rest of your script continues as normal...


// Restrict to subject_id = 1
if ($subject_id != 1) {
    die("This page only displays data for Subject ID 1.");
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
    die("Student not found or invalid student ID.");
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

if (!$quantitative_topic_id || !$lr_topic_id) {
    die("Required topics 'Quantitative' or 'LR' not found in the database.");
}

// Function to fetch existing marks
function getExistingMarks($conn, $std_id, $subject_id, $semester, $topic_id) {
    $query = "SELECT `Mid term`, `End Sem Mark`, `Assignment 1`, `Assignment 2`, 
                     `Extra 1`, `Extra 2`,Remark
              FROM mark_tbl 
              WHERE user_id = ? AND subject_id = ? AND sem = ? AND topic_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("siii", $std_id, $subject_id, $semester, $topic_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
    return $data;
}

// Check for existing marks for Quantitative and LR
$existing_quant_marks = getExistingMarks($conn, $std_id, $subject_id, $semester, $quantitative_topic_id);
$existing_lr_marks = getExistingMarks($conn, $std_id, $subject_id, $semester, $lr_topic_id);

// Combine existing marks into a structured array for easier table rendering
$existing_marks = [
    'Quantitative' => array_merge($existing_quant_marks ?? [], [
        'total' => array_sum($existing_quant_marks ?? []),
        'Remark' => $_POST['remark_quant'] ?? '',
    ]),
    'LR' => array_merge($existing_lr_marks ?? [], [
        'total' => array_sum($existing_lr_marks ?? []),
        'Remark' => $_POST['remark_lr'] ?? '',
    ])
];
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
                    <li onclick="navigateTo('C:\xampp\htdocs\login\teacher_interface.php')">Home</li>
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
    <form id="marks-form" method="POST" action="2.php?class_id=<?php echo htmlspecialchars($class_id); ?>&semester=<?php echo htmlspecialchars($semester); ?>&subject_id=<?php echo htmlspecialchars($subject_id); ?>&user_id=<?php echo htmlspecialchars($std_id); ?>&mode=<?php echo htmlspecialchars($mode); ?>">

    <!-- Add hidden input fields to pass required parameters -->
    <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class_id); ?>">
    <input type="hidden" name="semester" value="<?php echo htmlspecialchars($semester); ?>">
    <input type="hidden" name="subject_id" value="<?php echo htmlspecialchars($subject_id); ?>">
    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($std_id); ?>">
    <input type="hidden" name="mode" value="<?php echo htmlspecialchars($mode); ?>">

    <label for="semester-select">Select Semester:</label>
    <select id="semester-select" name="semester">
        <option value="3" <?php echo $semester == 3 ? 'selected' : ''; ?>>Sem 3</option>
        <option value="4" <?php echo $semester == 4 ? 'selected' : ''; ?>>Sem 4</option>
        <option value="5" <?php echo $semester == 5 ? 'selected' : ''; ?>>Sem 5</option>
    </select>

    <!-- Marks Table -->
    <table id="marks-table" border="1">
        <thead>
            <tr>
                <th>Evaluation</th>
                <th>Quantitative</th>
                <th>Logical Reasoning</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $evaluations = ['Mid term', 'End Sem Mark', 'Assignment 1', 'Assignment 2', 'Extra 1', 'Extra 2'];
            foreach ($evaluations as $evaluation) {
                // Format evaluation key for lookup
                $eval_key = $evaluation;

                // Fetch existing values
                $quant_mark = $existing_marks['Quantitative'][$eval_key] ?? 0;
                $lr_mark = $existing_marks['LR'][$eval_key] ?? 0;

                echo '<tr>';
                echo '<td>' . htmlspecialchars($evaluation) . '</td>';
                echo '<td><input type="number" class="marks-input" name="' . $eval_key . '_quant" value="' . htmlspecialchars($quant_mark) . '" /></td>';
                echo '<td><input type="number" class="marks-input" name="' . $eval_key . '_lr" value="' . htmlspecialchars($lr_mark) . '" /></td>';
                echo '<td>' . ($quant_mark + $lr_mark) . '</td>';
                echo '</tr>';
            }
            ?>
            <tr>
                <td>Remark</td>
                <td><input type="text" class="marks-text" name="remark_quant" value="<?php echo htmlspecialchars($existing_marks['Quantitative']['Remark'] ?? ''); ?>" /></td>
                <td><input type="text" class="marks-text" name="remark_lr" value="<?php echo htmlspecialchars($existing_marks['LR']['Remark'] ?? ''); ?>" /></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td>Grand Total</td>
                <td><?php echo htmlspecialchars($existing_marks['Quantitative']['total']); ?></td>
                <td><?php echo htmlspecialchars($existing_marks['LR']['total']); ?></td>
                <td><?php echo $existing_marks['Quantitative']['total'] + $existing_marks['LR']['total']; ?></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center;">
                    <button type="submit" id="save-button">Save Marks</button>
                </td>
            </tr>
        </tfoot>
    </table>
</form>

            </div>
        </div>
    </div>
</body>
</html>
