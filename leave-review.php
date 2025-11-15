<?php 
include 'session.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave a Review - SkillSwap</title>
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
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="text-center mb-4">Leave a Review</h2>
                        <p class="text-center text-muted mb-4">
                            How was your skill exchange with <strong id="reviewee-name">loading...</strong>?
                        </p>

                        <form id="review-form">
                            <div class="mb-4 text-center">
                                <label class="form-label d-block mb-3">Rating</label>
                                <div class="rating-input">
                                    <input type="radio" name="rating" value="5" id="star5" required>
                                    <label for="star5" class="star">★</label>
                                    <input type="radio" name="rating" value="4" id="star4">
                                    <label for="star4" class="star">★</label>
                                    <input type="radio" name="rating" value="3" id="star3">
                                    <label for="star3" class="star">★</label>
                                    <input type="radio" name="rating" value="2" id="star2">
                                    <label for="star2" class="star">★</label>
                                    <input type="radio" name="rating" value="1" id="star1">
                                    <label for="star1" class="star">★</label>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="comment" class="form-label">Your Review</label>
                                <textarea class="form-control" id="comment" rows="5"
                                          placeholder="Share your experience with this skill exchange..." required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                Submit Review
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .rating-input {
            direction: rtl;
            display: inline-block;
            font-size: 3rem;
        }

        .rating-input input[type="radio"] {
            display: none;
        }

        .rating-input label.star {
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s;
        }

        .rating-input input[type="radio"]:checked ~ label.star,
        .rating-input label.star:hover,
        .rating-input label.star:hover ~ label.star {
            color: #ffc107;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="/js/app.js"></script>
    <script type="module" src="/js/leave-review.js"></script>
</body>
</html>
