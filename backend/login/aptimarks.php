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

<!-- HTML code remains the same -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marks Management</title>
    <link rel="stylesheet" href="teacher.css"> <!-- Same CSS for consistency -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome for icons -->
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar and other common content here -->
    </div>

    <div class="main-content">
        <h2>Manage Student Marks: <span id="class-name"></span><span id="student-name"><?= htmlspecialchars($studentName ?? '') ?></span></h2>
        <div>
            <label for="semester-select">Select Semester:</label>
            <select id="semester-select" name="semester" <?= !$canEdit ? 'disabled' : '' ?>>
                <option value="3" <?= $marksData['semester'] == 3 ? 'selected' : '' ?>>Sem 3</option>
                <option value="4" <?= $marksData['semester'] == 4 ? 'selected' : '' ?>>Sem 4</option>
                <option value="5" <?= $marksData['semester'] == 5 ? 'selected' : '' ?>>Sem 5</option>
            </select>
        </div>

        <form method="POST" action="">
            <table id="marks-table" border="1">
                <thead>
                    <tr>
                        <th>Evaluation</th>
                        <th>Quantitative</th>
                        <th>LR</th>
                        <th>Total</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody id="marks-body">
                    <tr>
                        <td>Mid Term</td>
                        <td><input type="number" class="marks-input" name="midterm-quant" value="<?= htmlspecialchars($marksData['midtermQuant']) ?>" <?= !$canEdit ? 'readonly' : '' ?> /></td>
                        <td><input type="number" class="marks-input" name="midterm-lr" value="<?= htmlspecialchars($marksData['midtermLR']) ?>" <?= !$canEdit ? 'readonly' : '' ?> /></td>
                        <td id="midterm-total"><?= htmlspecialchars($marksData['midtermQuant'] + $marksData['midtermLR']) ?></td>
                        <td><input type="text" name="midterm-remarks" value="<?= htmlspecialchars($marksData['midtermRemarks']) ?>" <?= !$canEdit ? 'readonly' : '' ?> /></td>
                    </tr>
                    <tr>
                        <td>End Sem</td>
                        <td><input type="number" class="marks-input" name="endsem-quant" value="<?= htmlspecialchars($marksData['endsemQuant']) ?>" <?= !$canEdit ? 'readonly' : '' ?> /></td>
                        <td><input type="number" class="marks-input" name="endsem-lr" value="<?= htmlspecialchars($marksData['endsemLR']) ?>" <?= !$canEdit ? 'readonly' : '' ?> /></td>
                        <td id="endsem-total"><?= htmlspecialchars($marksData['endsemQuant'] + $marksData['endsemLR']) ?></td>
                        <td><input type="text" name="endsem-remarks" value="<?= htmlspecialchars($marksData['endsemRemarks']) ?>" <?= !$canEdit ? 'readonly' : '' ?> /></td>
                    </tr>
                    <tr>
                        <td>Assignment 1</td>
                        <td><input type="number" class="marks-input" name="assign1-quant" value="<?= htmlspecialchars($marksData['assign1Quant']) ?>" <?= !$canEdit ? 'readonly' : '' ?> /></td>
                        <td><input type="number" class="marks-input" name="assign1-lr" value="<?= htmlspecialchars($marksData['assign1LR']) ?>" <?= !$canEdit ? 'readonly' : '' ?> /></td>
                        <td id="assign1-total"><?= htmlspecialchars($marksData['assign1Quant'] + $marksData['assign1LR']) ?></td>
                        <td><input type="text" name="assign1-remarks" value="<?= htmlspecialchars($marksData['assign1Remarks']) ?>" <?= !$canEdit ? 'readonly' : '' ?> /></td>
                    </tr>
                    <tr>
                        <td>Assignment 2</td>
                        <td><input type="number" class="marks-input" name="assign2-quant" value="<?= htmlspecialchars($marksData['assign2Quant']) ?>" <?= !$canEdit ? 'readonly' : '' ?> /></td>
                        <td><input type="number" class="marks-input" name="assign2-lr" value="<?= htmlspecialchars($marksData['assign2LR']) ?>" <?= !$canEdit ? 'readonly' : '' ?> /></td>
                        <td id="assign2-total"><?= htmlspecialchars($marksData['assign2Quant'] + $marksData['assign2LR']) ?></td>
                        <td><input type="text" name="assign2-remarks" value="<?= htmlspecialchars($marksData['assign2Remarks']) ?>" <?= !$canEdit ? 'readonly' : '' ?> /></td>
                    </tr>
                    <tr>
                        <td>Extra Session 1</td>
                        <td><input type="number" class="marks-input" name="extra1-quant" value="<?= htmlspecialchars($marksData['extra1Quant']) ?>" <?= !$canEdit ? 'readonly' : '' ?> /></td>
                        <td><input type="number" class="marks-input" name="extra1-lr" value="<?= htmlspecialchars($marksData['extra1LR']) ?>" <?= !$canEdit ? 'readonly' : '' ?> /></td>
                        <td id="extra1-total"><?= htmlspecialchars($marksData['extra1Quant'] + $marksData['extra1LR']) ?></td>
                        <td><input type="text" name="extra1-remarks" value="<?= htmlspecialchars($marksData['extra1Remarks']) ?>" <?= !$canEdit ? 'readonly' : '' ?> /></td>
                    </tr>
                    <tr>
                        <td>Extra Session 2</td>
                        <td><input type="number" class="marks-input" name="extra2-quant" value="<?= htmlspecialchars($marksData['extra2Quant']) ?>" <?= !$canEdit ? 'readonly' : '' ?> /></td>
                        <td><input type="number" class="marks-input" name="extra2-lr" value="<?= htmlspecialchars($marksData['extra2LR']) ?>" <?= !$canEdit ? 'readonly' : '' ?> /></td>
                        <td id="extra2-total"><?= htmlspecialchars($marksData['extra2Quant'] + $marksData['extra2LR']) ?></td>
                        <td><input type="text" name="extra2-remarks" value="<?= htmlspecialchars($marksData['extra2Remarks']) ?>" <?= !$canEdit ? 'readonly' : '' ?> /></td>
                    </tr>
                </tbody>
            </table>
            <input type="hidden" name="student_name" value="<?= htmlspecialchars($studentName) ?>" />
            <input type="hidden" name="semester" value="<?= htmlspecialchars($semester) ?>" />
            <?php if ($canEdit): ?>
                <input type="submit" value="Save Marks" />
            <?php endif; ?>
        </form>
    </div>
    <script src="teacher.js"></script>
    <script>
        document.querySelectorAll('.marks-input').forEach(input => {
            input.addEventListener('input', function () {
                const row = this.closest('tr');
                const quant = parseFloat(row.querySelector('input[name$="-quant"]').value) || 0;
                const lr = parseFloat(row.querySelector('input[name$="-lr"]').value) || 0;
                const totalCell = row.querySelector('td:last-child');
                totalCell.textContent = quant + lr;
            });
        });
    </script>
</body>
</html>
