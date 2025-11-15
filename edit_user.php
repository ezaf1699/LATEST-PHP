<?php
require 'db.php';
header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'Missing user ID']);
    exit();
}

$id = intval($_GET['id']);

// Adjust table name if your profiles table is `profiles` (plural)
$sql = "SELECT u.id, u.email, p.full_name, p.location, p.status
        FROM users u
        LEFT JOIN profiles p ON u.id = p.user_id
        WHERE u.id = ? LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();

if (!$user) {
    echo json_encode(['error' => 'User not found']);
    exit();
}

echo json_encode($user);
?>
