<?php
session_start();
require 'db.php'; 


if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}


$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

$user_id = $user['id'];


$sql = "
    SELECT 
        u.id AS matched_user_id,
        u.email AS matched_user_email,
        l2.skill_offered AS matched_skill_offered,
        l2.skill_wanted AS matched_skill_wanted,
        l2.description AS matched_description,
        sc.name AS category_name
    FROM skill_listings l1
    JOIN skill_listings l2 ON l1.category_id = l2.category_id 
        AND l1.user_id != l2.user_id
    JOIN users u ON l2.user_id = u.id
    JOIN skill_categories sc ON l1.category_id = sc.id
    WHERE l1.user_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$matches = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Matches - SkillSwap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="bi bi-arrow-left-right"></i> SkillSwap
            </a>
        </div>
    </nav>

    <div class="container py-5">
        <h1 class="mb-4">My Matches</h1>

        <?php if ($matches->num_rows > 0): ?>
            <div class="row">
                <?php while ($match = $matches->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-primary"><?= htmlspecialchars($match['matched_user_email']); ?></h5>
                                <p><strong>Category:</strong> <?= htmlspecialchars($match['category_name']); ?></p>
                                <p><strong>Skill Offered:</strong> <?= htmlspecialchars($match['matched_skill_offered']); ?></p>
                                <p><strong>Wants to Learn:</strong> <?= htmlspecialchars($match['matched_skill_wanted']); ?></p>
                                <p><?= htmlspecialchars($match['matched_description']); ?></p>
                                <button class="btn btn-outline-primary w-100">Connect</button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">No matches found yet. Try adding more listings!</div>
        <?php endif; ?>
    </div>
</body>
</html>
