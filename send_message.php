<?php
session_start();
require 'db.php';

if (!isset($_SESSION['email'])) {
    http_response_code(403);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);
$chat_room_id = intval($data['chat_room_id'] ?? 0);
$message = trim($data['message'] ?? '');

if ($chat_room_id <= 0 || $message == '') {
    exit();
}

// Get sender ID
$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$sender_id = $user['id'];

// Insert message
$stmt = $conn->prepare("
    INSERT INTO chat_messages (chat_room_id, sender_id, message, sent_at)
    VALUES (?, ?, ?, NOW())
");
$stmt->bind_param("iis", $chat_room_id, $sender_id, $message);
$stmt->execute();

echo json_encode(['success' => true]);
?>
