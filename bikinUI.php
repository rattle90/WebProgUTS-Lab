<?php
include 'db.php'; // Database connection

$query = $pdo->query("SELECT * FROM tasks");
$tasks = $query->fetchAll(PDO::FETCH_ASSOC);
$today = date('Y-m-d');

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

        .cursor-pointer {
            cursor: pointer;
        }
    </style>
</head>

<body class="bg-white h-screen">

    <div class="max-w-4xl mx-auto py-10">

        <div class="mb-6">
            <h1 class="text-2xl font-black">Today</h1>
            <!-- diisi sama select * todolist totaldata -->
            <p class="text-gray-500">5 tasks</p>
        </div>

        <div class="mb-8">
            <h2 class="text-base font-black">Overdue</h2>
            <hr class="border-t border-gray-300 w-full my-2">
            <ul class="space-y-4" id="task-list">
                <!-- sesuaiin dbnya -->
                <?php
                $tasks = [
                    ['id' => 1, 'task_name' => 'Download Todoist on all devices', 'due_date' => '12 Oct', 'status' => 'overdue'],
                    ['id' => 2, 'task_name' => 'Do a weekly review of my tasks', 'due_date' => '13 Oct', 'status' => 'overdue'],
                    ['id' => 3, 'task_name' => 'Take productivity method quiz', 'due_date' => '13 Oct', 'status' => 'overdue'],
                    ['id' => 4, 'task_name' => 'Browse Todoist Inspiration Hub', 'due_date' => '14 Oct', 'status' => 'overdue'],
                ];

                foreach ($tasks as $task): ?>
                    <?php if ($task['status'] == 'overdue'): ?>
                        <li
                            class="flex items-center justify-between bg-white p-4 rounded-lg shadow-md border-l-4 border-red-500">
                            <div>
                                <input type="checkbox" id="task-<?= $task['id'] ?>" class="mr-3 task-checkbox"
                                    data-task-id="<?= $task['id'] ?>">
                                <label for="task-<?= $task['id'] ?>" class="text-gray-700 task-label"
                                    id="label-<?= $task['id'] ?>">
                                    <?= htmlspecialchars($task['task_name']) ?>
                                </label>
                                <p class="text-gray-400 text-sm"><?= htmlspecialchars($task['due_date']) ?></p>
                            </div>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>

        <div>
            <h2 class="text-base font-black"><?php echo date("d M") . " · Today · " . date("l"); ?></h2>
            <hr class="border-t border-gray-300 w-full my-2">
            <ul class="space-y-4" id="task-list-today">
                <?php
                $tasks_today = [
                    ['id' => 5, 'task_name' => 'Review exam dates and plan ahead', 'due_date' => '21 Oct', 'status' => 'today'],
                ];

                foreach ($tasks_today as $task): ?>
                    <?php if ($task['status'] == 'today'): ?>
                        <li class="flex items-center justify-between bg-white p-4 rounded-lg shadow-md">
                            <div>
                                <input type="checkbox" id="task-<?= $task['id'] ?>" class="mr-3">
                                <label for="task-<?= $task['id'] ?>" class="text-gray-700">
                                    <?= htmlspecialchars($task['task_name']) ?>
                                </label>
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

    <div id="add-task-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-2/3 relative" id="modal-content">
            <form id="add-task-form">
                <div class="mb-4">
                    <input type="text" name="task_name" placeholder="Task name"
                        class="p-2 text-lg font-semibold border-b w-full focus:outline-none mb-2" required>
                    <textarea name="notes" placeholder="Description"
                        class="p-2 text-gray-500 border-b w-full focus:outline-none"></textarea>
                </div>

                <div class="flex items-center space-x-3 mb-6">
                    <div class="flex items-center space-x-2 bg-gray-100 p-2 rounded-md">
                        <span class="text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 4h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </span>
                        <span class="text-gray-600">ni suru isi tanggal</span>
                        <span class="text-red-500 cursor-pointer">✕</span>
                    </div>
                </div>

                <div class="flex space-x-2">
                    <button type="button" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-md">Cancel</button>
                    <button type="submit" class="bg-red-400 text-white px-4 py-2 rounded-md">Add task</button>
                </div>
            </form>
        </div>
    </div>


    <script>
        document.querySelectorAll('.task-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const taskId = this.getAttribute('data-task-id');
                const label = document.getElementById(`label-${taskId}`);

                // Toggle the line-through class on the label based on the checkbox state
                if (this.checked) {
                    label.classList.add('line-through', 'text-gray-400');
                } else {
                    label.classList.remove('line-through', 'text-gray-400');
                }
            });
        });

        document.getElementById('add-task-form').addEventListener('submit', function (e) {
            e.preventDefault();
            alert('New task added!');
            document.getElementById('add-task-modal').classList.add('hidden');
        });

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

        document.getElementById('add-task-btn').addEventListener('click', function () {
            document.getElementById('add-task-modal').classList.remove('hidden');
        });

        function markTaskComplete(taskId) {
            fetch(`mark_complete.php?task_id=${taskId}`, { method: 'POST' })
                .then(() => {
                    loadTaskDetails(taskId);
                });
        }

        document.getElementById('add-task-modal').addEventListener('click', (e) => {
            const modalContent = document.getElementById('modal-content');
            if (!modalContent.contains(e.target)) {
                document.getElementById('add-task-modal').classList.add('hidden');
            }
        });

        document.getElementById('add-task-form').addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            fetch('add_task.php', {
                method: 'POST',
                body: formData
            }).then(() => {
                window.location.reload();
            });
        });
    </script>
</body>

</html>