
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
                   <th>total<th>
                    <th>dyam</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Mid Term</td>
                    <td><input type="number" name="midterm_bl_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Mid Term']['Body Language']); ?>"></td>
                    <td><input type="number" name="midterm_grammar_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Mid Term']['Grammar']); ?>"></td>
                    <td><input type="number" name="midterm_vocab_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Mid Term']['Vocabulary']); ?>"></td>
                    <td><input type="number" name="midterm_confidence_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Mid Term']['Confidence']); ?>"></td>
                    <td><input type="number" name="midterm_extra1_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Mid Term']['Extra 1']); ?>"></td>
                    <td><input type="number" name="midterm_extra2_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Mid Term']['Extra 2']); ?>"></td>
                    <td><?php echo htmlspecialchars(array_sum($existing_softskill_marks['Mid Term'])); ?></td>
                    <td><input type="text" name="midterm_remark_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Mid Term']['Remark']); ?>"></td>
                </tr>
                <tr>
                    <td>End Sem</td>
                    <td><input type="number" name="endsem_bl_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['End Sem']['Body Language']); ?>"></td>
                    <td><input type="number" name="endsem_grammar_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['End Sem']['Grammar']); ?>"></td>
                    <td><input type="number" name="endsem_vocab_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['End Sem']['Vocabulary']); ?>"></td>
                    <td><input type="number" name="endsem_confidence_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['End Sem']['Confidence']); ?>"></td>
                    <td><input type="number" name="endsem_extra1_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['End Sem']['Extra 1']); ?>"></td>
                    <td><input type="number" name="endsem_extra2_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['End Sem']['Extra 2']); ?>"></td>
                    <td><?php echo htmlspecialchars(array_sum($existing_softskill_marks['End Sem'])); ?></td>
                    <td><input type="text" name="endsem_remark_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['End Sem']['Remark']); ?>"></td>
                </tr>
                <tr>
                    <td>Assignment 1</td>
                    <td><input type="number" name="assign1_bl_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Assignment 1']['Body Language']); ?>"></td>
                    <td><input type="number" name="assign1_grammar_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Assignment 1']['Grammar']); ?>"></td>
                    <td><input type="number" name="assign1_vocab_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Assignment 1']['Vocabulary']); ?>"></td>
                    <td><input type="number" name="assign1_confidence_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Assignment 1']['Confidence']); ?>"></td>
                    <td><input type="number" name="assign1_extra1_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Assignment 1']['Extra 1']); ?>"></td>
                    <td><input type="number" name="assign1_extra2_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Assignment 1']['Extra 2']); ?>"></td>
                    <td><?php echo htmlspecialchars(array_sum($existing_softskill_marks['Assignment 1'])); ?></td>
                    <td><input type="text" name="assign1_remark_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Assignment 1']['Remark']); ?>"></td>
                </tr>
                <tr>
                    <td>Assignment 2</td>
                    <td><input type="number" name="assign2_bl_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Assignment 2']['Body Language']); ?>"></td>
                    <td><input type="number" name="assign2_grammar_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Assignment 2']['Grammar']); ?>"></td>
                    <td><input type="number" name="assign2_vocab_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Assignment 2']['Vocabulary']); ?>"></td>
                    <td><input type="number" name="assign2_confidence_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Assignment 2']['Confidence']); ?>"></td>
                    <td><input type="number" name="assign2_extra1_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Assignment 2']['Extra 1']); ?>"></td>
                    <td><input type="number" name="assign2_extra2_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Assignment 2']['Extra 2']); ?>"></td>
                    <td><?php echo htmlspecialchars(array_sum($existing_softskill_marks['Assignment 2'])); ?></td>
                    <td><input type="text" name="assign2_remark_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Assignment 2']['Remark']); ?>"></td>
                </tr>
                <tr>
                    <td>Extra 1</td>
                    <td><input type="number" name="extra1_bl_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Extra 1']['Body Language']); ?>"></td>
                    <td><input type="number" name="extra1_grammar_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Extra 1']['Grammar']); ?>"></td>
                    <td><input type="number" name="extra1_vocab_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Extra 1']['Vocabulary']); ?>"></td>
                    <td><input type="number" name="extra1_confidence_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Extra 1']['Confidence']); ?>"></td>
                    <td><input type="number" name="extra1_extra1_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Extra 1']['Extra 1']); ?>"></td>
                    <td><input type="number" name="extra1_extra2_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Extra 1']['Extra 2']); ?>"></td>
                    <td><?php echo htmlspecialchars(array_sum($existing_softskill_marks['Extra 1'])); ?></td>
                    <td><input type="text" name="extra1_remark_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Extra 1']['Remark']); ?>"></td>
                </tr>
                <tr>
                    <td>Extra 2</td>
                    <td><input type="number" name="extra2_bl_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Extra 2']['Body Language']); ?>"></td>
                    <td><input type="number" name="extra2_grammar_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Extra 2']['Grammar']); ?>"></td>
                    <td><input type="number" name="extra2_vocab_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Extra 2']['Vocabulary']); ?>"></td>
                    <td><input type="number" name="extra2_confidence_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Extra 2']['Confidence']); ?>"></td>
                    <td><input type="number" name="extra2_extra1_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Extra 2']['Extra 1']); ?>"></td>
                    <td><input type="number" name="extra2_extra2_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Extra 2']['Extra 2']); ?>"></td>
                    <td><?php echo htmlspecialchars(array_sum($existing_softskill_marks['Extra 2'])); ?></td>
                    <td><input type="text" name="extra2_remark_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Extra 2']['Remark']); ?>"></td>
                </tr>
                <tr>
    <td>Remark</td>
    <td><input type="text" name="remark_bl_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Body Language']['Remark']); ?>"></td>
    <td><input type="text" name="remark_grammar_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Grammar']['Remark']); ?>"></td>
    <td><input type="text" name="remark_vocab_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Vocabulary']['Remark']); ?>"></td>
    <td><input type="text" name="remark_confidence_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Confidence']['Remark']); ?>"></td>
    <td><input type="text" name="remark_extra1_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Extra 1']['Remark']); ?>"></td>
    <td><input type="text" name="remark_extra2_softskill" value="<?php echo htmlspecialchars($existing_softskill_marks['Extra 2']['Remark']); ?>"></td>
    <td></td>
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
