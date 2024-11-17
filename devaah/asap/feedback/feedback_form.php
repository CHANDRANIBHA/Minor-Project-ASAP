<?php
session_start(); // Start the session to track the user

// Check if the user is logged in (you may have some login validation elsewhere)
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}

// Database connection
$servername = "localhost";
$username = "root"; // Use your actual database username
$password = "admin"; // Use your actual database password
$dbname = "asap"; // Use your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the user_id from session (sanitize the input to avoid any unexpected issues)
$user_id = $_SESSION['user_id'];

// Make sure $user_id is a valid string (you can apply further validation/sanitization if necessary)
$user_id = htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8');

// Fetch the user_name from the database based on user_id
$sql = "SELECT user_name FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);

// Check if the prepare() call was successful
if ($stmt === false) {
    die('Error preparing statement: ' . $conn->error);
}

$stmt->bind_param("s", $user_id); // Use "s" for string data type
$stmt->execute();
$stmt->bind_result($user_name);
$stmt->fetch();
$stmt->close();

// If user_name is not found, handle the error (e.g., redirect to login page or show an error message)
if (!$user_name) {
    echo "User not found.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Page</title>
    <style>
         .body {
            font-family: Arial, sans-serif;
            background-color: #dfd3c3;
            margin: 0;
            padding: 0;
        }

        .main-content {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* Back Arrow Styling */
        .back-arrow {
            position: absolute;
            top: 15px;
            left: 20px;
            font-size: 24px;
            color: #dfd3c3;
            cursor: pointer;
            text-decoration: none;
        }

        .feedback-container {
            background-color: #8d493a;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: #fff;
            text-align: center;
            position: relative; /* Ensure the arrow is positioned relative to this container */
        }

        h2 {
            color: #fff;
            margin-bottom: 15px;
        }

        textarea {
            width: 100%;
            padding: 15px; /* Increased padding for better typing space */
            border-radius: 4px;
            border: 1px solid #fff;
            background-color: #f4f4f4;
            color: #333;
            resize: none;
            font-size: 16px; /* Ensures the text inside is legible */
            box-sizing: border-box; /* Ensures padding doesn't affect overall width */
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #dfd3c3;
            color: #8d493a;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        button:hover {
            background-color: #d1c2b8;
        }

        .feedback-box {
            display: none;
            margin-top: 20px;
            padding: 15px;
            background-color: #8d493a;
            color: #fff;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .feedback-box p {
            margin: 0;
        }

    </style>
</head>
<body>

<div class="main-content">
    <h2>Share Your Thoughts</h2>
    <form action="submit_feedback.php" method="POST">
        <label for="user_name">Your Name:</label><br>
        <input type="text" id="user_name" name="user_name" value="<?php echo htmlspecialchars($user_name); ?>" readonly><br><br>
        
        <label for="feedback">Feedback:</label><br>
        <textarea id="feedback" name="feedback" rows="10" required></textarea><br><br>
        
        <button type="submit">Submit Feedback</button>
    </form>

    <?php
    if (isset($_GET['success']) && $_GET['success'] == 'true') {
        echo "<p>Thank you for your feedback!</p>";
    }
    ?>

</div>

</body>
</html>