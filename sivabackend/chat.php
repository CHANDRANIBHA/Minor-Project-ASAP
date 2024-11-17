<?php
session_start();
require 'C:/xampp/htdocs/asap backend/db.php';

// Replace with your session logic
$currentUserId = $_SESSION['user_id']; // Example: The logged-in user's ID

// Fetch all users except the current user for the chat list
$stmt = $conn->prepare("SELECT user_id, user_name, profile_image FROM users WHERE user_id != ?");
$stmt->bind_param("s", $currentUserId);
$stmt->execute();
$result = $stmt->get_result();
$chatList = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Application</title>
    <link rel="stylesheet" href="chatcss.css">
</head>
<body>
    <div class="container">
        <!-- Chat List -->
        <div class="chat-list">
            <h2>Chat Users</h2>
            <ul id="userList">
                <!-- User list will be dynamically populated -->
            </ul>
        </div>

        <!-- Chat Area -->
        <div class="chat-area">
            <div class="chat-header">
                <h3 id="chatWith">Select a user to chat</h3>
            </div>
            <div id="chatMessages" class="chat-messages">
                <!-- Messages will be dynamically populated -->
            </div>

            <!-- Message Input Form -->
            <form id="messageForm" enctype="multipart/form-data">
                <input type="hidden" id="currentUserId" value="1"> <!-- Replace with logged-in user ID -->
                <input type="hidden" id="receiverId">
                <textarea id="messageInput" name="message_content" placeholder="Type a message..." required></textarea>
                <label for="attachment" class="attach-button">Attach</label>
                <input type="file" name="attachment" id="attachment">
                <button type="submit" id="sendButton">Send</button>
            </form>
        </div>
    </div>
    <script src="chatjs.js"></script>
</body>
</html>
