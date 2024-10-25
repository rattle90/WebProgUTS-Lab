<?php
include 'db.php'; 

if (isset($_GET['task_id'])) {
    $taskId = intval($_GET['task_id']);
    
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
    $stmt->bindParam(':id', $taskId, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
?>
