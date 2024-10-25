<?php
include 'db.php';

if (isset($_GET['task_id'])) {
    $taskId = $_GET['task_id'];
    
    $query = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
    $query->execute([$taskId]);
    
    $task = $query->fetch(PDO::FETCH_ASSOC);

    if ($task) {
        echo json_encode($task);
    } else {
        echo json_encode(['error' => 'Task not found']);
    }
} else {
    echo json_encode(['error' => 'Task ID not provided']);
}
