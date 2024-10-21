<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['task_id'])) {
    $taskId = intval($_GET['task_id']);
    // Update is_completed to 1
    $stmt = $pdo->prepare("UPDATE tasks SET is_completed = 1 WHERE id = :id");
    $stmt->execute(['id' => $taskId]);
    echo json_encode(['success' => true]);
}
