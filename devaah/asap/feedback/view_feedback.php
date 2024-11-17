<?php
// view_feedback.php (Admin view feedback page)

// Database connection
$servername = "localhost";
$username = "root";  // Adjust as per your database setup
$password = "admin";      // Adjust as per your database setup
$dbname = "asap";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Fetch feedback from the database
    $stmt = $pdo->prepare("SELECT * FROM feedbacks ORDER BY feedback_date DESC");
    $stmt->execute();
    $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback</title>
    <style>
        /* Add your styles here */
    </style>
</head>
<body>

<div class="main-content">
    <h2>Feedback from Users</h2>

    <table border="1">
        <thead>
            <tr>
                <th>Feedback ID</th>
                <th>User</th>
                <th>Feedback</th>
                <th>Date</th>
                <th>Reply</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($feedbacks as $feedback) : ?>
                <tr>
                    <td><?= $feedback['id'] ?></td>
                    <td><?= htmlspecialchars($feedback['user_name']) ?></td>
                    <td><?= nl2br(htmlspecialchars($feedback['feedback'])) ?></td>
                    <td><?= $feedback['feedback_date'] ?></td>
                    <td><?= $feedback['reply'] ? nl2br(htmlspecialchars($feedback['reply'])) : 'No reply yet' ?></td>
                    <td>
                        <form action="reply_feedback.php" method="POST">
                            <textarea name="reply" rows="4" placeholder="Type your reply..."></textarea><br>
                            <input type="hidden" name="feedback_id" value="<?= $feedback['id'] ?>">
                            <button type="submit">Submit Reply</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

</body>
</html>
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