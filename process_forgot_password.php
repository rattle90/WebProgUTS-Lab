<?php
$host = 'localhost';
$dbname = 'todo_list_db';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data dari form
$email = $_POST['email'];
$user = $_POST['username'];
$new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

// Query untuk cek apakah email dan username cocok
$sql = "SELECT * FROM users WHERE email = ? AND username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $email, $user);
$stmt->execute();
$result = $stmt->get_result();

$status = "";
$link = "";

if ($result->num_rows > 0) {
    // Update password baru
    $update_sql = "UPDATE users SET password = ? WHERE email = ? AND username = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('sss', $new_password, $email, $user);

    if ($update_stmt->execute()) {
        $status = "Password berhasil direset.";
        $link = "<a href='login.php' class='text-blue-500 hover:underline'>Kembali ke Login</a>";
    } else {
        $status = "Gagal mereset password. Silakan coba lagi.";
    }
} else {
    $status = "Email atau username tidak ditemukan.";
    $link = "<a href='forgot_password.php' class='text-blue-500 hover:underline'>Coba lagi</a>";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Result | Mengnugas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-6">

    <!-- Background pattern -->
    <div class="absolute inset-0 bg-[url('https://source.unsplash.com/featured/?abstract,success')] bg-cover opacity-10 pointer-events-none"></div>

    <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8 space-y-6 transform transition duration-300 hover:scale-[1.02]">
        <h1 class="text-3xl font-semibold text-center <?php echo ($result->num_rows > 0) ? 'text-green-600' : 'text-red-500'; ?>">
            <?php echo ($result->num_rows > 0) ? 'Password Reset Successful!' : 'Reset Password Failed'; ?>
        </h1>
        
        <p class="text-center text-gray-600 text-md">
            <?php echo $status; ?>
        </p>

        <div class="text-center">
            <?php echo $link; ?>
        </div>
    </div>

</body>
</html>
