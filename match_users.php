<?php
require 'db.php';

function createMatchIfNeeded($user_id, $category_id) {
    global $conn;


    $sql = "SELECT DISTINCT user_id FROM skill_listings 
            WHERE category_id = ? AND user_id != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $category_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $other_user_id = $row['user_id'];

 
        $check = $conn->prepare("SELECT id FROM matches 
                                 WHERE (user1_id = ? AND user2_id = ?) 
                                    OR (user1_id = ? AND user2_id = ?)");
        $check->bind_param("iiii", $user_id, $other_user_id, $other_user_id, $user_id);
        $check->execute();
        $check_result = $check->get_result();

        if ($check_result->num_rows === 0) {
       
            $insert = $conn->prepare("INSERT INTO matches (user1_id, user2_id, status, created_at)
                                      VALUES (?, ?, 'pending', NOW())");
            $insert->bind_param("ii", $user_id, $other_user_id);
            $insert->execute();
        }
    }
}
?>
