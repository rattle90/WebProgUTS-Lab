<?php
include 'db.php';
session_start();

$group = $_GET['group'];
$user_id = $_SESSION['user_id']; 

$query = $pdo->prepare("
    SELECT * FROM tasks 
    WHERE user_id = ? AND due_date_category = ?
");
$query->execute([$user_id, $group]);
$tasks = $query->fetchAll(PDO::FETCH_ASSOC);

function calculateProgress($tasks) {
    if (empty($tasks)) return 0;
    $total = count($tasks);
    $completed = count(array_filter($tasks, fn($task) => $task['is_completed']));
    return ($completed / $total) * 100;
}

$progress = calculateProgress($tasks);
echo json_encode(['progress' => $progress]);
?>
