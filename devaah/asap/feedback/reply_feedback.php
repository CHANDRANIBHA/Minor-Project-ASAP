<?php
// reply_feedback.php (Admin reply to feedback)

// Check if reply is posted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $feedback_id = $_POST['feedback_id'];
    $reply = $_POST['reply'];

    // Database connection
    $servername = "localhost";
    $username = "root";  // Adjust as per your database setup
    $password = "admin";      // Adjust as per your database setup
    $dbname = "asap";

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Update the reply in the database
        $stmt = $pdo->prepare("UPDATE feedbacks SET reply = :reply WHERE id = :feedback_id");
        $stmt->bindParam(':reply', $reply);
        $stmt->bindParam(':feedback_id', $feedback_id);
        
        $stmt->execute();
        
        // Redirect back to view feedback page
        header("Location: view_feedback.php");
        exit();
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}
?>
