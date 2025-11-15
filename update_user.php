<?php
require 'db.php';
header('Content-Type: application/json');

$id = intval($_POST['id'] ?? 0);
$full_name = $_POST['full_name'] ?? '';
$location = $_POST['location'] ?? '';
$status = $_POST['status'] ?? 'active';

if (!$id) {
    echo json_encode(['error' => 'Missing ID']);
    exit();
}

// Ensure profile row exists — insert if missing
$stmt = $conn->prepare("SELECT id FROM profiles WHERE user_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    $ins = $conn->prepare("INSERT INTO profiles (user_id, full_name, location, profile_image, is_verified, status, created_at) VALUES (?, ?, ?, '', 0, ?, NOW())");
    $ins->bind_param("isss", $id, $full_name, $location, $status);
    $ok = $ins->execute();
    if ($ok) echo json_encode(['success' => true]);
    else echo json_encode(['error' => 'Failed to create profile']);
    exit();
}

// Otherwise update existing profile
$update = $conn->prepare("UPDATE profiles SET full_name = ?, location = ?, status = ? WHERE user_id = ?");
$update->bind_param("sssi", $full_name, $location, $status, $id);
if ($update->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to update profile']);
}
?>
