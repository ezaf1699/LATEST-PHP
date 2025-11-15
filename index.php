<?php
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillSwap - Exchange Skills, Build Community</title>
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

    <!-- The rest of your page content -->
    <section class="hero-section bg-light py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Exchange Skills,<br>Build Community</h1>
                    <p class="lead mb-4">Connect with people who want to learn what you know, and teach you what they know. No money required, just mutual learning.</p>
                    <div class="d-flex gap-3">
                        <a href="register.php" class="btn btn-primary btn-lg">Get Started</a>
                        <a href="browse.php" class="btn btn-outline-primary btn-lg">Browse Skills</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.pexels.com/photos/3184292/pexels-photo-3184292.jpeg?auto=compress&cs=tinysrgb&w=800" alt="People collaborating" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <section class="features py-5">
        <div class="container">
            <h2 class="text-center mb-5">How It Works</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-person-plus fs-3"></i>
                            </div>
                            <h5 class="card-title">1. Create Your Profile</h5>
                            <p class="card-text">Sign up and list the skills you can offer and what you'd like to learn.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-search fs-3"></i>
                            </div>
                            <h5 class="card-title">2. Find Matches</h5>
                            <p class="card-text">Browse skill listings and connect with users who match your interests.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-warning bg-opacity-10 text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-chat-dots fs-3"></i>
                            </div>
                            <h5 class="card-title">3. Exchange Skills</h5>
                            <p class="card-text">Chat with matches, arrange sessions, and start learning together.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

   <section class="categories bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-5">Popular Categories</h2>
        <div class="row g-4 justify-content-center">

            <div class="col-6 col-md-4 col-lg-3">
                <div class="card text-center border-0 shadow-sm h-100 py-4 hover-shadow">
                    <i class="bi bi-briefcase fs-1 text-primary mb-3"></i>
                    <h6>Business & Marketing</h6>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3">
                <div class="card text-center border-0 shadow-sm h-100 py-4 hover-shadow">
                    <i class="bi bi-hammer fs-1 text-primary mb-3"></i>
                    <h6>Crafts & DIY</h6>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3">
                <div class="card text-center border-0 shadow-sm h-100 py-4 hover-shadow">
                    <i class="bi bi-palette fs-1 text-primary mb-3"></i>
                    <h6>Design & Creative</h6>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3">
                <div class="card text-center border-0 shadow-sm h-100 py-4 hover-shadow">
                    <i class="bi bi-book fs-1 text-primary mb-3"></i>
                    <h6>Education & Tutoring</h6>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3">
                <div class="card text-center border-0 shadow-sm h-100 py-4 hover-shadow">
                    <i class="bi bi-heart fs-1 text-primary mb-3"></i>
                    <h6>Lifestyle & Wellness</h6>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3">
                <div class="card text-center border-0 shadow-sm h-100 py-4 hover-shadow">
                    <i class="bi bi-music-note-beamed fs-1 text-primary mb-3"></i>
                    <h6>Music & Audio</h6>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3">
                <div class="card text-center border-0 shadow-sm h-100 py-4 hover-shadow">
                    <i class="bi bi-code-slash fs-1 text-primary mb-3"></i>
                    <h6>Programming & Tech</h6>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3">
                <div class="card text-center border-0 shadow-sm h-100 py-4 hover-shadow">
                    <i class="bi bi-pencil-square fs-1 text-primary mb-3"></i>
                    <h6>Writing & Translation</h6>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
    .hover-shadow:hover {
        transform: translateY(-5px);
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
</style>


    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; 2025 SkillSwap. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="/js/app.js"></script>
    <script type="module" src="/js/home.js"></script>
</body>
</html>
