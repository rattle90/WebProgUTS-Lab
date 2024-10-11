<?php
include 'db.php';

if (isset($_GET['task_id'])) {
    $taskId = $_GET['task_id'];

    // Update the task status
    $updateQuery = $pdo->prepare("UPDATE tasks SET status = 'Completed' WHERE id = ?");
    $updateQuery->execute([$taskId]);

    echo "Task marked as completed";
}
