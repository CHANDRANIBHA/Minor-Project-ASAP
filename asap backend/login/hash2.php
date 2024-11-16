<?php
// At the start of hash2.php
error_log(print_r($_POST, true));  // This will log the POST data to the PHP error log

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_GET['class_id'], $_GET['semester'], $_GET['subject_id'], $_GET['user_id'], $_GET['mode'])) {
    echo "Missing required parameters.";
    exit;
}

$class_id = $_GET['class_id'];
$semester = $_GET['semester'];
$subject_id = $_GET['subject_id'];
$mode = $_GET['mode'];

// std_id here represents user_id, so assign it to $user_id
$user_id = $_GET['user_id'];

// Check that subject_id is 1 (as per your page restriction)
if ($subject_id != 1) {
    echo "This page only displays data for Subject ID 1.";
    exit;
}

$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';

// Include the database connection
include "C:/xampp/htdocs/db.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Prepare the query using $user_id
$student_query = "
    SELECT u.user_name, u.user_id, s.std_id
    FROM students_tbl s
    JOIN users u ON s.user_id = u.user_id
    WHERE u.user_id = ?
";

$stmt = $conn->prepare($student_query);
$stmt->bind_param("s", $user_id); // Bind user_id as a string
$stmt->execute();
$student_result = $stmt->get_result();
$student = $student_result->fetch_assoc();
$stmt->close();

if (!$student) {
    echo "Student not found or invalid student ID.";
    exit;
}

$query = "SELECT topic_id, topic_name FROM topic_tbl WHERE topic_id IN (1, 2)";
$result = mysqli_query($conn, $query);
$topics = [];
while ($row = mysqli_fetch_assoc($result)) {
    $topics[$row['topic_id']] = $row['topic_name'];
}
$quantitative_topic = $topics[1] ?? 'Quantitative';
$lr_topic = $topics[2] ?? 'LR';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['student_id'], $input['subject_id'], $input['semester'], $input['marksData'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required data']);
        exit;
    }

    $student_id = $input['user_id'];
    $subject_id = $input['subject_id'];
    $semester = $input['semester'];
    $marksData = $input['marksData'];

    $query = "INSERT INTO mark_tbl (user_id, subject_id, evaluation_id, topic_id, mark) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    foreach ($marksData as $mark) {
        $evaluation_id = $mark['evaluation_id'];

        // Insert Quantitative marks
        $quant_mark = $mark['quant_marks'];
        $quant_topic_id = 1;  // Assuming Quantitative has topic_id = 1
        $stmt->bind_param("siiii", $student_id, $subject_id, $evaluation_id, $quant_topic_id, $quant_mark);
        if (!$stmt->execute()) {
            error_log("Error inserting Quantitative mark: " . $stmt->error);
            echo json_encode(['success' => false, 'message' => 'Failed to insert data']);
            exit;
        }

        // Insert LR marks
        $lr_mark = $mark['lr_marks'];
        $lr_topic_id = 2;  // Assuming LR has topic_id = 2
        $stmt->bind_param("siiii", $student_id, $subject_id, $evaluation_id, $lr_topic_id, $lr_mark);
        if (!$stmt->execute()) {
            error_log("Error inserting LR mark: " . $stmt->error);
            echo json_encode(['success' => false, 'message' => 'Failed to insert data']);
            exit;
        }
    }

    $stmt->close();
    echo json_encode(['success' => true, 'message' => 'Marks saved successfully']);
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

        </div>
        <div class="main-content">
            <h2>Update Marks for Student: <?php echo htmlspecialchars($student['user_name']); ?></h2>
            <div>
        <h3 id="semester-display">Semester: 5</h3>
            <label for="semester-select">Select Semester:</label>
            <select id="semester-select">
                <option value="3">Sem 3</option>
                <option value="4">Sem 4</option>
                <option value="5">Sem 5</option>
            </select>
        </div>
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
                <!-- Each row dynamically populated using JavaScript based on marksData -->
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
    
</tfoot>

        </table>
            <button id="save-button">Save Marks</button>
        </div>
    </div>
</body>
</html>

<script>
// Menu Toggle
document.getElementById('menuToggle').addEventListener('click', function() {
    const sidebar = document.getElementById('sidebar');
    const isOpen = sidebar.style.width === '250px';
    sidebar.style.width = isOpen ? '0' : '250px';
    this.querySelector('i').classList.toggle('fa-bars', isOpen); // Change icon to bars if open
    this.querySelector('i').classList.toggle('fa-times', !isOpen);
});
// Variables
const saveButton = document.getElementById('save-button');
const marksInputs = document.querySelectorAll('.marks-input');
const quantGrandTotal = document.getElementById('quant-grand-total');
const lrGrandTotal = document.getElementById('lr-grand-total');
const grandTotal = document.getElementById('grand-total');

// Function to calculate and update totals for each row and grand totals
function updateTotals() {
    let quantTotal = 0;
    let lrTotal = 0;
    let total = 0;

    // Loop through each row and calculate the totals
    document.querySelectorAll('#marks-body tr').forEach(row => {
        const quantInput = row.querySelector('.marks-input[id$="-quant"]');
        const lrInput = row.querySelector('.marks-input[id$="-lr"]');
        const rowTotal = row.querySelector('.row-total');

        // Get the values of Quantitative and LR marks
        const quantMarks = parseFloat(quantInput.value) || 0;
        const lrMarks = parseFloat(lrInput.value) || 0;

        // Calculate row total (Quant + LR)
        const rowSum = quantMarks + lrMarks;
        rowTotal.textContent = rowSum;

        // Update the grand totals
        quantTotal += quantMarks;
        lrTotal += lrMarks;
        total += rowSum;
    });

    // Update the grand totals displayed at the bottom
    quantGrandTotal.textContent = quantTotal;
    lrGrandTotal.textContent = lrTotal;
    grandTotal.textContent = total;
}

// Add event listeners for all marks inputs to update totals when changed
marksInputs.forEach(input => {
    input.addEventListener('input', updateTotals);
});

// Initially update the totals when the page loads
updateTotals();

// Save button click handler
saveButton.addEventListener('click', () => {
    const marksData = Array.from(document.querySelectorAll('#marks-body tr')).map(row => ({
        evaluation_id: row.getAttribute('data-evaluation-id'),
        quant_marks: row.querySelector('.marks-input[id$="-quant"]').value || 0,
        lr_marks: row.querySelector('.marks-input[id$="-lr"]').value || 0,
    }));

    fetch('hash2.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            student_id: "<?php echo $student['std_id']; ?>",
            subject_id: "<?php echo $subject_id; ?>",
            semester: "<?php echo $semester; ?>",
            marksData: marksData,
        }),
    })
    .then(response => response.json())
    .then(data => alert(data.message))
    .catch(error => console.error('Error:', error));
});
</script>
</body>
</html>
