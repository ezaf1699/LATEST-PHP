<?php
require 'db.php';

header('Content-Type: application/json');

try {
    // Fetch users with their profile info
    $users_sql = "
        SELECT 
            u.id,
            u.email,
            p.full_name,
            p.location,
            p.status,
            p.created_at
        FROM users u
        LEFT JOIN profiles p ON u.id = p.user_id
        ORDER BY p.created_at DESC
    ";
    $users_result = $conn->query($users_sql);
    $users = [];
    while ($row = $users_result->fetch_assoc()) {
        $users[] = $row;
    }

    // ✅ Fetch all skill listings
    $listings_sql = "
        SELECT 
            l.id,
            l.user_id,
            l.skill_offered,
            l.skill_wanted,
            l.is_active,
            l.created_at,
            p.full_name AS user_name
        FROM skill_listings l
        JOIN profiles p ON l.user_id = p.user_id
        ORDER BY l.created_at DESC
    ";
    $listings_result = $conn->query($listings_sql);
    $listings = [];
    while ($row = $listings_result->fetch_assoc()) {
        $listings[] = $row;
    }

    echo json_encode([
        'users' => $users,
        'listings' => $listings
    ]);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
