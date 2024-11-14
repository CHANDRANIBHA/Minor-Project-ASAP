<?php
session_start();

// Enable error reporting
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

// Database connection setup
require_once __DIR__ . '/../db.php'; 

// Debug output
echo "Class ID: $class_id | Semester: $semester | Subject ID: $subject_id | Student ID: $std_id | Mode: $mode <br>";

// Prepare the SQL query
$query = "
    SELECT u.user_id, u.user_name 
    FROM students_tbl s
    JOIN class_tbl c ON s.class_id = c.class_id
    JOIN users u ON s.user_id = u.user_id
    WHERE s.class_id = ? AND c.sem = ? AND u.user_id = ?
";

$stmt = $conn->prepare($query);

if ($stmt === false) {
    echo "Error preparing the query: " . $conn->error;
    exit;
}

$stmt->bind_param("iis", $class_id, $semester, $std_id);
$stmt->execute();

// Check if the query executed successfully
if ($stmt->error) {
    echo "Error executing query: " . $stmt->error;
    exit;
} else {
    // Get the result
    $result = $stmt->get_result();
    $students = [];
    
    // Fetch results
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    // Output the result count for debugging
    echo "Students found: " . count($students) . "<br>";
    
    if (empty($students)) {
        echo "No students found for the provided criteria.";
        exit;
    }
}

// Close the prepared statement
$stmt->close();
?>


<?php
session_start();

// Include the db.php file using the correct relative path
require_once __DIR__ . '/../db.php';

$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'N/A'; // Default to 'N/A' if not set

// Retrieve class_id and semester from the URL
$class_id = isset($_GET['class_id']) ? (int)$_GET['class_id'] : 0;
$semester = isset($_GET['semester']) ? (int)$_GET['semester'] : 0;
$subject_id = isset($_GET['subject_id']) ? (int)$_GET['subject_id'] : 0;
$std_id = isset($_GET['std_id']) ? (int)$_GET['std_id'] : 0; // Optional, use only if selecting a specific student

// echo "Class ID: " . $class_id . " | Semester: " . $semester;

// Initialize an empty array to hold the student data
$students = [];

// Check if class_id and semester are valid
if ($class_id && $semester) {
    // Prepare the SQL query
    $stmt = $conn->prepare("SELECT u.user_id, u.user_name 
                            FROM students_tbl s
                            JOIN class_tbl c ON s.class_id = c.class_id
                            JOIN users u ON s.user_id = u.user_id
                            WHERE s.class_id = ? AND c.sem = ?");

    if ($stmt === false) {
        // Query preparation failed, show error
        echo "Error preparing the query: " . $conn->error;
    } else {
        // Bind the parameters and execute the query
        $stmt->bind_param("ii", $class_id, $semester);
        $stmt->execute();

        // Check for execution errors
        if ($stmt->error) {
            echo "Error executing query: " . $stmt->error;
        } else {
            // Get the result and fetch students
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $students[] = $row;
            }
        }

        // Close the prepared statement
        $stmt->close();
    }
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

        <!-- This is your marks table (simplified for the example) -->
        <form action="aptimarks.php" method="POST" id="marksForm" onsubmit="collectTableData(); return false;">
        <input type="hidden" name="marksData" id="marksData">
   


    <!-- Your marks table goes here -->
    <table>
        <thead>
            <tr>
                <th>Evaluation</th>
                <th>Quantitative</th>
                <th>LR</th>
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

    <button id="save-button" type="button">Save</button>
    </form>

    </div>
</div>
    <script src="aptimarks1.js"></script>
    <script>
        // Function to toggle the sidebar menu
document.getElementById('menuToggle').addEventListener('click', function () {
    const sidebar = document.getElementById('sidebar');
    const isOpen = sidebar.style.width === '250px';
    sidebar.style.width = isOpen ? '0' : '250px';
    this.querySelector('i').classList.toggle('fa-bars', isOpen);
    this.querySelector('i').classList.toggle('fa-times', !isOpen);
});

// Function to calculate totals for the marks table
const calculateTotal = () => {
    let grandQuantTotal = 0;
    let grandLRTotal = 0;
    let grandTotal = 0;

    // Iterate through each row and calculate totals
    document.querySelectorAll('tbody tr').forEach(row => {
        const quantInput = row.querySelector('input[data-type="quant"]');
        const lrInput = row.querySelector('input[data-type="lr"]');
        const maxQuant = parseFloat(quantInput.getAttribute('max'));
        const maxLR = parseFloat(lrInput.getAttribute('max'));

        let quant = parseFloat(quantInput.value) || 0;
        let lr = parseFloat(lrInput.value) || 0;

        // Enforce max value for Quantitative and LR
        quant = Math.min(quant, maxQuant);
        lr = Math.min(lr, maxLR);

        quantInput.value = quant;
        lrInput.value = lr;

        const rowTotal = quant + lr;
        row.querySelector('.row-total').innerText = rowTotal;

        grandQuantTotal += quant;
        grandLRTotal += lr;
        grandTotal += rowTotal;
    });

    // Update the grand total fields
    document.getElementById('quant-grand-total').innerText = grandQuantTotal;
    document.getElementById('lr-grand-total').innerText = grandLRTotal;
    document.getElementById('grand-total').innerText = grandTotal;
};

// Event listener for recalculating totals when input values change
document.querySelectorAll('.marks-input').forEach(input => {
    input.addEventListener('input', calculateTotal);
});

// Initial calculation of totals
calculateTotal();

// Function to collect all data from the marks table
// Modify collectTableData function in aptimarks.js
// Event listener for the Save button
document.querySelector("#save-button").addEventListener("click", function(event) {
    event.preventDefault();  // Prevent the form from submitting
    collectTableData();      // Call the function to collect and send data
});

// Function to collect all data from the marks table
function collectTableData() {
    const tableRows = document.querySelectorAll('table tbody tr');
    const marksData = [];

    tableRows.forEach((row) => {
        const evaluation = row.querySelector('input[name*="[evaluation]"]').value;
        const quantMark = parseFloat(row.querySelector('input[name*="[quant]"]').value) || 0;
        const lrMark = parseFloat(row.querySelector('input[name*="[lr]"]').value) || 0;
        const remarks = row.querySelector('input[name*="[remarks]"]').value;

        marksData.push({
            evaluation: evaluation,
            quant: quantMark,
            lr: lrMark,
            remarks: remarks,
        });
    });

    const marksDataInput = document.getElementById('marksData');
    marksDataInput.value = JSON.stringify(marksData);

    // Log the data to ensure it's correct
    console.log("marksData:", marksData);

    // Submit the form after populating the hidden field
    document.getElementById('marksForm').submit();
}

// Function to send collected marks data to the server using a POST form submission
function sendDataToForm(data) {
    console.log("Sending the following data to server:", data); // Log the data to verify it's being passed correctly

    const form = document.createElement("form");
    form.method = "POST";
    form.action = "aptimarks.php"; // PHP script where data will be processed

    // Create a hidden input to hold the JSON data
    const hiddenInput = document.createElement("input");
    hiddenInput.type = "hidden";
    hiddenInput.name = "marksData"; // The name of the input field
    hiddenInput.value = JSON.stringify(data); // The data to be sent

    // Append the hidden input to the form
    form.appendChild(hiddenInput);

    // Append the form to the body (it will not be visible)
    document.body.appendChild(form);

    // Submit the form
    form.submit();
}


// Attach the click event to the Save button
document.querySelector("#save-button").addEventListener("click", function(event) {
    event.preventDefault();  // Prevent the form from submitting
    collectTableData();      // Call the function to collect and send data
});
</script>
</body>
</html>
