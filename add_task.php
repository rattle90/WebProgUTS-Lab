<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskName = $_POST['task_name'];
    $notes = $_POST['notes'] ?? ''; // Make notes optional
    $dueDate = $_POST['due_date'];

    // Validate the due date
    if (empty($dueDate) || $dueDate == '0000-00-00') {
        echo json_encode(['success' => false, 'message' => 'Due date is invalid.']);
        exit;
    }

    // Insert the task into the database
    $addQuery = $pdo->prepare("INSERT INTO tasks (task_name, notes, status, due_date) VALUES (?, ?, 'Pending', ?)");
    $result = $addQuery->execute([$taskName, $notes, $dueDate]);

    if ($result) {
        // Berikan respon sukses sebagai JSON
        echo json_encode(['success' => true]);
    } else {
        // Berikan respon error sebagai JSON
        echo json_encode(['success' => false]);
    }
}
?>
