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

$topic_query = "SELECT topic_id, topic_name 
                FROM topic_tbl 
                WHERE topic_name IN ('bl_verbal', 'grammar_verbal', 'vocabulary_verbal', 'confidence_verbal', 'add1', 'add2')";

// Execute the query
$result = $conn->query($topic_query);

// Check if query execution was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Fetch topic IDs dynamically
$topics = [];
while ($row = $result->fetch_assoc()) {
    $topics[$row['topic_name']] = $row['topic_id'];
}


// Assign the topic IDs to variables
$bl_topic_id = $topics['bl_verbal'] ?? null;
$vocab_topic_id = $topics['vocabulary_verbal'] ?? null;
$grammar_topic_id = $topics['grammar_verbal'] ?? null;
$confidence_topic_id = $topics['confidence_verbal'] ?? null;
$extra1_topic_id = $topics['add1'] ?? null;
$extra2_topic_id = $topics['add2'] ?? null;


if (!$bl_topic_id || !$vocab_topic_id || !$grammar_topic_id || !$confidence_topic_id || !$extra1_topic_id || !$extra2_topic_id) {
    die("Required topics are not found in the database.");
}


$bl_topic_id = 5;
$vocab_topic_id = 7;
$grammar_topic_id = 8;
$confidence_topic_id = 9;
$extra1_topic_id = 10;
$extra2_topic_id = 11;


