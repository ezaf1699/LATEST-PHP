<?php
session_start();
require 'db.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}


$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'];


if (!isset($_GET['listing_id'])) {
    die("Invalid request.");
}

$listing_id = $_GET['listing_id'];


$stmt = $conn->prepare("SELECT user_id FROM skill_listings WHERE id = ?");
$stmt->bind_param("i", $listing_id);
$stmt->execute();
$result = $stmt->get_result();
$listing = $result->fetch_assoc();

if (!$listing) {
    die("Listing not found.");
}

$other_user_id = $listing['user_id'];


if ($other_user_id == $user_id) {
    die("You cannot connect with yourself.");
}

$stmt = $conn->prepare("
    SELECT id FROM matches
    WHERE (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)
");
$stmt->bind_param("iiii", $user_id, $other_user_id, $other_user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($match = $result->fetch_assoc()) {
    $match_id = $match['id'];
} else {

    $stmt = $conn->prepare("INSERT INTO matches (user1_id, user2_id, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $user_id, $other_user_id);
    $stmt->execute();
    $match_id = $stmt->insert_id;
}


$stmt = $conn->prepare("SELECT id FROM chat_rooms WHERE match_id = ?");
$stmt->bind_param("i", $match_id);
$stmt->execute();
$result = $stmt->get_result();

if ($chat_room = $result->fetch_assoc()) {
    $chat_room_id = $chat_room['id'];
} else {
  
    $stmt = $conn->prepare("INSERT INTO chat_rooms (match_id, created_at) VALUES (?, NOW())");
    $stmt->bind_param("i", $match_id);
    $stmt->execute();
    $chat_room_id = $stmt->insert_id;
}


header("Location: chat.php?room_id=" . $chat_room_id);
exit();
?>
