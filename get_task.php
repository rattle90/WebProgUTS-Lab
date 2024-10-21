<?php
include 'db.php';

if (isset($_GET['task_id'])) {
    $taskId = $_GET['task_id'];
    
    // Query untuk mengambil data tugas berdasarkan ID
    $query = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
    $query->execute([$taskId]);
    
    // Mengambil data tugas
    $task = $query->fetch(PDO::FETCH_ASSOC);

    // Mengembalikan data dalam format JSON
    echo json_encode($task);
}
