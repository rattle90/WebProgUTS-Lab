<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Periksa apakah email atau username sudah digunakan
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$email, $username]);
    $existingUser = $stmt->fetch();

    $status = "";
    $link = "";

    if ($existingUser) {
        // Jika email atau username sudah digunakan
        $status = "Email atau Username sudah terdaftar!";
        $link = "<a href='signup.php' class='text-blue-500 hover:underline'>Coba lagi</a>";
    } else {
        // Hash password dan masukkan data pengguna baru
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        
        if ($stmt->execute([$username, $email, $hashedPassword])) {
            $status = "Pendaftaran berhasil! Silakan login.";
            $link = "<a href='login.php' class='text-blue-500 hover:underline'>Kembali ke Login</a>";
        } else {
            $status = "Gagal mendaftarkan akun. Silakan coba lagi.";
            $link = "<a href='signup.php' class='text-blue-500 hover:underline'>Coba lagi</a>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Result | Mengnugas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-100 to-blue-100 min-h-screen flex items-center justify-center p-4 sm:p-0">

    <!-- Background pattern -->
    <div class="absolute inset-0 bg-[url('https://source.unsplash.com/featured/?abstract,success')] bg-cover opacity-10 pointer-events-none"></div>

    <div class="relative w-full max-w-lg bg-white shadow-2xl rounded-lg overflow-hidden transform transition duration-300 hover:scale-105">
        <div class="p-8 text-center">
            <h1 class="text-3xl font-semibold <?php echo ($existingUser) ? 'text-red-500' : 'text-green-600'; ?>">
                <?php echo ($existingUser) ? 'Pendaftaran Gagal!' : 'Pendaftaran Berhasil'; ?>
            </h1>
            <p class="mt-4 text-lg <?php echo ($existingUser) ? 'text-red-500' : 'text-green-500'; ?>">
                <?php echo $status; ?>
            </p>
            <div class="mt-6">
                <?php echo $link; ?>
            </div>
        </div>
    </div>
</body>
</html>
