<?php
include "C:/xampp/htdocs/db.php";
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['user_id'], $data['subject_id'], $data['semester'], $data['marks'])) {
    echo json_encode(["success" => false, "message" => "Invalid data provided."]);
    exit;
}

$user_id = $data['user_id'];
$subject_id = $data['subject_id'];
$semester = $data['semester'];

// Prepare the insert query
$query = "INSERT INTO mark_tbl (user_id, subject_id, topic_id, evaluation_id, mark, sem_id) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);

foreach ($data['marks'] as $markData) {
    $evaluation_id = $markData['evaluation_id'];
    $quantitative_mark = $markData['quantitative'];
    $lr_mark = $markData['lr'];

    // Insert Quantitative mark
    $stmt->bind_param("siidii", $user_id, $subject_id, 1, $evaluation_id, $quantitative_mark, $semester);
    $stmt->execute();

    // Insert LR mark
    $stmt->bind_param("siidii", $user_id, $subject_id, 2, $evaluation_id, $lr_mark, $semester);
    $stmt->execute();
}

$stmt->close();
echo json_encode(["success" => true, "message" => "Marks saved successfully!"]);
