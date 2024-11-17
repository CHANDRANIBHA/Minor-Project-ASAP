<?php
// submit_feedback.php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_name = $_POST['user_name'];
    $feedback = $_POST['feedback'];

    // Database connection
    $servername = "localhost";
    $username = "root"; // Adjust as per your database setup
    $password = "admin";     // Adjust as per your database setup
    $dbname = "asap";

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("INSERT INTO feedbacks (user_name, feedback) VALUES (:user_name, :feedback)");
        $stmt->bindParam(':user_name', $user_name);
        $stmt->bindParam(':feedback', $feedback);
        
        $stmt->execute();
        
        // Redirect to confirmation page or feedback view
        header("Location: feedback_form.php?success=true");
        exit();
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}
?>
