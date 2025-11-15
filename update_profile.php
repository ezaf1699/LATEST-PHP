<?php
session_start();
require 'db.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];


$sql = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

$user_id = $user['id'];


$full_name = $_POST['full_name'];
$location = $_POST['location'];
$bio = $_POST['bio'];


$sql = "SELECT profile_image FROM profiles WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$current_profile = $result->fetch_assoc();
$current_image = $current_profile['profile_image'] ?? null;


$profile_image = $current_image;

if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = uniqid() . "_" . basename($_FILES["profile_image"]["name"]);
    $target_file = $target_dir . $file_name;

 
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (in_array($_FILES['profile_image']['type'], $allowed_types)) {
        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
            $profile_image = $target_file;
        }
    }
}

$sql = "SELECT id FROM profiles WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  
    $sql = "UPDATE profiles 
            SET full_name = ?, bio = ?, location = ?, profile_image = ? 
            WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $full_name, $bio, $location, $profile_image, $user_id);
} else {
  
    $sql = "INSERT INTO profiles (user_id, full_name, bio, location, profile_image, is_verified, status, created_at) 
            VALUES (?, ?, ?, ?, ?, 0, 'active', NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $user_id, $full_name, $bio, $location, $profile_image);
}

$stmt->execute();

header("Location: profile.php");
exit();
?>
