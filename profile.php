<?php
session_start();
include 'db.php'; // Koneksi ke database
include 'component/navbar.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Arahkan ke halaman login jika belum login
    exit;
}

// Ambil informasi pengguna dari database
$userId = $_SESSION['user_id'];
$query = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$query->execute([$userId]);
$user = $query->fetch(PDO::FETCH_ASSOC);

// Cek apakah data pengguna ditemukan
if (!$user) {
    echo "User not found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-gray-900 p-2 fixed top-0 left-0 right-0 z-10">
        <!-- Navbar Code -->
        <!-- (Pastikan untuk menyertakan navbar Anda di sini) -->
    </nav>

    <div class="container mx-auto mt-24 p-6">
        <h1 class="text-3xl font-semibold text-gray-800 mb-6">Profile Information</h1>
        <div class="bg-white shadow-md rounded-lg p-6">
            <p class="text-lg"><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
            <p class="text-lg"><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <!-- Tambahkan informasi lain yang diperlukan di sini -->
        </div>
    </div>
</body>
</html>
