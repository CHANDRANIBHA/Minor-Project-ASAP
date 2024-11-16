<?php
// Database connection setup
require_once __DIR__ . '/../db.php';
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Ensure parameters are set
if (!isset($_GET['class_id'], $_GET['semester'], $_GET['subject_id'], $_GET['std_id'], $_GET['mode'])) {
    echo "Missing required parameters.";
    exit;
}

// Extract parameters
$class_id = $_GET['class_id'];
$semester = $_GET['semester'];
$subject_id = $_GET['subject_id'];
$std_id = $_GET['std_id'];
$mode = $_GET['mode'];

$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'N/A'; // Default to 'N/A' if not set

// Retrieve class_id and semester from the URL
$class_id = isset($_GET['class_id']) ? (int)$_GET['class_id'] : 0;
$semester = isset($_GET['semester']) ? (int)$_GET['semester'] : 0;
$subject_id = isset($_GET['subject_id']) ? (int)$_GET['subject_id'] : 0;
$std_id = isset($_GET['std_id']) ? (int)$_GET['std_id'] : 0; // Optional, use only if selecting a specific student

// Get the user data from session
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'N/A';



// Prepare the SQL query to fetch topics and evaluations
$topic_query = "SELECT * FROM topic_tbl"; // Fetch all topics
$evaluation_query = "SELECT * FROM evaluation_tbl"; // Fetch all evaluations

// Get Topics
$topic_result = $conn->query($topic_query);
$topics = [];
while ($row = $topic_result->fetch_assoc()) {
    $topics[$row['topic_id']] = $row['topic_name'];
}

// Get Evaluations
$evaluation_result = $conn->query($evaluation_query);
$evaluations = [];
while ($row = $evaluation_result->fetch_assoc()) {
    $evaluations[$row['evaluation_id']] = $row['evaluation_name'];
}

// If mode is 'update', we display the mark input form
if ($mode === 'update') {
    // Get student's current marks from mark_tbl
    $marks_query = "
        SELECT m.mark, m.remark, m.topic_id, m.evaluation_id 
        FROM mark_tbl m
        WHERE m.std_id = ? AND m.subject_id = ?
    ";
    $stmt = $conn->prepare($marks_query);
    $stmt->bind_param("ii", $std_id, $subject_id);
    $stmt->execute();
    $mark_result = $stmt->get_result();
    $marks = [];
    while ($row = $mark_result->fetch_assoc()) {
        $marks[$row['topic_id']][$row['evaluation_id']] = [
            'mark' => $row['mark'],
            'remark' => $row['remark']
        ];
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student Marks</title>
    <link rel="stylesheet" href="teacher.css">
</head>
<body>
<div class="dashboard">
        <!-- Left Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="profile">
                <img src="profile-pic.png" alt="Profile Image" class="profile-img">
                <h3 id="user_name"><?php echo htmlspecialchars($user_name); ?></h3> <!-- Display dynamic username -->
                
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
        <button id="menuToggle" class="menu-icon">
            <i class="fas fa-bars"></i>
        </button>

    <div class="main-class">
        <h2>Update Marks for Student: <?php echo htmlspecialchars($user_name); ?> (Reg No: <?php echo htmlspecialchars($user_id); ?>)</h2>
        <h3>Subject: <?php echo htmlspecialchars($subject_id); ?> | Class: <?php echo htmlspecialchars($class_id); ?> | Semester: <?php echo htmlspecialchars($semester); ?></h3>

        <?php if ($mode === 'update'): ?>
            <form action="aptimarks.php" method="POST">
                <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                <input type="hidden" name="semester" value="<?php echo $semester; ?>">
                <input type="hidden" name="subject_id" value="<?php echo $subject_id; ?>">
                <input type="hidden" name="std_id" value="<?php echo $std_id; ?>">

                <table>
                    <thead>
                        <tr>
                            <th>Evaluation</th>
                            <?php foreach ($topics as $topic_id => $topic_name): ?>
                                <th><?php echo htmlspecialchars($topic_name); ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($evaluations as $evaluation_id => $evaluation_name): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($evaluation_name); ?></td>
                                <?php foreach ($topics as $topic_id => $topic_name): ?>
                                    <td>
                                        <input type="number" name="marks[<?php echo $topic_id; ?>][<?php echo $evaluation_id; ?>]" 
                                               value="<?php echo isset($marks[$topic_id][$evaluation_id]['mark']) ? $marks[$topic_id][$evaluation_id]['mark'] : ''; ?>" 
                                               step="0.01" min="0" max="100">
                                        <textarea name="remarks[<?php echo $topic_id; ?>][<?php echo $evaluation_id; ?>]">
                                            <?php echo isset($marks[$topic_id][$evaluation_id]['remark']) ? htmlspecialchars($marks[$topic_id][$evaluation_id]['remark']) : ''; ?>
                                        </textarea>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <button type="submit" name="submit">Save Marks</button>
            </form>
        <?php endif; ?>
    </div>

    <?php
    // Handle the form submission and insert the marks
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        $marks_data = $_POST['marks'];
        $remarks_data = $_POST['remarks'];

        // Insert marks into mark_tbl
        foreach ($marks_data as $topic_id => $evaluations) {
            foreach ($evaluations as $evaluation_id => $mark) {
                $remark = isset($remarks_data[$topic_id][$evaluation_id]) ? $remarks_data[$topic_id][$evaluation_id] : '';

                // Check if a mark already exists for this student, topic, evaluation
                $insert_query = "
                    INSERT INTO mark_tbl (std_id, subject_id, topic_id, evaluation_id, mark, remark)
                    VALUES (?, ?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE mark = VALUES(mark), remark = VALUES(remark)
                ";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("iiidss", $std_id, $subject_id, $topic_id, $evaluation_id, $mark, $remark);
                $stmt->execute();
            }
        }

        // After saving marks, calculate and update total_tbl (total marks for each topic)
        foreach ($marks_data as $topic_id => $evaluations) {
            $total = 0;
            foreach ($evaluations as $evaluation_id => $mark) {
                $total += $mark; // Sum up marks for this topic
            }

            // Insert or update the total in total_tbl
            $total_query = "
                INSERT INTO total_tbl (std_id, topic_id, evaluation_id, total)
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE total = VALUES(total)
            ";
            $stmt = $conn->prepare($total_query);
            $stmt->bind_param("iiid", $std_id, $topic_id, $evaluation_id, $total);
            $stmt->execute();
        }

        echo "<p>Marks updated successfully!</p>";
    }
    ?>
</body>
</html>
