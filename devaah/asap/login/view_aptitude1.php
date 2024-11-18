<?php
session_start();

// Include the db.php file
require_once __DIR__ . '/../db.php';

// Retrieve user details from session
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'N/A';

// Retrieve GET parameters
$class_id = isset($_GET['class_id']) ? (int)$_GET['class_id'] : 0;
$semester = isset($_GET['semester']) ? (int)$_GET['semester'] : 0;
$subject_id = isset($_GET['subject_id']) ? (int)$_GET['subject_id'] : 0;

// Function to fetch subject name
function getSubjectName($subject_id) {
    global $conn;

    $stmt = $conn->prepare("SELECT subject_name FROM subject_tbl WHERE subject_id = ?");
    $stmt->bind_param("i", $subject_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        return $row['subject_name'];
    }

    return 'Unknown Subject'; // Default fallback
}

// Fetch the subject name
$subject_name = getSubjectName($subject_id);

// Initialize an empty array for students
$students = [];

// Fetch students based on class_id and semester
if ($class_id && $semester) {
    $stmt = $conn->prepare("SELECT u.user_id, u.user_name 
                            FROM students_tbl s
                            JOIN class_tbl c ON s.class_id = c.class_id
                            JOIN users u ON s.user_id = u.user_id
                            WHERE s.class_id = ? AND c.sem = ?");
    if ($stmt) {
        $stmt->bind_param("ii", $class_id, $semester);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Marks: <?php echo htmlspecialchars($subject_name); ?></title>
    <link rel="stylesheet" href="teacher.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="dashboard">
        <div class="sidebar" id="sidebar">
            <div class="profile">
                <img src="profile-pic.png" alt="Profile Image" class="profile-img">
                <h3 id="username"><?php echo htmlspecialchars($user_name); ?></h3>
                <p id="reg-number">Reg No: <?php echo htmlspecialchars($user_id); ?></p>
            </div>
            <div id="menu" class="menu">
                <ul>
                    <li onclick="navigateTo('teacher_interface.php')">Home</li>
                    <li onclick="window.location.href='teacher_interface.php';">Dashboard</li>
                    <li onclick="window.location.href='reso.php';">Resources</li>
                    <li onclick="window.location.href='../feedback/feedback_form.php';">Feedback</li>
                    <li onclick="window.location.href='logout.php';">Logout</li>
                </ul>
            </div>
        </div>

        <button id="menuToggle" class="menu-icon">
            <i class="fas fa-bars"></i>
        </button>

        <div class="main-content">
            <h2>Update Students' Marks: <?php echo htmlspecialchars($subject_name); ?> (Class: <?php echo htmlspecialchars($class_id); ?>, Semester: <?php echo htmlspecialchars($semester); ?>)</h2>

            <div class="student-panels" id="student-panels-container">
    <?php if (count($students) > 0): ?>
        <?php foreach ($students as $student): ?>
            <div class="panel">
                <h3 onclick="studentClicked('<?php echo htmlspecialchars($student['user_id']); ?>')">
                    <?php echo htmlspecialchars($student['user_name']); ?> (Roll No: <?php echo htmlspecialchars($student['user_id']); ?>)
                </h3>

                <?php if ($subject_id == 2): ?>
                    <!-- Dropdown for Verbal Section -->
                    <select onchange="handleVerbalOption(this, '<?php echo htmlspecialchars($student['user_id']); ?>')">
                        <option value="">Choose Option</option>
                        <option value="writing">Writing</option>
                        <option value="speaking">Speaking</option>
                    </select>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No students found for this class and semester.</p>
    <?php endif; ?>
</div>

<script>
    function studentClicked(studentId) {
        const mode = "view";
        const classId = "<?php echo $class_id; ?>";
        const semester = "<?php echo $semester; ?>";
        const subjectId = "<?php echo $subject_id; ?>";
        const userId = studentId;

        // Determine the correct file based on subjectId
        let fileName;
        if (subjectId == 1) {
            fileName = "8.php";
        } else if (subjectId == 2) {
            // This case now handles dropdown actions
            return;
        } else {
            fileName = "softskills.php"; // Default to softskill if not 1 or 2
        }

        // Construct the URL with all parameters
        const url = `${fileName}?class_id=${classId}&semester=${semester}&subject_id=${subjectId}&user_id=${userId}&mode=${mode}`;
        window.location.href = url;
    }

    function handleVerbalOption(selectElement, studentId) {
        const mode = "view";
        const classId = "<?php echo $class_id; ?>";
        const semester = "<?php echo $semester; ?>";
        const subjectId = "<?php echo $subject_id; ?>";
        const userId = studentId;

        let fileName;
        switch (selectElement.value) {
            case "writing":
                fileName = "verbalmarks.php";
                break;
            case "speaking":
                fileName = "speaking_verbal.php";
                break;
            default:
                return; // Do nothing if "Choose Option" is selected
        }

        // Redirect to the selected file
        const url = `${fileName}?class_id=${classId}&semester=${semester}&subject_id=${subjectId}&user_id=${userId}&mode=${mode}`;
        window.location.href = url;
    }
</script>


    </div>
</body>
</html>
