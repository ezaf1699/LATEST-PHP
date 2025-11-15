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
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

if (!$user) {
    die("User not found in database.");
}

$user_id = $user['id'];


$stmt = $conn->prepare("SELECT * FROM profiles WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$profile_result = $stmt->get_result();
$profile = $profile_result->fetch_assoc();


if (!$profile) {
    $profile = [
        'full_name' => 'Not set',
        'bio' => 'No bio yet.',
        'location' => 'Not specified',
        'profile_image' => 'https://via.placeholder.com/100',
        'is_verified' => 0,
        'status' => 'active'
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - SkillSwap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/style.css">
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
                    <li class="nav-item"><a class="nav-link" href="browse.php">Browse Skills</a></li>
                    <li class="nav-item"><a class="nav-link" href="my-listings.php">My Listings</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <?php echo htmlspecialchars($email); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item active" href="profile.php">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="profile-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-auto">
                    <img id="profile-avatar" src="<?php echo htmlspecialchars($profile['profile_image']); ?>"
                         alt="Profile" class="avatar avatar-lg border border-3 border-white rounded-circle" width="100" height="100">
                </div>
                <div class="col">
                    <h2 id="profile-username"><?php echo htmlspecialchars($profile['full_name']); ?></h2>
                    <p class="mb-1" id="profile-location">
                        <i class="bi bi-geo-alt"></i> 
                        <span><?php echo htmlspecialchars($profile['location']); ?></span>
                    </p>
                    <p class="mb-0" id="profile-email"><?php echo htmlspecialchars($email); ?></p>
                </div>
                <div class="col-auto">
                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        <i class="bi bi-pencil"></i> Edit Profile
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div class="container py-5">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">About Me</h5>
                        <p id="profile-bio" class="text-muted"><?php echo nl2br(htmlspecialchars($profile['bio'])); ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Recent Activity</h5>
                        <p class="text-muted">No recent activity yet.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

 
    <div class="modal fade" id="editProfileModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="update_profile.php" enctype="multipart/form-data" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

                    <div class="mb-3 text-center">
                        <img src="<?php echo htmlspecialchars($profile['profile_image']); ?>" id="preview" class="rounded-circle mb-3" width="100" height="100">
                        <div>
                            <input type="file" name="profile_image" class="form-control" accept="image/*" onchange="previewImage(event)">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($profile['full_name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($profile['location']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" class="form-control" rows="4"><?php echo htmlspecialchars($profile['bio']); ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('preview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
