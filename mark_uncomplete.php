<?php
include 'db.php'; // Koneksi database

if (isset($_GET['task_id'])) {
    $taskId = (int)$_GET['task_id'];

    // Update status tugas menjadi uncompleted (is_completed = 0)
    $stmt = $pdo->prepare("UPDATE tasks SET is_completed = 0 WHERE id = ?");
    $stmt->execute([$taskId]);
}
?>
