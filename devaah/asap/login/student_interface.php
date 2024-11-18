<?php
session_start(); // Start the session

// Include database connection
require_once __DIR__ . '/../db.php';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user_name and user_id from the session
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '12345678';

// Fetch class_id based on user_id
$class_id = null;
$sql = "SELECT class_id FROM students_tbl WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $class_id = $row['class_id'];
} else {
    die("Error: Class ID not found for the given user ID.");
}
$stmt->close();

// Define subject IDs
$subject_ids = [
    'aptitude' => 1,
    'verbal' => 2,
    'softskills' => 3,
    'professional_training' => 4
];

// Define subject-specific pages
$subject_pages = [
    'aptitude' => 'aptimarks1.php',
    'verbal' => 'verbalmarks.php',
    'softskills' => 'softskills.php',
    'professional_training' => 'ptr.php'
];

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="teacher.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome for icons -->
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar Section -->
        <div class="sidebar">
            <div class="profile">
                <img src="asap.jpg" alt="Profile Image" class="profile-img">
                <h3 id="username"><?php echo htmlspecialchars($user_name); ?></h3>
                <p id="reg-number">Reg No: <?php echo htmlspecialchars($user_id); ?></p>
            </div>
            <ul class="menu">
                <li onclick="window.location.href='../home page/homepage.php';">Home</li>
                <li onclick="window.location.href='student_interface.php';">Dashboard</li>
                <li onclick="window.location.href='reso.php';">Resources</li>
                <li onclick="window.location.href='../feedback/feedback_form.php';">Feedback</li>
                <li onclick="logout()">Logout</li>
            </ul>
        </div>

        <!-- Main Content Area -->
        <div class="main-content">
            <div class="banner">
                <h1>Welcome, <span><?php echo htmlspecialchars($user_name); ?></span>!</h1>
                <p>We are excited to have you here!</p>
            </div>

            <!-- Panels Section -->
            <div class="panels-section">
                <!-- Aptitude Panel -->
                <div class="panel" id="aptitude-panel">
                    <h4>Aptitude</h4>
                    <select id="aptitude-semester">
                        <option value="3">Semester 3</option>
                        <option value="4">Semester 4</option>
                        <option value="5">Semester 5</option>
                    </select>
                    <button onclick="redirectToPage('aptitude')">Go</button>
                </div>

                <!-- Verbal Panel -->
                <div class="panel" id="verbal-panel">
                    <h4>Verbal</h4>
                    <select id="verbal-semester">
                        <option value="3">Semester 3</option>
                        <option value="4">Semester 4</option>
                        <option value="5">Semester 5</option>
                    </select>
                    <div id="verbal-options" style="display: none; margin-top: 10px;">
                        <select id="verbal-type">
                            <option value="">Choose</option>
                            <option value="writing">Writing</option>
                            <option value="speaking">Speaking</option>
                        </select>
                    </div>
                    <button onclick="redirectToPage('verbal')">Go</button>
                </div>

                <!-- Soft Skills Panel -->
                <div class="panel" id="softskills-panel">
                    <h4>Soft Skills</h4>
                    <select id="softskills-semester">
                        <option value="3">Semester 3</option>
                        <option value="4">Semester 4</option>
                        <option value="5">Semester 5</option>
                    </select>
                    <button onclick="redirectToPage('softskills')">Go</button>
                </div>

                <!-- Professional Training Panel -->
                <div class="panel" id="training-panel">
                    <h4>Professional Training</h4>
                    <select id="training-semester">
                        <option value="3">Semester 3</option>
                        <option value="4">Semester 4</option>
                        <option value="5">Semester 5</option>
                    </select>
                    <button onclick="redirectToPage('professional_training')">Go</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Predefined subject IDs and pages
        const subjectConfig = {
            'aptitude': { id: 1, page: 'aptimarks1.php' },
            'verbal': { id: 2, pages: { default: 'verbalmarks.php', writing: 'verbalmarks.php', speaking: 'speaking_verbal.php' } },
            'softskills': { id: 3, page: 'softskills.php' },
            'professional_training': { id: 4, page: 'ptr.php' }
        };

        // Show additional dropdown for Verbal
        const verbalDropdown = document.getElementById('verbal-options');
        document.getElementById('verbal-semester').addEventListener('change', () => {
            verbalDropdown.style.display = 'block';
        });

        // Function to handle redirection
        function redirectToPage(subject) {
            const userId = "<?php echo $user_id; ?>";
            const classId = "<?php echo $class_id; ?>";
            const subjectData = subjectConfig[subject];
            const subjectId = subjectData.id;

            // Get the selected semester
            const semester = document.getElementById(`${subject}-semester`).value;

            // Handle Verbal's specific logic
            if (subject === 'verbal') {
                const verbalType = document.getElementById('verbal-type').value || 'default';
                const subjectPage = subjectData.pages[verbalType];

                if (!subjectPage) {
                    alert("Please select a valid option for Verbal.");
                    return;
                }

                // Generate and redirect to Verbal-specific URL
                const url = `${subjectPage}?user_id=${userId}&subject_id=${subjectId}&class_id=${classId}&semester=${semester}&mode=view`;
                window.location.href = url;
                return;
            }

            // Default redirection for other subjects
            const subjectPage = subjectData.page;
            const url = `${subjectPage}?user_id=${userId}&subject_id=${subjectId}&class_id=${classId}&semester=${semester}&mode=view`;
            window.location.href = url;
        }
    </script>
</body>
</html>
