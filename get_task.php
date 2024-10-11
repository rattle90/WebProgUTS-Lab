<?php
include 'db.php';

if (isset($_GET['task_id'])) {
    $taskId = $_GET['task_id'];

    // Fetch task details
    $taskQuery = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
    $taskQuery->execute([$taskId]);
    $task = $taskQuery->fetch(PDO::FETCH_ASSOC);

    // Return the task as JSON
    echo json_encode($task);
}
