<?php
// Include database connection file
include "C:\Users\USER\Desktop\Minor-Project-ASAP\Frontend\TEACHER ORGINAL"; // Update the path as needed

// Initialize variables
$currentUser = $_SESSION['user_id'] ?? ''; // Assumes user is logged in and session has user_id
$selectedUser = $_GET['user_id'] ?? ''; // Get selected user_id from URL or request
$errors = [];

// Fetch user list for the sidebar
$users = [];
$userStmt = $conn->prepare("SELECT user_id, username FROM users WHERE user_id != ?");
$userStmt->bind_param("s", $currentUser);
$userStmt->execute();
$userResult = $userStmt->get_result();
while ($row = $userResult->fetch_assoc()) {
    $users[] = $row;
}
$userStmt->close();

// Fetch chat messages between current user and selected user
$messages = [];
if ($selectedUser) {
    $messageStmt = $conn->prepare("SELECT sender_id, message, timestamp FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY timestamp");
    $messageStmt->bind_param("ssss", $currentUser, $selectedUser, $selectedUser, $currentUser);
    $messageStmt->execute();
    $messageResult = $messageStmt->get_result();
    while ($row = $messageResult->fetch_assoc()) {
        $messages[] = $row;
    }
    $messageStmt->close();
}

// Handle new message submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_message'])) {
    $newMessage = $_POST['new_message'];
    
    // Insert the new message
    $insertStmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message, timestamp, status) VALUES (?, ?, ?, NOW(), 'sent')");
    $insertStmt->bind_param("sss", $currentUser, $selectedUser, $newMessage);
    if ($insertStmt->execute()) {
        // Redirect to the same page to clear the form input
        header("Location: chat.php?user_id=" . urlencode($selectedUser));
        exit();
    } else {
        $errors['send'] = "Failed to send message.";
    }
    $insertStmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat System</title>
    <link rel="stylesheet" href="chat.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="chat-container">
        <!-- Sidebar for users -->
        <div class="user-sidebar">
            <h3>Users</h3>
            <div class="users-list">
                <?php foreach ($users as $user): ?>
                    <div class="user">
                        <a href="chat.php?user_id=<?php echo htmlspecialchars($user['user_id']); ?>">
                            <?php echo htmlspecialchars($user['username']); ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Chat area -->
        <div class="chat-area">
            <div class="chat-header">
                <h2>Chat with <?php echo htmlspecialchars($selectedUser); ?></h2>
            </div>
            <div class="chat-messages" id="chat-messages">
                <?php foreach ($messages as $msg): ?>
                    <div class="message <?php echo $msg['sender_id'] === $currentUser ? 'sent' : 'received'; ?>">
                        <div class="message-text">
                            <?php echo htmlspecialchars($msg['message']); ?>
                        </div>
                        <div class="message-time">
                            <?php echo htmlspecialchars(date("H:i", strtotime($msg['timestamp']))); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="chat-input-area">
                <form action="chat.php?user_id=<?php echo urlencode($selectedUser); ?>" method="POST">
                    <input type="text" name="new_message" placeholder="Type a message..." required>
                    <button type="submit">Send</button>
                </form>
                <p class="error"><?php echo isset($errors['send']) ? $errors['send'] : ''; ?></p>
            </div>
        </div>
    </div>
</body>
</html>
