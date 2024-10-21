<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskName = $_POST['task_name'];
    $notes = $_POST['notes'];
    $dueDate = $_POST['due_date']; // Ambil tanggal jatuh tempo dari input

    // Insert the task into the database
    $addQuery = $pdo->prepare("INSERT INTO tasks (task_name, notes, status, due_date) VALUES (?, ?, 'Pending', ?)");
    $addQuery->execute([$taskName, $notes, $dueDate]);

    echo "Task added successfully";
}
