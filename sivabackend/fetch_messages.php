<?php
session_start();
require 'C:/xampp/htdocs/asap backend/db.php';

$sender_id = $_GET['sender_id'] ?? null;
$receiver_id = $_GET['receiver_id'] ?? null;

if (!$sender_id || !$receiver_id) {
    echo json_encode(['success' => false, 'error' => 'Invalid sender or receiver']);
    exit;
}

$sql = "SELECT sender_id, receiver_id, message_content, attachment_path, timestamp 
        FROM messages 
        WHERE (sender_id = ? AND receiver_id = ?) 
           OR (sender_id = ? AND receiver_id = ?) 
        ORDER BY timestamp ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $sender_id, $receiver_id, $receiver_id, $sender_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode(['success' => true, 'messages' => $messages]);
?>
