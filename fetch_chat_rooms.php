<?php
session_start();
require 'db.php';

if (!isset($_SESSION['email'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Get logged-in user ID
$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$user_id = $user['id'];

// Fetch chat rooms where user is part of the match
$sql = "
    SELECT 
        cr.id AS chat_room_id,
        m.id AS match_id,
        u.id AS other_user_id,
        u.email AS other_user_email,
        p.profile_image
    FROM chat_rooms cr
    JOIN matches m ON cr.match_id = m.id
    JOIN users u ON 
        (CASE WHEN m.user1_id = ? THEN m.user2_id ELSE m.user1_id END) = u.id
    LEFT JOIN profiles p ON p.user_id = u.id
    WHERE m.user1_id = ? OR m.user2_id = ?
    ORDER BY cr.created_at DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $user_id, $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$rooms = [];
while ($row = $result->fetch_assoc()) {
    $rooms[] = $row;
}

header('Content-Type: application/json');
echo json_encode($rooms);
?>
