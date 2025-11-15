<?php
require 'db.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? null;
$type = $_POST['type'] ?? null;
$id = intval($_POST['id'] ?? 0);

if (!$action || !$type || !$id) {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit;
}

if ($action === 'delete') {
    if ($type === 'user') {
        // Delete user and their listings
        $conn->query("DELETE FROM skill_listings WHERE user_id = $id");
        $result = $conn->query("DELETE FROM users WHERE id = $id");
    } elseif ($type === 'listing') {
        $result = $conn->query("DELETE FROM skill_listings WHERE id = $id");
    }

    echo json_encode(['success' => (bool)$result]);
    exit;
}

if ($action === 'toggle') {
    if ($type === 'user') {
        $conn->query("UPDATE users SET is_active = NOT is_active WHERE id = $id");
    } elseif ($type === 'listing') {
        $conn->query("UPDATE skill_listings SET is_active = NOT is_active WHERE id = $id");
    }

    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action']);
?>
