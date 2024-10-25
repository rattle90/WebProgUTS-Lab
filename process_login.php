<?php
session_start();
include 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login']; // Bisa email atau username
    $password = $_POST['password'];

    if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    }

    $stmt->execute([$login]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];


        header('Location: index.php');
        exit();
    } else {
    
        echo "<script>alert('Email/Username atau password salah!'); window.location.href='login.php';</script>";
        exit();
    }
}
?>
