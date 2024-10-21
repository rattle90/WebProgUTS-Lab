<?php
include 'db.php'; // Database connection
include 'component/navbar.php';

// Fetch tasks from the database
$query = $pdo->query("SELECT * FROM tasks");
$tasks = $query->fetchAll(PDO::FETCH_ASSOC);
$today = date('Y-m-d');

// Count tasks for display
$overdue_count = count(array_filter($tasks, fn($task) => $task['status'] == 'overdue'));
$today_count = count(array_filter($tasks, fn($task) => $task['due_date'] == $today));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-white h-screen">

    <div class="max-w-4xl mx-auto py-10">
        <div class="mb-6">
            <h1 class="text-2xl font-black">Today</h1>
            <p class="text-gray-500"><?= $today_count ?> tasks</p>
        </div>

        <div class="mb-8">
            <h2 class="text-base font-black">Overdue</h2>
            <hr class="border-t border-gray-300 w-full my-2">
            <ul class="space-y-4">
                <?php foreach ($tasks as $task): ?>
                    <?php if ($task['status'] == 'overdue'): ?>
                        <li class="flex items-center justify-between bg-white p-4 rounded-lg shadow-md border-l-4 border-red-500">
                            <div>
                                <input type="checkbox" id="task-<?= $task['id'] ?>" class="mr-3 task-checkbox" data-task-id="<?= $task['id'] ?>" <?= $task['is_completed'] ? 'checked' : '' ?>>
                                <label for="task-<?= $task['id'] ?>" class="text-gray-700"><?= htmlspecialchars($task['task_name']) ?></label>
                                <p class="text-gray-400 text-sm"><?= htmlspecialchars($task['due_date']) ?></p>
                            </div>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>

        <div>
            <h2 class="text-base font-black"><?= date("d M") . " · Today · " . date("l"); ?></h2>
            <hr class="border-t border-gray-300 w-full my-2">
            <ul class="space-y-4">
                <?php foreach ($tasks as $task): ?>
                    <?php if ($task['due_date'] == $today): ?>
                        <li class="flex items-center justify-between bg-white p-4 rounded-lg shadow-md">
                            <div>
                                <input type="checkbox" id="task-<?= $task['id'] ?>" class="mr-3 task-checkbox" data-task-id="<?= $task['id'] ?>" <?= $task['is_completed'] ? 'checked' : '' ?>>
                                <label for="task-<?= $task['id'] ?>" class="text-gray-700"><?= htmlspecialchars($task['task_name']) ?></label>
                                <p class="text-gray-400 text-sm"><?= htmlspecialchars($task['due_date']) ?></p>
                            </div>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="mt-8">
            <button class="flex items-center text-gray-500 hover:text-gray-700" id="add-task-btn">
                <span class="text-red-500 text-xl mr-2">+</span>
                <span>Add task</span>
            </button>
        </div>
    </div>

    <!-- Add Task Modal -->
    <div id="add-task-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-2/3 relative" id="modal-content">
            <form id="add-task-form">
                <div class="mb-4">
                    <input type="text" name="task_name" placeholder="Task name" class="p-2 text-lg font-semibold border-b w-full focus:outline-none mb-2" required>
                    <input type="date" name="due_date" class="p-2 text-lg font-semibold border-b w-full focus:outline-none mb-2" required>
                </div>
                <div class="flex space-x-2">
                    <button type="button" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-md">Cancel</button>
                    <button type="submit" class="bg-red-400 text-white px-4 py-2 rounded-md">Add task</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('add-task-form').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            fetch('add_task.php', {
                method: 'POST',
                body: formData
            }).then(() => {
                window.location.reload();
            });
        });

        document.getElementById('add-task-btn').addEventListener('click', function () {
            document.getElementById('add-task-modal').classList.remove('hidden');
        });

        document.getElementById('add-task-modal').addEventListener('click', (e) => {
            const modalContent = document.getElementById('modal-content');
            if (!modalContent.contains(e.target)) {
                document.getElementById('add-task-modal').classList.add('hidden');
            }
        });

        document.querySelectorAll('.task-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const taskId = this.dataset.taskId;
                const isCompleted = this.checked ? 1 : 0;

                fetch('update_task.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: taskId, is_completed: isCompleted })
                }).then(() => {
                    // Optionally reload or update the UI here
                });
            });
        });
    </script>
</body>
</html>
