<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "Admin";
$dbname = "asap";

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


echo "<p>Class ID: $class_id</p>";
echo "<p>Semester: $semester</p>";
echo "<p>Subject ID: $subject_id</p>";
echo "<p>Student ID: $std_id</p>";


$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$marks = [
    'quantitative_midterm' => 25,
    'lr_midterm' => 25,
    'quantitative_endsem' => 100,
    'lr_endsem' => 100,
    'assignment1_quant' => 10,
    'assignment1_lr' => 10,
    'extra1_quant' => 10,
    'extra1_lr' => 10
  ];
  // Validate the submitted marks
foreach ($marks as $field => $max) {
    if (isset($_POST[$field]) && $_POST[$field] > $max) {
      echo "$field cannot exceed $max.";
      exit;
    }
  }

// Initialize marks data (to avoid undefined errors)
$marksData = [
    'midtermQuant' => 0,
    'midtermLR' => 0,
    'midtermRemarks' => "",
    'endsemQuant' => 0,
    'endsemLR' => 0,
    'endsemRemarks' => "",
    'assign1Quant' => 0,
    'assign1LR' => 0,
    'assign1Remarks' => "",
    'assign2Quant' => 0,
    'assign2LR' => 0,
    'assign2Remarks' => "",
    'extra1Quant' => 0,
    'extra1LR' => 0,
    'extra1Remarks' => "",
    'extra2Quant' => 0,
    'extra2LR' => 0,
    'extra2Remarks' => ""
];

// (Rest of your logic, data fetching, and form handling here...)
$canEdit = false;
$studentName = isset($_GET['student']) ? $_GET['student'] : ''; // Ensure student name is passed
$semester = isset($_GET['semester']) ? $_GET['semester'] : 3; // Default to semester 3 if not passed

if (isset($_GET['source'])) {
    $source = $_GET['source'];
    if ($source === 'update_aptitude') {
        $canEdit = true; // User can add marks
    } elseif ($source === 'view_aptitude') {
        // User can only view marks
        // Fetch saved marks data if needed (for pre-filling)
        
    }
}

// Handle form submission for adding marks
if ($canEdit && $_SERVER["REQUEST_METHOD"] == "POST") {
    $studentName = $_POST['student_name'];
    $semester = $_POST['semester'];

    // Retrieve marks from the form
    $marksData['midtermQuant'] = $_POST['midterm-quant'];
    $marksData['midtermLR'] = $_POST['midterm-lr'];
    $marksData['midtermRemarks'] = $_POST['midterm-remarks'];
    $marksData['endsemQuant'] = $_POST['endsem-quant'];
    $marksData['endsemLR'] = $_POST['endsem-lr'];
    $marksData['endsemRemarks'] = $_POST['endsem-remarks'];
    $marksData['assign1Quant'] = $_POST['assign1-quant'];
    $marksData['assign1LR'] = $_POST['assign1-lr'];
    $marksData['assign1Remarks'] = $_POST['assign1-remarks'];
    $marksData['assign2Quant'] = $_POST['assign2-quant'];
    $marksData['assign2LR'] = $_POST['assign2-lr'];
    $marksData['assign2Remarks'] = $_POST['assign2-remarks'];
    $marksData['extra1Quant'] = $_POST['extra1-quant'];
    $marksData['extra1LR'] = $_POST['extra1-lr'];
    $marksData['extra1Remarks'] = $_POST['extra1-remarks'];
    $marksData['extra2Quant'] = $_POST['extra2-quant'];
    $marksData['extra2LR'] = $_POST['extra2-lr'];
    $marksData['extra2Remarks'] = $_POST['extra2-remarks'];

    $stmt->close();
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - Update Aptitude Marks</title>
    <link rel="stylesheet" href="teacher.css"> 

    <style>
        /* Basic styles for the body and layout */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
        }

        .dashboard {
            display: flex;
            width: 100%;
        }


        .sidebar {
            width: 250px;
            background-color: #8d493a;
            color: white;
            transition: width 0.3s;
            overflow: hidden;
        }

        .sidebar .profile {
            text-align: center;
            padding: 20px;
        }

        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }

        .menu {
            padding: 10px 0;
        }

        .menu ul {
            list-style-type: none;
            padding: 0;
        }

        .menu li {
            padding: 15px 20px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .menu li:hover {
            background-color: #dfd3c3;
            color: black;
        }

        .menu-icon {
            position: absolute;
            top: 20px;
            left: 10px;
            background: #d0b8a8;
            border: none;
            color: white;
            font-size: 20px;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            z-index: 10;
        }

        .menu-icon:hover {
            background: #8d493a;
        }


        .main-content {
            flex-grow: 1;
            padding: 20px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none; /* Hide by default */
    position: absolute;
    background-color: #d0b8a8;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}

.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover {
    background-color: #8d493a;
}

        .semester-selection {
            margin-bottom: 20px;
        }

        .semester-selection label {
            margin-right: 10px;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        input {
            width: 100%;
            text-align: right;
        }

        tfoot td {
            font-weight: bold;
        }
    </style>
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

<script>

    // Toggle Sidebar Menu
    document.getElementById('menuToggle').addEventListener('click', function () {
            const sidebar = document.getElementById('sidebar');
            const isOpen = sidebar.style.width === '250px';
            sidebar.style.width = isOpen ? '0' : '250px';
            this.querySelector('i').classList.toggle('fa-bars', isOpen);
            this.querySelector('i').classList.toggle('fa-times', !isOpen);
        });

    // JS to calculate totals
   const calculateTotal = () => {
    let grandQuantTotal = 0;
    let grandLRTotal = 0;
    let grandTotal = 0;

    document.querySelectorAll('tbody tr').forEach(row => {
        const quantInput = row.querySelector('input[data-type="quant"]');
        const lrInput = row.querySelector('input[data-type="lr"]');
        const maxQuant = parseFloat(quantInput.getAttribute('max'));
        const maxLR = parseFloat(lrInput.getAttribute('max'));

        let quant = parseFloat(quantInput.value) || 0;
        let lr = parseFloat(lrInput.value) || 0;

        // Enforce max value for Quantitative and LR
        if (quant > maxQuant) quant = maxQuant;
        if (lr > maxLR) lr = maxLR;

        quantInput.value = quant;
        lrInput.value = lr;

        const rowTotal = quant + lr;
        row.querySelector('.row-total').innerText = rowTotal;

        grandQuantTotal += quant;
        grandLRTotal += lr;
        grandTotal += rowTotal;
    });

    document.getElementById('quant-grand-total').innerText = grandQuantTotal;
    document.getElementById('lr-grand-total').innerText = grandLRTotal;
    document.getElementById('grand-total').innerText = grandTotal;
};

document.querySelectorAll('.marks-input').forEach(input => {
    input.addEventListener('input', calculateTotal);
});

calculateTotal();

</script>

</body>
</html>
