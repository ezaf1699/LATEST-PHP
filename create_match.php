<?php
session_start();
require 'db.php';

// Ensure logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Get current user ID
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();
$currentUser = $stmt->get_result()->fetch_assoc();
$currentUserId = $currentUser['id'];

// Get target user
if (!isset($_GET['user_id'])) {
    die("Invalid request");
}
$targetUserId = (int)$_GET['user_id'];

if ($currentUserId === $targetUserId) {
    header("Location: browse.php");
    exit();
}

// 1️⃣ Check if match already exists
$sql = "SELECT id FROM matches 
        WHERE (user1_id = ? AND user2_id = ?) 
           OR (user1_id = ? AND user2_id = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $currentUserId, $targetUserId, $targetUserId, $currentUserId);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    $matchId = $res->fetch_assoc()['id'];
} else {
    // 2️⃣ Create match
    $stmt = $conn->prepare("INSERT INTO matches (user1_id, user2_id, status, created_at) VALUES (?, ?, 'accepted', NOW())");
    $stmt->bind_param("ii", $currentUserId, $targetUserId);
    $stmt->execute();
    $matchId = $stmt->insert_id;

    // 3️⃣ Create chat room for this match
    $stmt = $conn->prepare("INSERT INTO chat_rooms (match_id, created_at) VALUES (?, NOW())");
    $stmt->bind_param("i", $matchId);
    $stmt->execute();
}

// 4️⃣ Find chat room
$stmt = $conn->prepare("SELECT id FROM chat_rooms WHERE match_id = ?");
$stmt->bind_param("i", $matchId);
$stmt->execute();
$room = $stmt->get_result()->fetch_assoc();
$chatRoomId = $room['id'];

// 5️⃣ Redirect to chat page
header("Location: chat.php?room_id=" . $chatRoomId);
exit();
?>
