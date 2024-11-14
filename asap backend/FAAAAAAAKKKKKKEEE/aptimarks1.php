<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "Admin";
$dbname = "asap"; // Include your database connection file

session_start();

// Ensure variables are defined by checking session or assigning default values
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'N/A';


// Retrieve class_id and semester from the URL
$class_id = isset($_GET['class_id']) ? (int)$_GET['class_id'] : 0;
$semester = isset($_GET['semester']) ? (int)$_GET['semester'] : 0;
$subject_id = isset($_GET['subject_id']) ? (int)$_GET['subject_id'] : 0;
$std_id = isset($_GET['std_id']) ? (int)$_GET['std_id'] : 0; // Student ID from the URL
$mode = isset($_GET['mode']) ? $_GET['mode'] : ''; // Mode (e.g., update)


$semester = isset($_GET['semester']) ? $_GET['semester'] : null;
$canEdit = false;

if (isset($_GET['source'])) {
    $source = $_GET['source'];
    if ($source === 'update_aptitude') {
        $canEdit = true; // User can add marks
    } elseif ($source === 'view_aptitude') {
        // User can only view marks
        // Fetch saved marks data if needed (for pre-filling)
        $query = "SELECT * FROM aptimarks WHERE user_id = ? AND semester = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('si', $studentName, $semester);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $marksData = $result->fetch_assoc();
        } else {
            echo "<script>alert('No marks updated.');</script>";
        }
        $stmt->close();
    }
}

// Handle form submission to insert or update marks
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $canEdit) {
    $evaluation_ids = $_POST['evaluation_id'];
    $quantitative_marks = $_POST['quantitative'];
    $lr_marks = $_POST['lr'];
    $remarks = $_POST['remarks'];

    $query = "INSERT INTO mark_tbl (std_id, subject_id, topic_id, evaluation_id, mark, remark)
              VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    foreach ($evaluation_ids as $key => $evaluation_id) {
        $topic_quantitative_id = 1; // Replace with the correct topic ID for Quantitative
        $topic_lr_id = 2; // Replace with the correct topic ID for LR

        // Insert Quantitative mark
        $stmt->bind_param('iiiiis', $student_id, $subject_id, $topic_quantitative_id, $evaluation_id, $quantitative_marks[$key], $remarks[$key]);
        $stmt->execute();

        // Insert LR mark
        $stmt->bind_param('iiiiis', $student_id, $subject_id, $topic_lr_id, $evaluation_id, $lr_marks[$key], $remarks[$key]);
        $stmt->execute();
    }
    $stmt->close();
    echo "<script>alert('Marks updated successfully!');</script>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Aptitude Marks</title>
    <link rel="stylesheet" href="aptimarks.css">
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

    <div class="main-content">
        <h1>Update Aptitude Marks</h1>
        <div class="semester-selection">
            <label for="semester">Semester:</label>
            <select id="semester">
                <option value="" disabled selected>Select semester</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </div>

        <form method="POST" action="">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Evaluation</th>
                            <th>Quantitative</th>
                            <th>LR</th>
                            <th>Total</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Render the table rows for evaluations (loop through your data)
                        $evaluations = ['Mid Term', 'End Sem Mark', 'Assignment 1', 'Assignment 2', 'Extra 1', 'Extra 2'];
                        foreach ($evaluations as $index => $evaluation) {
                            $quant = isset($aptitude_marks[$index]['quant']) ? $aptitude_marks[$index]['quant'] : 0;
                            $lr = isset($aptitude_marks[$index]['lr']) ? $aptitude_marks[$index]['lr'] : 0;
                            $remarks = isset($aptitude_marks[$index]['remarks']) ? $aptitude_marks[$index]['remarks'] : '';
                            echo "
                            <tr>
                                <td>$evaluation</td>
                                <td><input type='number' name='marksData[$index][quant]' class='marks-input' data-type='quant' value='$quant'></td>
                                <td><input type='number' name='marksData[$index][lr]' class='marks-input' data-type='lr' value='$lr'></td>
                                <td class='row-total'>".($quant + $lr)."</td>
                                <td><input type='text' name='marksData[$index][remarks]' value='$remarks'></td>
                                <input type='hidden' name='marksData[$index][evaluation]' value='$evaluation'>
                            </tr>";
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>Grand Total</td>
                            <td id="quant-grand-total">0</td>
                            <td id="lr-grand-total">0</td>
                            <td id="grand-total">0</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <button type="submit" id="save-button">Save</button>
        </form>
    </div>
</div>
    <script src="aptimarks.js"></script>
</body>
</html>
