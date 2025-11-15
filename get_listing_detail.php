<?php
require 'db.php';

if (!isset($_GET['id'])) {
    echo json_encode(["error" => "Missing listing ID"]);
    exit();
}

$listing_id = intval($_GET['id']);

$sql = "
    SELECT 
        l.id,
        l.skill_offered,
        l.skill_wanted,
        l.description,
        sc.name AS category_name,
        u.email,
        p.full_name,
        p.location,
        p.profile_image
    FROM skill_listings l
    JOIN users u ON l.user_id = u.id
    JOIN skill_categories sc ON l.category_id = sc.id
    LEFT JOIN profiles p ON p.user_id = u.id
    WHERE l.id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $listing_id);
$stmt->execute();
$result = $stmt->get_result();
$listing = $result->fetch_assoc();

if ($listing) {
    echo json_encode($listing);
} else {
    echo json_encode(["error" => "Listing not found"]);
}
?>
