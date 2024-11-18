<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Form</title>
</head>
<body>

<?php
// Check if the 'success' parameter is set in the URL
if (isset($_GET['success']) && $_GET['success'] == 'true')      {
    echo "<p>Thank you for your feedback!</p>";
    }
?>

<!-- Your feedback form here -->
<form action="submit_feedback.php" method="POST">
    <textarea name="feedback" placeholder="Your feedback..." required></textarea><br>
    <button type="submit">Submit</button>
</form>

</body>
</html>
