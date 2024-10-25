<?php
session_start();
include 'db.php';

$error = ''; // Variabel untuk menyimpan pesan error

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $password = $_POST['password'];

    // Tentukan apakah login adalah email atau username
    $stmt = filter_var($login, FILTER_VALIDATE_EMAIL) 
        ? $pdo->prepare("SELECT * FROM users WHERE email = ?")
        : $pdo->prepare("SELECT * FROM users WHERE username = ?");

    $stmt->execute([$login]);
    $user = $stmt->fetch();

    // Verifikasi password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        header('Location: index.php');
        exit();
    } else {
        $error = 'Email/Username atau password salah!'; // Pesan error ditampilkan langsung
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Mengnugas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-6">

    <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8 space-y-6 transform transition duration-300 hover:scale-[1.02]">
        <h1 class="text-3xl font-semibold text-center text-gray-800">Mengnugas</h1>
        <p class="text-center text-gray-500 text-sm">Stay organized, stay productive</p>

        <!-- Pesan Error -->
        <?php if (!empty($error)): ?>
            <p class="text-center text-red-500"><?php echo $error; ?></p>
        <?php endif; ?>
        
        <form action="login.php" method="POST" class="space-y-4">
            <div>
                <label for="login" class="block text-sm font-medium text-gray-600">Email or Username</label>
                <input 
                    type="text" 
                    name="login" 
                    id="login" 
                    required 
                    class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150"
                    placeholder="Your email or username">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-600">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    required 
                    class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150"
                    placeholder="Your password">
            </div>
            <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                Login
            </button>
        </form>
        
        <div class="text-center text-sm text-gray-500">
            <a href="forgot_password.php" class="hover:underline">Forgot password?</a>
        </div>
        <p class="text-center text-sm text-gray-500">
            Don't have an account? <a href="signup.php" class="text-blue-500 hover:underline">Sign up</a>
        </p>
    </div>

</body>
</html>
