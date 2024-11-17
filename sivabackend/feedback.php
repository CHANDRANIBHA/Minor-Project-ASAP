<?php
session_start();
require 'C:/xampp/htdocs/asap backend/db.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User ID is not set in session.");
}
 {
    header('Location: login.php');
    exit;
}

// Handle feedback form submission
$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $feedback_text = $_POST['feedback'];

    // Insert feedback into feedback_tbl
    $stmt = $conn->prepare("INSERT INTO feedback_tbl (user_id, feedback_text) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $feedback_text);
    if ($stmt->execute()) {
        $message = "Thank you for your feedback!";
    } else {
        $message = "Failed to submit feedback. Please try again.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Page</title>
    <style>
        /* Styling here */  body {
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

    <div class="feedback-container">
        <!-- Back Arrow -->
        <span class="back-arrow" onclick="goBack()">&#8592; </span>

        <h2>Share your thoughts</h2>

        <?php if ($message): ?>
            <div id="feedbackBox" class="feedback-box">
                <p><?php echo $message; ?></p>
            </div>
        <?php endif; ?>

        <form method="POST">
            <textarea id="feedbackText" name="feedback" placeholder="Enter here..." rows="6" required></textarea>
            <button type="submit">SUBMIT</button>
        </form>
    </div>
</div>

<script>
    function goBack() {
        window.history.back();
    }
</script>

</body>
</html>
