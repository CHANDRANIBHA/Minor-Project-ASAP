<?php
// Database connection
$servername = "localhost"; // Change if needed
$username = "root"; // Change if needed
$password = "Admin"; // Change if needed
$dbname = "asap"; // Change to your database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize marks data
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

// Check where the user came from
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

    // Insert or update marks in the database
    $sql = "INSERT INTO aptimarks (user_id, semester, midterm_quant, midterm_lr, midterm_remarks, endsem_quant, endsem_lr, endsem_remarks, assign1_quant, assign1_lr, assign1_remarks, assign2_quant, assign2_lr, assign2_remarks, extra1_quant, extra1_lr, extra1_remarks, extra2_quant, extra2_lr, extra2_remarks)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
            midterm_quant = ?, midterm_lr = ?, midterm_remarks = ?, endsem_quant = ?, endsem_lr = ?, endsem_remarks = ?, assign1_quant = ?, assign1_lr = ?, assign1_remarks = ?, assign2_quant = ?, assign2_lr = ?, assign2_remarks = ?, extra1_quant = ?, extra1_lr = ?, extra1_remarks = ?, extra2_quant = ?, extra2_lr = ?, extra2_remarks = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        'iisisiisiisiiisiiiiiiiiii',
        $studentName, $semester,
        $marksData['midtermQuant'], $marksData['midtermLR'], $marksData['midtermRemarks'],
        $marksData['endsemQuant'], $marksData['endsemLR'], $marksData['endsemRemarks'],
        $marksData['assign1Quant'], $marksData['assign1LR'], $marksData['assign1Remarks'],
        $marksData['assign2Quant'], $marksData['assign2LR'], $marksData['assign2Remarks'],
        $marksData['extra1Quant'], $marksData['extra1LR'], $marksData['extra1Remarks'],
        $marksData['extra2Quant'], $marksData['extra2LR'], $marksData['extra2Remarks'],
        $marksData['midtermQuant'], $marksData['midtermLR'], $marksData['midtermRemarks'],
        $marksData['endsemQuant'], $marksData['endsemLR'], $marksData['endsemRemarks'],
        $marksData['assign1Quant'], $marksData['assign1LR'], $marksData['assign1Remarks'],
        $marksData['assign2Quant'], $marksData['assign2LR'], $marksData['assign2Remarks'],
        $marksData['extra1Quant'], $marksData['extra1LR'], $marksData['extra1Remarks'],
        $marksData['extra2Quant'], $marksData['extra2LR'], $marksData['extra2Remarks']
    );

    if ($stmt->execute()) {
        echo "<script>alert('Marks saved successfully!');</script>";
    } else {
        echo "<script>alert('Error saving marks: " . $stmt->error . "');</script>";
    }

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
            background-color: #34495e;
            color: white;
            height: 100vh;
            padding: 20px 0;
        }

        .profile {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }

        .user-id {
            margin-top: 10px;
            font-size: 14px;
            color: #bdc3c7;
        }

        .menu {
            padding: 10px 0;
        }

        .menu ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .menu li {
            padding: 15px 20px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .menu li:hover {
            background-color: #1abc9c;
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
    <div class="sidebar">
        <div class="profile">
            <img src="profile.jpg" alt="Profile" class="profile-img">
            <h2>Teacher Name</h2>
            <div class="user-id">User ID: T12345</div>
        </div>
        <div class="menu">
            <ul>
                <li>HOME</li>
                <li>CHAT</li>
                <li>SESSION</li>
                <li>RESOURCES</li>
                <li>FAQ</li>
            </ul>
        </div>
    </div>

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
