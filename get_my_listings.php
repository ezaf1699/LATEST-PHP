<?php
session_start();
require 'db.php';

if (!isset($_SESSION['email'])) {
    echo json_encode([]);
    exit();
}

$email = $_SESSION['email'];

// Get user_id
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    echo json_encode([]);
    exit();
}

$user_id = $user['id'];

// Fetch all listings for that user
$sql = "SELECT l.*, c.name AS category_name 
        FROM skill_listings l
        JOIN skill_categories c ON l.category_id = c.id
        WHERE l.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$listings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode($listings);
?>
