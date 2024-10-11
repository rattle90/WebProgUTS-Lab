<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskName = $_POST['task_name'];
    $notes = $_POST['notes'];

    // Insert the task into the database
    $addQuery = $pdo->prepare("INSERT INTO tasks (task_name, notes, status) VALUES (?, ?, 'Pending')");
    $addQuery->execute([$taskName, $notes]);

    echo "Task added successfully";
}
