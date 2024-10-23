<?php
session_start();
include 'db.php'; // Koneksi ke database
include 'component/navbar.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$query = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$query->execute([$userId]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found!";
    exit;
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUsername = $_POST['username'] ?? $user['username'];
    $newEmail = $_POST['email'] ?? $user['email'];
    $newPassword = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $user['password'];

    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } else {
        $updateQuery = $pdo->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
        $updated = $updateQuery->execute([$newUsername, $newEmail, $newPassword, $userId]);

        if ($updated) {
            $message = "Profile updated successfully.";
            $_SESSION['username'] = $newUsername;
        } else {
            $message = "Failed to update profile.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-gray-900 p-2 fixed top-0 left-0 right-0 z-10">
        <!-- Navbar Code -->
    </nav>

    <div class="container mx-auto mt-24 p-6">
        <h1 class="text-3xl font-semibold text-gray-800 mb-6">Edit Profile</h1>

        <?php if ($message): ?>
            <div class="bg-green-200 text-green-800 p-4 mb-4 rounded">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="edit_profile.php" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700">Username:</label>
                    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" class="w-full px-3 py-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Email:</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="w-full px-3 py-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">New Password (leave blank if not changing):</label>
                    <input type="password" name="password" placeholder="Enter new password" class="w-full px-3 py-2 border rounded-lg">
                </div>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save Changes</button>
            </form>
        </div>
    </div>
</body>
</html>
