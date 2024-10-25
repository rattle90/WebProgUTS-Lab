<?php
include 'db.php';

session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskName = $_POST['task_name'];
    $notes = $_POST['notes'] ?? ''; 
    $dueDate = $_POST['due_date'];

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
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
