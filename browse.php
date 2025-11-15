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
$user_id = $user['id'];


$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';


$categories = $conn->query("SELECT id, name FROM skill_categories ORDER BY name ASC");


$sql = "
    SELECT 
        l.id,
        u.email,
        p.profile_image,
        sc.name AS category_name,
        l.skill_offered,
        l.skill_wanted,
        l.description,
        l.created_at
    FROM skill_listings l
    JOIN users u ON l.user_id = u.id
    JOIN skill_categories sc ON l.category_id = sc.id
    LEFT JOIN profiles p ON u.id = p.user_id
    WHERE l.user_id != ?
";

$params = [$user_id];
$types = "i";

if (!empty($search)) {
    $sql .= " AND (l.skill_offered LIKE ? OR l.skill_wanted LIKE ?)";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "ss";
}

if (!empty($category_id)) {
    $sql .= " AND l.category_id = ?";
    $params[] = $category_id;
    $types .= "i";
}

$sql .= " ORDER BY l.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$listings = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Skills - SkillSwap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="bi bi-arrow-left-right"></i> SkillSwap
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="browse.php">Browse Skills</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['email']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                            <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h1 class="mb-4">Browse Skill Listings</h1>

        <form method="GET" class="row mb-4">
            <div class="col-md-5 mb-2">
                <input type="text" name="search" class="form-control" placeholder="Search by skill..."
                    value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-4 mb-2">
                <select name="category_id" class="form-select">
                    <option value="">All Categories</option>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <option value="<?= $cat['id']; ?>" <?= ($category_id == $cat['id']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
        </form>

        <?php if ($listings->num_rows > 0): ?>
            <div class="row">
                <?php while ($listing = $listings->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="<?= htmlspecialchars($listing['profile_image'] ?? 'https://via.placeholder.com/60'); ?>"
                                        alt="Profile" class="rounded-circle me-3" width="60" height="60">
                                    <div>
                                        <h6 class="mb-0"><?= htmlspecialchars($listing['email']); ?></h6>
                                        <small class="text-muted"><?= htmlspecialchars($listing['category_name']); ?></small>
                                    </div>
                                </div>
                                
                                <h5 class="card-title text-primary"><?= htmlspecialchars($listing['skill_offered']); ?></h5>
                                <p class="mb-1"><strong>Wants to Learn:</strong>
                                    <?= htmlspecialchars($listing['skill_wanted']); ?></p>
                                <p class="text-muted"><?= htmlspecialchars($listing['description']); ?></p>

                                <div class="d-flex gap-2">
                                    <button class="btn btn-outline-primary flex-fill"
                                        onclick="window.location.href='connect.php?listing_id=<?= $listing['id']; ?>'">
                                        <i class="bi bi-chat-dots"></i> Connect
                                    </button>

                                    <button class="btn btn-primary flex-fill"
                                        onclick="window.location.href='listing-detail.php?id=<?= $listing['id']; ?>'">
                                        <i class="bi bi-info-circle"></i> Details
                                    </button>
                                </div>


                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">No listings match your search.</div>
        <?php endif; ?>
    </div>
</body>

</html>