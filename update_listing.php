<?php
session_start();
require 'db.php';

if (!isset($_SESSION['email'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit();
}

$id = $_POST['id'] ?? null;
$skill_offered = $_POST['skill_offered'] ?? '';
$skill_wanted = $_POST['skill_wanted'] ?? '';
$category_id = $_POST['category_id'] ?? '';
$description = $_POST['description'] ?? '';

if (!$id || !$skill_offered || !$skill_wanted || !$category_id || !$description) {
    echo json_encode(["success" => false, "error" => "Incomplete data"]);
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

// Update only user’s own listing
$stmt = $conn->prepare("UPDATE skill_listings SET skill_offered=?, skill_wanted=?, category_id=?, description=? WHERE id=? AND user_id=?");
$stmt->bind_param("ssiiii", $skill_offered, $skill_wanted, $category_id, $description, $id, $user_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => "Failed to update listing"]);
}
?>
