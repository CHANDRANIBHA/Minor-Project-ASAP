<?php
// Include database connection
include "C:/xampp/htdocs/db.php";
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Ensure required parameters are set
if (!isset($_GET['class_id'], $_GET['semester'], $_GET['subject_id'], $_GET['user_id'], $_GET['mode'])) {
    die("Missing required parameters.");
}

// Extract parameters
$class_id = (int)$_GET['class_id'];
$semester = (int)$_GET['semester'];
$subject_id = (int)$_GET['subject_id'];
$std_id = $_GET['user_id'];
$mode = $_GET['mode'];

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
    $query = "SELECT mark_id, `Mid term`, `End Sem Mark`, `Assignment 1`, `Assignment 2`, 
                     `Extra 1`, `Extra 2`, `Remark`
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

// Fetch existing marks for both topics
$existing_quant_marks = getExistingMarks($conn, $std_id, $subject_id, $semester, $quantitative_topic_id);
$existing_lr_marks = getExistingMarks($conn, $std_id, $subject_id, $semester, $lr_topic_id);

// Predefine values for missing data
$existing_quant_marks = $existing_quant_marks ?: [
    'Mid term' => 0,
    'End Sem Mark' => 0,
    'Assignment 1' => 0,
    'Assignment 2' => 0,
    'Extra 1' => 0,
    'Extra 2' => 0,
    'Remark' => ''
];

$existing_lr_marks = $existing_lr_marks ?: [
    'Mid term' => 0,
    'End Sem Mark' => 0,
    'Assignment 1' => 0,
    'Assignment 2' => 0,
    'Extra 1' => 0,
    'Extra 2' => 0,
    'Remark' => ''
];

// Function to validate marks (ensures they are numeric and within a reasonable range)
function validateMark($mark) {
    return is_numeric($mark) && $mark >= 0 && $mark <= 100;
}

