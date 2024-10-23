<?php
session_start();
include 'db.php'; // Koneksi ke database

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Arahkan ke halaman login jika belum login
    exit;
}

if (isset($_GET['id'])) {
    $taskId = $_GET['id'];

    // Query untuk mengambil data tugas berdasarkan ID
    $query = $pdo->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
    $query->execute([$taskId, $_SESSION['user_id']]);
    $task = $query->fetch(PDO::FETCH_ASSOC);

    // Cek apakah data tugas ditemukan
    if (!$task) {
        echo "Task not found!";
        exit;
    }
} else {
    echo "Invalid request!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css">
    <script>
        function markTaskComplete(taskId) {
            fetch(`mark_complete.php?task_id=${taskId}`, { method: 'POST' })
                .then(() => {
                    const statusElement = document.getElementById('status');
                    statusElement.textContent = 'Completed';
                    statusElement.classList.remove('text-red-500');
                    statusElement.classList.add('text-green-500');

                    const completeButton = document.getElementById('mark-complete-button');
                    const uncompleteButton = document.getElementById('mark-uncomplete-button');
                    completeButton.style.display = 'none'; // Hide complete button
                    uncompleteButton.style.display = 'block'; // Show uncomplete button
                });
        }

        function markTaskUncomplete(taskId) {
            fetch(`mark_uncomplete.php?task_id=${taskId}`, { method: 'POST' })
                .then(() => {
                    const statusElement = document.getElementById('status');
                    statusElement.textContent = 'Not Completed';
                    statusElement.classList.remove('text-green-500');
                    statusElement.classList.add('text-red-500');

                    const completeButton = document.getElementById('mark-complete-button');
                    const uncompleteButton = document.getElementById('mark-uncomplete-button');
                    completeButton.style.display = 'block'; // Show complete button
                    uncompleteButton.style.display = 'none'; // Hide uncomplete button
                });
        }
    </script>
</head>
<body class="bg-gray-100">
    <?php include 'component/navbar.php'; // Include navbar ?>

    <div class="container mx-auto mt-24 p-6">
        <h1 class="text-3xl font-semibold text-gray-800 mb-6">Task Details</h1>
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-2"><?= htmlspecialchars($task['task_name']) ?></h2>
            <p class="text-lg mb-4"><strong>Notes:</strong> <?= htmlspecialchars($task['notes']) ?></p>
            <p class="text-lg mb-4"><strong>Due Date:</strong> <?= htmlspecialchars($task['due_date']) ?></p>
            <p class="text-lg mb-4"><strong>Status:</strong> <span id="status" class="<?= $task['is_completed'] ? 'text-green-500' : 'text-red-500' ?>"><?= $task['is_completed'] ? 'Completed' : 'Not Completed' ?></span></p>
            <p class="text-lg mb-4"><strong>Created At:</strong> <?= htmlspecialchars($task['created_at']) ?></p>

            <div class="mt-4">
                <?php if (!$task['is_completed']): ?>
                    <button id="mark-complete-button" onclick="markTaskComplete(<?= $task['id'] ?>)" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Mark Complete</button>
                <?php else: ?>
                    <button id="mark-uncomplete-button" onclick="markTaskUncomplete(<?= $task['id'] ?>)" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Mark Uncomplete</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