// Function to fetch existing marks
function getExistingMarks($conn, $std_id, $subject_id, $semester, $topic_id) {
    $query = "SELECT `mark_id`, `Mid term`, `End Sem Mark`, `Assignment 1`, `Assignment 2`, 
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


// Fetch existing marks for each topic
$existing_bl_marks = getExistingMarks($conn, $std_id, $subject_id, $semester, $bl_topic_id) ?? [];
$existing_vocab_marks = getExistingMarks($conn, $std_id, $subject_id, $semester, $vocab_topic_id) ?? [];
$existing_grammar_marks = getExistingMarks($conn, $std_id, $subject_id, $semester, $grammar_topic_id) ?? [];
$existing_confidence_marks = getExistingMarks($conn, $std_id, $subject_id, $semester, $confidence_topic_id) ?? [];
$existing_extra1_marks = getExistingMarks($conn, $std_id, $subject_id, $semester, $extra1_topic_id) ?? [];
$existing_extra2_marks = getExistingMarks($conn, $std_id, $subject_id, $semester, $extra2_topic_id) ?? [];
// Predefine values for missing data in case of no existing marks
$existing_bl_marks = array_merge([
    'Mid term' => 0,
    'End Sem Mark' => 0,
    'Assignment 1' => 0,
    'Assignment 2' => 0,
    'Extra 1' => 0,
    'Extra 2' => 0,
    'Remark' => ''
], $existing_bl_marks);

$existing_vocab_marks = array_merge([
    'Mid term' => 0,
    'End Sem Mark' => 0,
    'Assignment 1' => 0,
    'Assignment 2' => 0,
    'Extra 1' => 0,
    'Extra 2' => 0,
    'Remark' => ''
], $existing_vocab_marks);

$existing_grammar_marks = array_merge([
    'Mid term' => 0,
    'End Sem Mark' => 0,
    'Assignment 1' => 0,
    'Assignment 2' => 0,
    'Extra 1' => 0,
    'Extra 2' => 0,
    'Remark' => ''
], $existing_grammar_marks);

$existing_confidence_marks = array_merge([
    'Mid term' => 0,
    'End Sem Mark' => 0,
    'Assignment 1' => 0,
    'Assignment 2' => 0,
    'Extra 1' => 0,
    'Extra 2' => 0,
    'Remark' => ''
], $existing_confidence_marks);

$existing_extra1_marks = array_merge([
    'Mid term' => 0,
    'End Sem Mark' => 0,
    'Assignment 1' => 0,
    'Assignment 2' => 0,
    'Extra 1' => 0,
    'Extra 2' => 0,
    'Remark' => ''
], $existing_extra1_marks);

$existing_extra2_marks = array_merge([
    'Mid term' => 0,
    'End Sem Mark' => 0,
    'Assignment 1' => 0,
    'Assignment 2' => 0,
    'Extra 1' => 0,
    'Extra 2' => 0,
    'Remark' => ''
], $existing_extra2_marks);


// Handle form submission (Insert or Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $mode === 'update') {
    
    $topics = [
        "bl_softskill" => $bl_topic_id, 
        'vocabulary_softskill' => $vocab_topic_id, 
        'grammar_softskill' => $grammar_topic_id, 
        'confidence_softskill' => $confidence_topic_id, 
        'extra1' => $extra1_topic_id, 
        'extra2' => $extra2_topic_id
    ];
    foreach ($topics as $topic_name => $topic_id) {
        $data = [
            'Mid term' => (int)($_POST["Mid_term_$topic_name"] ?? 0),
            'End Sem Mark' => (int)($_POST["End_Sem_Mark_$topic_name"] ?? 0),
            'Assignment 1' => (int)($_POST["Assignment_1_$topic_name"] ?? 0),
            'Assignment 2' => (int)($_POST["Assignment_2_$topic_name"] ?? 0),
            'Extra 1' => (int)($_POST["Extra_1_$topic_name"] ?? 0),
            'Extra 2' => (int)($_POST["Extra_2_$topic_name"] ?? 0),
            'Remark' => $_POST["Remark_$topic_name"] ?? '',
        

        // Calculate total marks (same for all topics)
        $total_bl = $existing_bl_marks['Mid term'] + $existing_bl_marks['End Sem Mark'] + $existing_bl_marks['Assignment 1'] + $existing_bl_marks['Assignment 2'] + $existing_bl_marks['Extra 1'] + $existing_bl_marks['Extra 2'],
        $total_vocab = $existing_vocab_marks['Mid term'] + $existing_vocab_marks['End Sem Mark'] + $existing_vocab_marks['Assignment 1'] + $existing_vocab_marks['Assignment 2'] + $existing_vocab_marks['Extra 1'] + $existing_vocab_marks['Extra 2'],
        $total_grammar = $existing_grammar_marks['Mid term'] + $existing_grammar_marks['End Sem Mark'] + $existing_grammar_marks['Assignment 1'] + $existing_grammar_marks['Assignment 2'] + $existing_grammar_marks['Extra 1'] + $existing_grammar_marks['Extra 2'],
        $total_confi = $existing_confidence_marks['Mid term'] + $existing_confidence_marks['End Sem Mark'] + $existing_confidence_marks['Assignment 1'] + $existing_confidence_marks['Assignment 2'] + $existing_confidence_marks['Extra 1'] + $existing_confidence_marks['Extra 2'],
        $total_extra1 = $existing_extra1_marks['Mid term'] + $existing_extra1_marks['End Sem Mark'] + $existing_extra1_marks['Assignment 1'] + $existing_extra1_marks['Assignment 2'] + $existing_extra1_marks['Extra 1'] + $existing_extra1_marks['Extra 2'],
        $total_extra2 = $existing_extra2_marks['Mid term'] +  $existing_extra2_marks['End Sem Mark'] +  $existing_extra2_marks['Assignment 1'] +  $existing_extra2_marks['Assignment 2'] + $existing_extra2_marks['Extra 1'] +  $existing_extra2_marks['Extra 2']
   
    ];
    // Check if record exists
    $existing = getExistingMarks($conn, $std_id, $subject_id, $semester, $topic_id);

    
        // // Check if record exists for this topic
        // $existing = $existing_marks[$topic_name];

        if (isset($existing['mark_id'])) {
            // Update existing record
             
           // Update existing record
           $query = "UPDATE mark_tbl SET 
           `Mid term` = ?, `End Sem Mark` = ?, `Assignment 1` = ?, 
           `Assignment 2` = ?, `Extra 1` = ?, `Extra 2` = ?, `Remark` = ?
         WHERE mark_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param(
            "iiiiissi",
            $data['Mid term'], $data['End Sem Mark'], $data['Assignment 1'],
            $data['Assignment 2'], $data['Extra 1'], $data['Extra 2'], 
            $data['Remark'], $existing['mark_id']
            );
            $stmt->execute();
            $stmt->close();
            echo($query);
        } // Insert new record
        $query = "INSERT INTO mark_tbl 
                    (user_id, subject_id, topic_id, `Mid term`, `End Sem Mark`, 
                     `Assignment 1`, `Assignment 2`, `Extra 1`, `Extra 2`, `Remark`, sem, mark) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($query);
        
        // Check each topic and bind parameters accordingly
        if ($topic_id == $bl_topic_id) {
            $stmt->bind_param(
                "siiddddddsid", 
                $std_id, $subject_id, $topic_id, $data['Mid term'], $data['End Sem Mark'],
                $data['Assignment 1'], $data['Assignment 2'], $data['Extra 1'], 
                $data['Extra 2'], $data['Remark'], $semester, $total_bl
            );
        } elseif ($topic_id == $vocab_topic_id) {
            $stmt->bind_param(
                "siiddddddsid", 
                $std_id, $subject_id, $topic_id, $data['Mid term'], $data['End Sem Mark'],
                $data['Assignment 1'], $data['Assignment 2'], $data['Extra 1'], 
                $data['Extra 2'], $data['Remark'], $semester, $total_vocab
            );
        } elseif ($topic_id == $grammar_topic_id) {
            $stmt->bind_param(
                "siiddddddsid", 
                $std_id, $subject_id, $topic_id, $data['Mid term'], $data['End Sem Mark'],
                $data['Assignment 1'], $data['Assignment 2'], $data['Extra 1'], 
                $data['Extra 2'], $data['Remark'], $semester, $total_grammar
            );
        } elseif ($topic_id == $confidence_topic_id) {
            $stmt->bind_param(
               "siiddddddsid",  
                $std_id, $subject_id, $topic_id, $data['Mid term'], $data['End Sem Mark'],
                $data['Assignment 1'], $data['Assignment 2'], $data['Extra 1'], 
                $data['Extra 2'], $data['Remark'], $semester, $total_confi
            );
        } elseif ($topic_id == $extra1_topic_id) {
            $stmt->bind_param(
               "siiddddddsid", 
                $std_id, $subject_id, $topic_id, $data['Mid term'], $data['End Sem Mark'],
                $data['Assignment 1'], $data['Assignment 2'], $data['Extra 1'], 
                $data['Extra 2'], $data['Remark'], $semester, $total_extra1
            );
        } elseif ($topic_id == $extra2_topic_id) {
            $stmt->bind_param(
                "siiddddddsid", 
                $std_id, $subject_id, $topic_id, $data['Mid term'], $data['End Sem Mark'],
                $data['Assignment 1'], $data['Assignment 2'], $data['Extra 1'], 
                $data['Extra 2'], $data['Remark'], $semester, $total_extra2
            );
        } else {
            // If the topic is not recognized
            die("Unknown topic ID: $topic_id");
        }
        echo($query);
        $stmt->execute();
        $stmt->close();
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
                    <th>Body Language</th>
                    <th>Grammar</th>
                    <th>Vocabulary</th>
                    <th>Confidence</th>
                    <th>Extra 1</th>
                    <th>Extra 2</th>
                    <th>Total</th>
                </tr>
            </thead>
<tbody>
    <tr>
    
    <td>Mid Term</td>
    <td><input type="number" name="Mid_term_bl_softskill" value="<?php echo htmlspecialchars($existing_bl_marks['Mid term'] ?? 0); ?>"></td>
    <td><input type="number" name="Mid_term_grammar_softskill" value="<?php echo htmlspecialchars($existing_grammar_marks['Mid term'] ?? 0); ?>"></td>
    <td><input type="number" name="Mid_term_vocabulary_softskill" value="<?php echo htmlspecialchars($existing_vocab_marks['Mid term'] ?? 0); ?>"></td>
    <td><input type="number" name="Mid_term_confidence_softskill" value="<?php echo htmlspecialchars($existing_confidence_marks['Mid term'] ?? 0); ?>"></td>
    <td><input type="number" name="Mid_term_extra1" value="<?php echo htmlspecialchars($existing_extra1_marks['Mid term'] ?? 0); ?>"></td>
    <td><input type="number" name="Mid_term_extra2" value="<?php echo htmlspecialchars($existing_extra2_marks['Mid term'] ?? 0); ?>"></td>
    <td id="midterm-total">
        <?php echo htmlspecialchars(
            ($existing_bl_marks['Mid term'] ?? 0) +
            ($existing_grammar_marks['Mid term'] ?? 0) +
            ($existing_vocab_marks['Mid term'] ?? 0) +
            ($existing_confidence_marks['Mid term'] ?? 0) +
            ($existing_extra1_marks['Mid term'] ?? 0) +
            ($existing_extra2_marks['Mid term'] ?? 0)
        ); ?>
    </td>
    </tr>

<tr>
    <td>End Sem</td>
    <td><input type="number" name="End_Sem_Mark_bl_softskill" value="<?php echo htmlspecialchars($existing_bl_marks['End Sem Mark'] ?? 0); ?>"></td>
    <td><input type="number" name="End_Sem_Mark_grammar_softskill" value="<?php echo htmlspecialchars($existing_grammar_marks['End Sem Mark'] ?? 0); ?>"></td>
    <td><input type="number" name="End_Sem_Mark_vocabulary_softskill" value="<?php echo htmlspecialchars($existing_vocab_marks['End Sem Mark'] ?? 0); ?>"></td>
    <td><input type="number" name="End_Sem_Mark_confidence_softskill" value="<?php echo htmlspecialchars($existing_confidence_marks['End Sem Mark'] ?? 0); ?>"></td>
    <td><input type="number" name="End_Sem_Mark_extra1" value="<?php echo htmlspecialchars($existing_extra1_marks['End Sem Mark'] ?? 0); ?>"></td>
    <td><input type="number" name="End_Sem_Mark_extra2" value="<?php echo htmlspecialchars($existing_extra2_marks['End Sem Mark'] ?? 0); ?>"></td>
    <td id="endsem-total">
        <?php echo htmlspecialchars(
            ($existing_bl_marks['End Sem Mark'] ?? 0) +
            ($existing_grammar_marks['End Sem Mark'] ?? 0) +
            ($existing_vocab_marks['End Sem Mark'] ?? 0) +
            ($existing_confidence_marks['End Sem Mark'] ?? 0) +
            ($existing_extra1_marks['End Sem Mark'] ?? 0) +
            ($existing_extra2_marks['End Sem Mark'] ?? 0)
        ); ?>
    </td>
   
</tr>

<tr>
    <td>Assignment 1</td>
    <td><input type="number" name="Assignment_1_bl_softskill" value="<?php echo htmlspecialchars($existing_bl_marks['Assignment 1'] ?? 0); ?>"></td>
    <td><input type="number" name="Assignment_1_grammar_softskill" value="<?php echo htmlspecialchars($existing_grammar_marks['Assignment 1'] ?? 0); ?>"></td>
    <td><input type="number" name="Assignment_1_vocabulary_softskill" value="<?php echo htmlspecialchars($existing_vocab_marks['Assignment 1'] ?? 0); ?>"></td>
    <td><input type="number" name="Assignment_1_confidence_softskill" value="<?php echo htmlspecialchars($existing_confidence_marks['Assignment 1'] ?? 0); ?>"></td>
    <td><input type="number" name="Assignment_1_extra1" value="<?php echo htmlspecialchars($existing_extra1_marks['Assignment 1'] ?? 0); ?>"></td>
    <td><input type="number" name="Assignment_1_extra2" value="<?php echo htmlspecialchars($existing_extra2_marks['Assignment 1'] ?? 0); ?>"></td>
    <td id="assign1-total">
        <?php echo htmlspecialchars(
            ($existing_bl_marks['Assignment 1'] ?? 0) +
            ($existing_grammar_marks['Assignment 1'] ?? 0) +
            ($existing_vocab_marks['Assignment 1'] ?? 0) +
            ($existing_confidence_marks['Assignment 1'] ?? 0) +
            ($existing_extra1_marks['Assignment 1'] ?? 0) +
            ($existing_extra2_marks['Assignment 1'] ?? 0)
        ); ?>
    </td>
   
</tr>

<tr>
    <td>Assignment 2</td>
    <td><input type="number" name="Assignment_2_bl_softskill" value="<?php echo htmlspecialchars($existing_bl_marks['Assignment 2'] ?? 0); ?>"></td>
    <td><input type="number" name="Assignment_2_grammar_softskill" value="<?php echo htmlspecialchars($existing_grammar_marks['Assignment 2'] ?? 0); ?>"></td>
    <td><input type="number" name="Assignment_2_vocabulary_softskill" value="<?php echo htmlspecialchars($existing_vocab_marks['Assignment 2'] ?? 0); ?>"></td>
    <td><input type="number" name="Assignment_2_confidence_softskill" value="<?php echo htmlspecialchars($existing_confidence_marks['Assignment 2'] ?? 0); ?>"></td>
    <td><input type="number" name="Assignment_2_extra1" value="<?php echo htmlspecialchars($existing_extra1_marks['Assignment 2'] ?? 0); ?>"></td>
    <td><input type="number" name="Assignment_2_extra2" value="<?php echo htmlspecialchars($existing_extra2_marks['Assignment 2'] ?? 0); ?>"></td>
    <td id="assign2-total">
        <?php echo htmlspecialchars(
            ($existing_bl_marks['Assignment 2'] ?? 0) +
            ($existing_grammar_marks['Assignment 2'] ?? 0) +
            ($existing_vocab_marks['Assignment 2'] ?? 0) +
            ($existing_confidence_marks['Assignment 2'] ?? 0) +
            ($existing_extra1_marks['Assignment 2'] ?? 0) +
            ($existing_extra2_marks['Assignment 2'] ?? 0)
        ); ?>
    </td>
    
</tr>

<td>Extra 1</td>
    <td><input type="number" name="Extra_1_bl_softskill" value="<?php echo htmlspecialchars($existing_bl_marks['Extra 1'] ?? 0); ?>"></td>
    <td><input type="number" name="Extra_1_grammar_softskill" value="<?php echo htmlspecialchars($existing_grammar_marks['Extra 1'] ?? 0); ?>"></td>
    <td><input type="number" name="Extra_1_vocabulary_softskill" value="<?php echo htmlspecialchars($existing_vocab_marks['Extra 1'] ?? 0); ?>"></td>
    <td><input type="number" name="Extra_1_confidence_softskill" value="<?php echo htmlspecialchars($existing_confidence_marks['Extra 1'] ?? 0); ?>"></td>
    <td><input type="number" name="Extra_1_extra1" value="<?php echo htmlspecialchars($existing_extra1_marks['Extra 1'] ?? 0); ?>"></td>
    <td><input type="number" name="Extra_1_extra2" value="<?php echo htmlspecialchars($existing_extra2_marks['Extra 1'] ?? 0); ?>"></td>
    <td id="extra1-total">
        <?php echo htmlspecialchars(
            ($existing_bl_marks['Extra 2'] ?? 0) +
            ($existing_grammar_marks['Extra 2'] ?? 0) +
            ($existing_vocab_marks['Extra 2'] ?? 0) +
            ($existing_confidence_marks['Extra 2'] ?? 0) +
            ($existing_extra1_marks['Extra 2'] ?? 0) +
            ($existing_extra2_marks['Extra 2'] ?? 0)
        ); ?>
    </td>
    
</tr>
    <tr>
    <td>Extra 2</td>
    <td><input type="number" name="Extra_2_bl_softskill" value="<?php echo htmlspecialchars($existing_bl_marks['Extra 2'] ?? 0); ?>"></td>
    <td><input type="number" name="Extra_2_grammar_softskill" value="<?php echo htmlspecialchars($existing_grammar_marks['Extra 2'] ?? 0); ?>"></td>
    <td><input type="number" name="Extra_2_vocabulary_softskill" value="<?php echo htmlspecialchars($existing_vocab_marks['Extra 2'] ?? 0); ?>"></td>
    <td><input type="number" name="Extra_2_confidence_softskill" value="<?php echo htmlspecialchars($existing_confidence_marks['Extra 2'] ?? 0); ?>"></td>
    <td><input type="number" name="Extra_2_extra1" value="<?php echo htmlspecialchars($existing_extra1_marks['Extra 2'] ?? 0); ?>"></td>
    <td><input type="number" name="Extra_2_extra2" value="<?php echo htmlspecialchars($existing_extra2_marks['Extra 2'] ?? 0); ?>"></td>
    <td id="extra2-total">
        <?php echo htmlspecialchars(
            ($existing_bl_marks['Extra 2'] ?? 0) +
            ($existing_grammar_marks['Extra 2'] ?? 0) +
            ($existing_vocab_marks['Extra 2'] ?? 0) +
            ($existing_confidence_marks['Extra 2'] ?? 0) +
            ($existing_extra1_marks['Extra 2'] ?? 0) +
            ($existing_extra2_marks['Extra 2'] ?? 0)
        ); ?>
    </td>
    
</tr>

<tr>
    <td>Remark</td>
    <td><input type="text" name="Remark_bl_softskill" value="<?php echo htmlspecialchars($existing_bl_marks['Remark'] ?? ''); ?>"></td>
    <td><input type="text" name="Remark_grammar_softskill" value="<?php echo htmlspecialchars($existing_grammar_marks['Remark'] ?? ''); ?>"></td>
    <td><input type="text" name="Remark_vocabulary_softskill" value="<?php echo htmlspecialchars($existing_vocab_marks['Remark'] ?? ''); ?>"></td>
    <td><input type="text" name="Remark_confidence_softskill" value="<?php echo htmlspecialchars($existing_confidence_marks['Remark'] ?? ''); ?>"></td>
    <td><input type="text" name="Remark_extra1" value="<?php echo htmlspecialchars($existing_extra1_marks['Remark'] ?? ''); ?>"></td>
    <td><input type="text" name="Remark_extra2" value="<?php echo htmlspecialchars($existing_extra2_marks['Remark'] ?? ''); ?>"></td>
    <td></td>
</tr>


<tr>
    <td>Grand Total</td>
    <td><?php echo htmlspecialchars(
        $existing_bl_marks['Mid term'] + 
        $existing_bl_marks['End Sem Mark'] + 
        $existing_bl_marks['Assignment 1'] + 
        $existing_bl_marks['Assignment 2'] + 
        $existing_bl_marks['Extra 1'] + 
        $existing_bl_marks['Extra 2']
    ); ?></td>
    <td><?php echo htmlspecialchars(
        $existing_grammar_marks['Mid term'] + 
        $existing_grammar_marks['End Sem Mark'] + 
        $existing_grammar_marks['Assignment 1'] + 
        $existing_grammar_marks['Assignment 2'] + 
        $existing_grammar_marks['Extra 1'] + 
        $existing_grammar_marks['Extra 2']
    ); ?></td>
    <td><?php echo htmlspecialchars(
        $existing_vocab_marks['Mid term'] + 
        $existing_vocab_marks['End Sem Mark'] + 
        $existing_vocab_marks['Assignment 1'] + 
        $existing_vocab_marks['Assignment 2'] + 
        $existing_vocab_marks['Extra 1'] + 
        $existing_vocab_marks['Extra 2']
    ); ?></td>
    <td><?php echo htmlspecialchars(
        $existing_confidence_marks['Mid term'] + 
        $existing_confidence_marks['End Sem Mark'] + 
        $existing_confidence_marks['Assignment 1'] + 
        $existing_confidence_marks['Assignment 2'] + 
        $existing_confidence_marks['Extra 1'] + 
        $existing_confidence_marks['Extra 2']
    ); ?></td>
    <td><?php echo htmlspecialchars(
        $existing_extra1_marks['Mid term'] + 
        $existing_extra1_marks['End Sem Mark'] + 
        $existing_extra1_marks['Assignment 1'] + 
        $existing_extra1_marks['Assignment 2'] + 
        $existing_extra1_marks['Extra 1'] + 
        $existing_extra1_marks['Extra 2']
    ); ?></td>
    <td><?php echo htmlspecialchars(
        $existing_extra2_marks['Mid term'] + 
        $existing_extra2_marks['End Sem Mark'] + 
        $existing_extra2_marks['Assignment 1'] + 
        $existing_extra2_marks['Assignment 2'] + 
        $existing_extra2_marks['Extra 1'] + 
        $existing_extra2_marks['Extra 2']
    ); ?></td>
</tr>

</tbody>
</table>
            <button type="submit">Save Marks</button>
</form>
</div>
</div>
<script>
         document.addEventListener("DOMContentLoaded", function() {
    // Check the mode from the URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const mode = urlParams.get('mode'); // This should either be 'view' or 'update'

    // Get all input fields
    const inputs = document.querySelectorAll('input[type="number"], input[type="text"]');
    
    if (mode === 'view') {
        // Make all inputs readonly if in 'view' mode
        inputs.forEach(input => {
            input.setAttribute('readonly', true);
        });
    }

    // Optionally, you can show a message if the form is in 'view' mode
    if (mode === 'view') {
        const message = document.createElement('p');
        message.textContent = "You are viewing the marks (read-only mode).";
        message.style.color = 'red';
        document.querySelector('.content').prepend(message);
    }

    // Optionally, you can add some basic form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        // If in 'view' mode, prevent form submission (no changes allowed)
        if (mode === 'view') {
            event.preventDefault();
            alert("You cannot submit the form in view mode.");
        }
    });
});
</script>
</body>
</html>
