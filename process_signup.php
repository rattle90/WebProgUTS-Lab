<?php
session_start();
include 'db.php'; // Koneksi database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Cek apakah email sudah terdaftar
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $existingUser = $stmt->fetch();

    if ($existingUser) {
        // Jika email sudah terdaftar
        echo "<script>alert('Email sudah terdaftar!'); window.location.href='signup.php';</script>";
        exit();
    } else {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Menyimpan data pengguna baru ke dalam database
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashedPassword]);

        // Alihkan ke halaman login setelah berhasil mendaftar
        echo "<script>alert('Pendaftaran berhasil! Silakan login.'); window.location.href='login.php';</script>";
        exit();
    }
}
?>
