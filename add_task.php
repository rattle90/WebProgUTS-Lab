<?php
include 'db.php';

session_start(); // Pastikan session sudah dimulai

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskName = $_POST['task_name'];
    $notes = $_POST['notes'] ?? ''; // Buat catatan bersifat opsional
    $dueDate = $_POST['due_date'];

    // Validasi tanggal jatuh tempo
    if (empty($dueDate) || $dueDate == '0000-00-00') {
        echo json_encode(['success' => false, 'message' => 'Due date is invalid.']);
        exit;
    }

    // Ambil user_id dari session
    $userId = $_SESSION['user_id'];

    // Masukkan tugas ke dalam database
    $addQuery = $pdo->prepare("INSERT INTO tasks (task_name, notes, status, due_date, user_id) VALUES (?, ?, 'Pending', ?, ?)");
    $result = $addQuery->execute([$taskName, $notes, $dueDate, $userId]);

    if ($result) {
        // Berikan respon sukses sebagai JSON
        echo json_encode(['success' => true]);
    } else {
        // Berikan respon error sebagai JSON
        echo json_encode(['success' => false]);
    }
}
?>
