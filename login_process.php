<?php
session_start();


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


$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($pass, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

  
        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('Invalid password!'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('No account found with that email!'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
