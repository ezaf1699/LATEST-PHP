<?php
require 'db.php';

if (!isset($_GET['id'])) {
    echo json_encode(["error" => "No user ID provided"]);
    exit;
}

$id = intval($_GET['id']);

$conn->begin_transaction();

try {
    // 1️⃣ Delete related chat rooms (depends on matches)
    $conn->query("DELETE FROM chat_rooms WHERE match_id IN (SELECT id FROM matches WHERE user1_id = $id OR user2_id = $id)");

    // 2️⃣ Delete matches (depends on users)
    $conn->query("DELETE FROM matches WHERE user1_id = $id OR user2_id = $id");

    // 3️⃣ Delete related profiles
    $conn->query("DELETE FROM profiles WHERE user_id = $id");

    // 4️⃣ Delete related skill listings
    $conn->query("DELETE FROM skill_listings WHERE user_id = $id");

    // 5️⃣ Finally, delete the user
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $conn->commit();
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["error" => $e->getMessage()]);
}

if (isset($stmt)) $stmt->close();
$conn->close();
?>
