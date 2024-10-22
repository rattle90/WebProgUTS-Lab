<?php
session_start();
include 'db.php'; // Koneksi database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Mengambil data pengguna dari database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
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
        echo "<script>alert('Email atau password salah!'); window.location.href='login.php';</script>";
        exit();
    }
}
?>
