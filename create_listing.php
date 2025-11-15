<?php
session_start();
require 'db.php';

if (!isset($_SESSION['email'])) {
    echo json_encode(["error" => "Not logged in"]);
    exit();
}

$email = $_SESSION['email'];

// Get user_id
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    echo json_encode(["error" => "User not found"]);
    exit();
}

$user_id = $user['id'];

// Get form data
$skill_offered = $_POST['skill_offered'] ?? '';
$skill_wanted = $_POST['skill_wanted'] ?? '';
$category_id = $_POST['category_id'] ?? '';
$description = $_POST['description'] ?? '';

if (!$skill_offered || !$skill_wanted || !$category_id || !$description) {
    echo json_encode(["error" => "Incomplete data"]);
    exit();
}

// Insert new listing
$stmt = $conn->prepare("INSERT INTO skill_listings (user_id, category_id, skill_offered, skill_wanted, description, is_active, created_at) VALUES (?, ?, ?, ?, ?, 1, NOW())");
$stmt->bind_param("iisss", $user_id, $category_id, $skill_offered, $skill_wanted, $description);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "Failed to create listing"]);
}
?>
