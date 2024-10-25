<?php
session_start();
include 'db.php'; 

if (isset($_POST['query'])) {
    $query = $_POST['query'];

    // Query untuk mencari task berdasarkan nama task, dan juga mengambil due_date dan is_completed
    $stmt = $pdo->prepare("SELECT id, task_name, due_date, is_completed FROM tasks WHERE task_name LIKE ? AND user_id = ?");
    $stmt->execute(['%' . $query . '%', $_SESSION['user_id']]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format due_date to "tanggal nama bulan"
    foreach ($tasks as &$task) {
        if ($task['due_date']) {
            $date = new DateTime($task['due_date']);
            $task['due_date'] = $date->format('j F'); 
        } else {
            $task['due_date'] = 'No due date'; 
        }
    }

    echo json_encode($tasks);
}
?>
