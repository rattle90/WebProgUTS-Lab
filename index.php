<?php
include 'db.php'; // Database connection

// Fetch all tasks from the database
$query = $pdo->query("SELECT * FROM tasks");
$tasks = $query->fetchAll(PDO::FETCH_ASSOC);

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
        body { font-family: 'Inter', sans-serif; }
        .cursor-pointer { cursor: pointer; }
    </style>
</head>
<body class="bg-blue-600 h-screen">
    <div class="flex h-full w-11/12 space-x-6 mx-auto py-10">

        <!-- Sidebar for All Tasks -->
        <div class="w-1/3 bg-gray-900 text-white p-4 rounded-lg shadow-lg flex flex-col">
            <div class="flex justify-between mb-4">
                <h1 class="text-xl font-semibold">All my tasks</h1>
            </div>
            <ul class="space-y-3 flex-1 overflow-y-auto" id="task-list">
                <?php foreach ($tasks as $task): ?>
                    <li class="bg-gray-700 p-3 rounded-md cursor-pointer hover:bg-gray-600" onclick="loadTaskDetails(<?= $task['id'] ?>)">
                        <?= htmlspecialchars($task['task_name']) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <button class="mt-4 p-2 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg" id="add-task-btn">+ Add task</button>
        </div>

        <!-- Task Details Section -->
        <div class="w-2/3 bg-gray-100 p-6 rounded-lg shadow-lg" id="task-details">
            <div class="flex justify-between">
                <h2 class="text-2xl font-semibold">Task Details</h2>
                <button class="text-gray-500 hover:text-gray-800" id="complete-task-btn">Mark as complete</button>
            </div>
            <div class="bg-white p-4 mt-6 rounded-lg shadow-lg">
                <p class="text-gray-600">Please select a task to see the details.</p>
            </div>
        </div>
    </div>

    <!-- Add task modal (hidden initially) -->
    <div id="add-task-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-4 rounded-lg shadow-lg w-96">
            <h2 class="text-xl font-semibold mb-4">Add New Task</h2>
            <form id="add-task-form">
                <input type="text" name="task_name" placeholder="Task Name" class="p-2 border rounded-md w-full mb-3" required>
                <textarea name="notes" placeholder="Notes" class="p-2 border rounded-md w-full mb-3"></textarea>
                <button type="submit" class="bg-blue-600 text-white p-2 rounded-md w-full">Add Task</button>
            </form>
        </div>
    </div>

    <script>
        // Load task details via AJAX
        function loadTaskDetails(taskId) {
            fetch(`get_task.php?task_id=${taskId}`)
                .then(response => response.json())
                .then(data => {
                    const detailsSection = document.getElementById('task-details');
                    detailsSection.innerHTML = `
                        <div class="flex justify-between">
                            <h2 class="text-2xl font-semibold">Task Details</h2>
                            <button class="text-gray-500 hover:text-gray-800" onclick="markTaskComplete(${taskId})">Mark as complete</button>
                        </div>
                        <div class="bg-white p-4 mt-6 rounded-lg shadow-lg">
                            <h3 class="text-xl font-semibold">${data.task_name}</h3>
                            <p class="text-gray-600">Status: ${data.status}</p>
                            <h4 class="text-lg font-semibold mt-4">Notes</h4>
                            <p class="text-gray-600">${data.notes}</p>
                        </div>
                    `;
                });
        }

        // Mark task as complete via AJAX
        function markTaskComplete(taskId) {
            fetch(`mark_complete.php?task_id=${taskId}`, { method: 'POST' })
                .then(() => {
                    loadTaskDetails(taskId);  // Refresh task details after marking complete
                });
        }

        // Show the add task modal
        document.getElementById('add-task-btn').addEventListener('click', () => {
            document.getElementById('add-task-modal').classList.remove('hidden');
        });

        // Handle add task form submission
        document.getElementById('add-task-form').addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            fetch('add_task.php', {
                method: 'POST',
                body: formData
            }).then(() => {
                window.location.reload();  // Reload page after adding task
            });
        });
    </script>
</body>
</html>
