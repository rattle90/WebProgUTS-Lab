<?php
session_start();
include 'db.php'; // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login']; // Bisa email atau username
    $password = $_POST['password'];

    // Mengecek apakah input adalah email atau username
    if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    }

    $stmt->execute([$login]);
    $user = $stmt->fetch();

    // Memverifikasi password
    if ($user && password_verify($password, $user['password'])) {
        // Menyimpan data pengguna di sesi
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // Alihkan ke dashboard atau halaman yang diinginkan
        header('Location: index.php');
        exit();
    } else {
        // Jika login gagal
        echo "<script>alert('Email/Username atau password salah!'); window.location.href='login.php';</script>";
        exit();
    }
}
?>
