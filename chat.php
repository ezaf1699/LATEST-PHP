<?php
include 'session.php'; 

require 'db.php';

if (!isset($_GET['room_id'])) {
    die("Chat room not specified.");
}
$room_id = (int)$_GET['room_id'];


$stmt = $conn->prepare("
    SELECT m.*, u.email AS sender_email
    FROM chat_messages m
    JOIN users u ON m.sender_id = u.id
    WHERE m.chat_room_id = ?
    ORDER BY m.sent_at ASC
");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$messages = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - SkillSwap</title>
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

    <div class="container py-4">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-chat-dots"></i> Conversations</h5>
                    </div>
                    <div class="list-group list-group-flush" id="chat-rooms-list">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0" id="chat-header-name">Select a conversation</h5>
                            <small id="chat-header-status"></small>
                        </div>
                        <button class="btn btn-sm btn-outline-light" id="complete-match-btn" style="display: none;">
                            Mark as Complete
                        </button>
                    </div>

                    <div class="chat-container">
                        <div class="chat-messages" id="chat-messages">
                            <div class="text-center text-muted py-5">
                                Select a conversation to start chatting
                            </div>
                        </div>

                        <div class="chat-input" id="chat-input-container" style="display: none;">
                            <form id="message-form" class="d-flex gap-2">
                                <input type="text" class="form-control" id="message-input"
                                       placeholder="Type a message..." required>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="/js/app.js"></script>
    <script type="module" src="/js/chat.js"></script>
</body>
</html>
