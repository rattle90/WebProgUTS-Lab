<?php
session_start();
include 'db.php'; // Koneksi ke database

if (isset($_POST['query'])) {
    $query = $_POST['query'];
    
    // Query untuk mencari task berdasarkan nama task
    $stmt = $pdo->prepare("SELECT id, task_name FROM tasks WHERE task_name LIKE ? AND user_id = ?");
    $stmt->execute(['%' . $query . '%', $_SESSION['user_id']]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Mengirimkan hasil pencarian dalam bentuk JSON
    echo json_encode($tasks);
}
?>
