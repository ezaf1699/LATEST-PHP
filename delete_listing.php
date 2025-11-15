<?php
session_start();
require 'db.php';

if (!isset($_SESSION['email'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    echo json_encode(["success" => false, "error" => "Missing ID"]);
    exit();
}

// Get user_id
$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$user_id = $user['id'] ?? null;

if (!$user_id) {
    echo json_encode(["success" => false, "error" => "User not found"]);
    exit();
}

// Delete only user’s own listing
$stmt = $conn->prepare("DELETE FROM skill_listings WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $user_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => "Failed to delete listing"]);
}
?>
