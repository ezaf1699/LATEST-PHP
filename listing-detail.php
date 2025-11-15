<?php include 'session.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listing Detail - SkillSwap</title>
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
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="browse.php">Browse Skills</a>
                    </li>

                    <!-- Dynamic Login/Profile -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['email']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item" id="nav-auth">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <span id="listing-category" class="badge bg-primary mb-3">Category</span>
                        <h2 id="listing-title" class="mb-3">Loading...</h2>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="border-start border-success border-4 ps-3 mb-3">
                                    <h6 class="text-success mb-1">
                                        <i class="bi bi-award"></i> Can Offer
                                    </h6>
                                    <p id="skill-offered" class="mb-0">Loading...</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border-start border-primary border-4 ps-3 mb-3">
                                    <h6 class="text-primary mb-1">
                                        <i class="bi bi-book"></i> Wants to Learn
                                    </h6>
                                    <p id="skill-requested" class="mb-0">Loading...</p>
                                </div>
                            </div>
                        </div>

                        <h5>Description</h5>
                        <p id="listing-description" class="text-muted">Loading...</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body text-center">
                        <img id="user-avatar" src="https://via.placeholder.com/80" alt="User"
                            class="avatar avatar-md mb-3">
                        <h5 id="user-name">Loading...</h5>
                        <p class="text-muted mb-3">
                            <i class="bi bi-geo-alt"></i> <span id="user-location">Not specified</span>
                        </p>
                        <button id="connect-btn" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-chat-dots"></i> Connect
                        </button>
                        <a id="view-profile-btn" href="#" class="btn btn-outline-primary w-100">
                            View Profile
                        </a>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="mb-3">User Reviews</h6>
                        <div id="user-reviews-container">
                            <p class="text-muted small">No reviews yet</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="/js/app.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", async () => {
            const params = new URLSearchParams(window.location.search);
            const id = params.get("id");
            if (!id) return alert("Invalid listing.");

            const res = await fetch(`get_listing_detail.php?id=${id}`);
            const data = await res.json();

            if (data.error) {
                document.querySelector("#listing-title").textContent = "Listing not found.";
                return;
            }

            document.querySelector("#listing-category").textContent = data.category_name;
            document.querySelector("#listing-title").textContent = data.skill_offered;
            document.querySelector("#skill-offered").textContent = data.skill_offered;
            document.querySelector("#skill-requested").textContent = data.skill_wanted;
            document.querySelector("#listing-description").textContent = data.description;
            document.querySelector("#user-name").textContent = data.full_name || data.email;
            document.querySelector("#user-location").textContent = data.location || "Not specified";
            document.querySelector("#user-avatar").src = data.profile_image || "https://via.placeholder.com/80";
            document.querySelector("#view-profile-btn").href = `profile.php?user=${encodeURIComponent(data.email)}`;
        });
    </script>

</body>

</html>