// Handle form submission (Insert or Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $topics = ['quantitative' => $quantitative_topic_id, 'lr' => $lr_topic_id];

    foreach ($topics as $topic_name => $topic_id) {
        // Collect data from POST
        $data = [
            'Mid term' => (int)($_POST["Mid_term_$topic_name"] ?? 0),
            'End Sem Mark' => (int)($_POST["End_Sem_$topic_name"] ?? 0),
            'Assignment 1' => (int)($_POST["Assignment_1_$topic_name"] ?? 0),
            'Assignment 2' => (int)($_POST["Assignment_2_$topic_name"] ?? 0),
            'Extra 1' => (int)($_POST["Extra_1_$topic_name"] ?? 0),
            'Extra 2' => (int)($_POST["Extra_2_$topic_name"] ?? 0),
            'Remark' => $_POST["Remark_$topic_name"] ?? '',
        ];

        // Validate all marks
        foreach ($data as $key => $value) {
            if (strpos($key, 'Mark') !== false || strpos($key, 'Assignment') !== false) {
                if (!validateMark($value)) {
                    die("Invalid mark for $key.");
                }
            }
        }

        // Check if record exists and update or insert accordingly
        $existing_marks = getExistingMarks($conn, $std_id, $subject_id, $semester, $topic_id);

        if ($existing_marks) {
            $query = "UPDATE mark_tbl SET 
                        `Mid term` = ?, `End Sem Mark` = ?, `Assignment 1` = ?, 
                        `Assignment 2` = ?, `Extra 1` = ?, `Extra 2` = ?, `Remark` = ?
                      WHERE mark_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param(
                "iiiiissi",
                $data['Mid term'], $data['End Sem Mark'], $data['Assignment 1'],
                $data['Assignment 2'], $data['Extra 1'], $data['Extra 2'], 
                $data['Remark'], $existing_marks['mark_id']
            );
            $stmt->execute();
            $stmt->close();
        } else {
            // If no existing record, insert a new one
            $query = "INSERT INTO mark_tbl 
                (user_id, subject_id, topic_id, `Mid term`, `End Sem Mark`, `Assignment 1`, `Assignment 2`, `Extra 1`, `Extra 2`, sem, mark, Remark) 
                VALUES (0, 0, 0, 0, 0, 0, 0, 0, 0, ?, 0, ?)";

            $stmt = $conn->prepare($query);
            $stmt->bind_param("siiiiiis", $std_id, $subject_id, $topic_id, 
                                $data['Mid term'], $data['End Sem Mark'], $data['Assignment 1'], 
                                $data['Assignment 2'], $data['Extra 1'], $data['Extra 2'], $semester, $data['Remark']);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Redirect to the same page after processing
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
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
            <form action="" method="POST">
                <table>
                    <thead>
                        <tr>
                            <th>Evaluation</th>
                            <th>Quantitative</th>
                            <th>Logical Reasoning</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Mid Term</td>
                            <td><input type="number" name="Mid_term_quantitative" value="<?php echo htmlspecialchars($existing_quant_marks['Mid term']); ?>"></td>
                            <td><input type="number" name="Mid_term_lr" value="<?php echo htmlspecialchars($existing_lr_marks['Mid term']); ?>"></td>
                        </tr>
                        <tr>
                            <td>End Sem Mark</td>
                            <td><input type="number" name="End_Sem_quantitative" value="<?php echo htmlspecialchars($existing_quant_marks['End Sem Mark']); ?>"></td>
                            <td><input type="number" name="End_Sem_lr" value="<?php echo htmlspecialchars($existing_lr_marks['End Sem Mark']); ?>"></td>
                        </tr>
                        <tr>
                            <td>Assignment 1</td>
                            <td><input type="number" name="Assignment_1_quantitative" value="<?php echo htmlspecialchars($existing_quant_marks['Assignment 1']); ?>"></td>
                            <td><input type="number" name="Assignment_1_lr" value="<?php echo htmlspecialchars($existing_lr_marks['Assignment 1']); ?>"></td>
                        </tr>
                        <tr>
                            <td>Assignment 2</td>
                            <td><input type="number" name="Assignment_2_quantitative" value="<?php echo htmlspecialchars($existing_quant_marks['Assignment 2']); ?>"></td>
                            <td><input type="number" name="Assignment_2_lr" value="<?php echo htmlspecialchars($existing_lr_marks['Assignment 2']); ?>"></td>
                        </tr>
                        <tr>
                            <td>Extra 1</td>
                            <td><input type="number" name="Extra_1_quantitative" value="<?php echo htmlspecialchars($existing_quant_marks['Extra 1']); ?>"></td>
                            <td><input type="number" name="Extra_1_lr" value="<?php echo htmlspecialchars($existing_lr_marks['Extra 1']); ?>"></td>
                        </tr>
                        <tr>
                            <td>Extra 2</td>
                            <td><input type="number" name="Extra_2_quantitative" value="<?php echo htmlspecialchars($existing_quant_marks['Extra 2']); ?>"></td>
                            <td><input type="number" name="Extra_2_lr" value="<?php echo htmlspecialchars($existing_lr_marks['Extra 2']); ?>"></td>
                        </tr>
                        <tr>
                            <td>Remark</td>
                            <td><input type="text" name="Remark_quantitative" value="<?php echo htmlspecialchars($existing_quant_marks['Remark']); ?>"></td>
                            <td><input type="text" name="Remark_lr" value="<?php echo htmlspecialchars($existing_lr_marks['Remark']); ?>"></td>
                        </tr>
                        <tr>
                            <td>Grand Total</td>
                            <td><?php echo htmlspecialchars($existing_quant_marks['Mid term'] + $existing_quant_marks['End Sem Mark'] + $existing_quant_marks['Assignment 1'] + $existing_quant_marks['Assignment 2'] + $existing_quant_marks['Extra 1'] + $existing_quant_marks['Extra 2']); ?></td>
                            <td><?php echo htmlspecialchars($existing_lr_marks['Mid term'] + $existing_lr_marks['End Sem Mark'] + $existing_lr_marks['Assignment 1'] + $existing_lr_marks['Assignment 2'] + $existing_lr_marks['Extra 1'] + $existing_lr_marks['Extra 2']); ?></td>
                        </tr>
                    </tbody>
                </table>
                <button type="submit">Save Marks</button>
            </form>
        </div>
    </div>
</body>
</html>
