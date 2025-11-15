<?php
session_start();
require 'db.php';

if (!isset($_SESSION['email'])) {
    http_response_code(403);
    exit();
}

$chat_room_id = intval($_GET['room_id'] ?? 0);
if ($chat_room_id <= 0) {
    exit();
}

$sql = "
    SELECT cm.id, cm.message, cm.sent_at, u.email AS sender_email
    FROM chat_messages cm
    JOIN users u ON cm.sender_id = u.id
    WHERE cm.chat_room_id = ?
    ORDER BY cm.sent_at ASC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $chat_room_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

header('Content-Type: application/json');
echo json_encode($messages);
?>
