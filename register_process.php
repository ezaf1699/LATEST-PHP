<?php

$servername = "localhost";   
$username = "root";         
$password = "";              
$dbname = "elphp";  

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$email = $_POST['email'];
$pass = $_POST['password'];
$confirm_pass = $_POST['confirm_password'];
$role = $_POST['role'];


if ($pass !== $confirm_pass) {
    echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
    exit();
}


$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "<script>alert('Email already registered!'); window.history.back();</script>";
    exit();
}


$hashed_pass = password_hash($pass, PASSWORD_DEFAULT);


$stmt = $conn->prepare("INSERT INTO users (email, password, role, created_at) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("sss", $email, $hashed_pass, $role);

if ($stmt->execute()) {
    echo "<script>alert('Registration successful! You can now log in.'); window.location.href='login.php';</script>";
} else {
    echo "<script>alert('Error: could not register user.'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
