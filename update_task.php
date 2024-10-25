<?php
include 'db.php'; 

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id']) && isset($data['is_completed'])) {
    $taskId = $data['id'];
    $isCompleted = $data['is_completed'] ? 1 : 0;

    $query = $pdo->prepare("UPDATE tasks SET is_completed = ? WHERE id = ?");
    $success = $query->execute([$isCompleted, $taskId]);

    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update task status.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
}
?>
