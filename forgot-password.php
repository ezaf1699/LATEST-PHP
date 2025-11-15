<?php
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);

    $stmt = $conn->prepare("SELECT id FROM profiles WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $conn->query("DELETE FROM password_resets WHERE email='$email'"); // clear old
        $save = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $save->bind_param("sss", $email, $token, $expiry);
        $save->execute();

        $reset_link = "http://" . $_SERVER['HTTP_HOST'] . "/reset_password.php?token=$token";

 
        $subject = "Password Reset Request";
        $message = "Click this link to reset your password: $reset_link";
        $headers = "From: noreply@skillswap.com";

        mail($email, $subject, $message, $headers);

        echo "<script>alert('Password reset link sent! Check your email.');window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Email not found.');history.back();</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - SkillSwap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="bi bi-arrow-left-right"></i> SkillSwap
            </a>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <h2 class="text-center mb-4">Reset Password</h2>
                        <p class="text-center text-muted mb-4">Enter your email to receive a password reset link</p>

                        <form id="forgot-password-form">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                Send Reset Link
                            </button>

                            <div class="text-center">
                                <a href="login.php" class="text-decoration-none">Back to Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="/js/auth.js"></script>
</body>
</html>
