<?php
include 'db.php'; // Koneksi database

// Ambil semua task dari database
$query = $pdo->query("SELECT * FROM tasks");
$tasks = $query->fetchAll(PDO::FETCH_ASSOC);

// Fungsi untuk mengelompokkan tugas berdasarkan tanggal jatuh tempo
function groupTasksByDueDate($tasks) {
    $groupedTasks = [
        'today' => [],
        'tomorrow' => [],
        'this_week' => [],
        'next_week' => [],
        'later' => [],
    ];

    $today = new DateTime();
    $tomorrow = (clone $today)->modify('+1 day');
    $endOfWeek = (clone $today)->modify('Sunday this week');
    $startOfNextWeek = (clone $endOfWeek)->modify('+1 day');
    $endOfNextWeek = (clone $startOfNextWeek)->modify('Sunday this week');

    foreach ($tasks as $task) {
        $dueDate = new DateTime($task['due_date']);
        
        if ($dueDate->format('Y-m-d') === $today->format('Y-m-d')) {
            $groupedTasks['today'][] = $task;
        } elseif ($dueDate->format('Y-m-d') === $tomorrow->format('Y-m-d')) {
            $groupedTasks['tomorrow'][] = $task;
        } elseif ($dueDate >= $startOfNextWeek && $dueDate <= $endOfNextWeek) {
            $groupedTasks['next_week'][] = $task;
        } elseif ($dueDate >= $endOfWeek && $dueDate <= $startOfNextWeek) {
            $groupedTasks['this_week'][] = $task;
        } else {
            $groupedTasks['later'][] = $task;
        }
    }

    return $groupedTasks;
}

$groupedTasks = groupTasksByDueDate($tasks);
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
    </style>
</head>
<body class="bg-blue-600 h-screen">
    <div class="flex h-full w-11/12 space-x-6 mx-auto py-10">

        <!-- Sidebar untuk Daftar Semua Tugas -->
        <div class="w-1/3 bg-gray-900 text-white p-4 rounded-lg shadow-lg flex flex-col">
            <div class="flex justify-between mb-4">
                <h1 class="text-xl font-semibold">All my tasks</h1>
            </div>
            <ul class="space-y-3 flex-1 overflow-y-auto" id="task-list">
                <?php foreach ($groupedTasks as $group => $tasks): ?>
                    <?php if (!empty($tasks)): ?>
                        <h2 class="text-lg font-semibold text-gray-300 mt-4"><?= ucfirst(str_replace('_', ' ', $group)) ?></h2>
                        <?php foreach ($tasks as $task): ?>
                            <li class="flex items-center justify-between bg-gray-700 p-3 rounded-md cursor-pointer hover:bg-gray-600"
                                onclick="loadTaskDetails(<?= $task['id'] ?>)">
                                <div class="flex items-center">
                                    <div class="w-1 h-full bg-green-500 mr-3"></div> <!-- Lis hijau di kiri -->
                                    <input type="checkbox" id="task-<?= $task['id'] ?>" class="mr-3 task-checkbox"
                                           data-task-id="<?= $task['id'] ?>" <?= $task['status'] == 'complete' ? 'checked' : '' ?>>
                                    <label for="task-<?= $task['id'] ?>" class="task-label <?= $task['status'] == 'complete' ? 'line-through' : '' ?>">
                                        <?= htmlspecialchars($task['task_name']) ?>
                                    </label>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
            <button class="mt-4 p-2 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg" id="add-task-btn">+ Add task</button>
        </div>

        <!-- Task Details Section -->
        <div class="w-2/3 bg-gray-100 p-6 rounded-lg shadow-lg" id="task-details">
            <div class="flex justify-between">
                <h2 class="text-2xl font-semibold">Task Details</h2>
            </div>
            <div class="bg-white p-4 mt-6 rounded-lg shadow-lg">
                <p class="text-gray-600">Please select a task to see the details.</p>
            </div>
        </div>
    </div>

    <!-- Modal untuk menambah task (hidden awalnya) -->
    <div id="add-task-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-4 rounded-lg shadow-lg w-1/3 relative" id="modal-content">
            <h2 class="text-xl font-semibold mb-4">Add New Task</h2>
            <form id="add-task-form">
            <input type="text" name="task_name" placeholder="Task Name" class="p-2 border rounded-md w-full mb-3" required>
            <textarea name="notes" placeholder="Notes" class="p-2 border rounded-md w-full mb-3"></textarea>
            <input type="date" name="due_date" class="p-2 border rounded-md w-full mb-3" required>
            <button type="submit" class="bg-blue-600 text-white p-2 rounded-md w-full">Add Task</button>
            </form>
        </div>
    </div>

    <script>
        // Fungsi untuk load task details via AJAX
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
                            <p class="text-gray-600">Due Date: ${data.due_date}</p> <!-- Menambahkan tanggal jatuh tempo -->
                            <h4 class="text-lg font-semibold mt-4">Notes</h4>
                            <p class="text-gray-600">${data.notes}</p>
                        </div>
                    `;
                });
        }

        // Fungsi untuk tandai task sebagai selesai via AJAX
        function markTaskComplete(taskId) {
            fetch(`mark_complete.php?task_id=${taskId}`, { method: 'POST' })
                .then(() => {
                    document.getElementById(`task-${taskId}`).checked = true;
                    loadTaskDetails(taskId);  // Refresh task details
                });
        }

        // Menampilkan modal add task
        document.getElementById('add-task-btn').addEventListener('click', () => {
            document.getElementById('add-task-modal').classList.remove('hidden');
        });

        // Tutup modal saat mengklik di luar form
        document.getElementById('add-task-modal').addEventListener('click', (e) => {
            const modalContent = document.getElementById('modal-content');
            if (!modalContent.contains(e.target)) {
                document.getElementById('add-task-modal').classList.add('hidden');
            }
        });

        // Handle submit form untuk menambah task
        document.getElementById('add-task-form').addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            fetch('add_task.php', {
                method: 'POST',
                body: formData
            }).then(() => {
                window.location.reload();  // Reload halaman setelah menambah task
            });
        });

        // Update tampilan checklist
        document.querySelectorAll('.task-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const taskId = this.getAttribute('data-task-id');
                const label = document.querySelector(`label[for="task-${taskId}"]`);
                
                if (this.checked) {
                    label.classList.add('line-through');
                } else {
                    label.classList.remove('line-through');
                }
            });
        });
    </script>
</body>
</html>
