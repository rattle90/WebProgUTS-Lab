<?php
include 'db.php'; // Koneksi database
include 'component/navbar.php';

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>





</head>
<body class="bg-blue-600 pt-20 h-screen">
    <div class="flex h-full w-11/12 space-x-6 mx-auto py-10">

        <div class="w-1/3 bg-gray-900 text-white p-4 rounded-lg shadow-lg flex flex-col">
            <div class="flex justify-between mb-4">
                <h1 class="text-xl font-semibold">All my tasks</h1>
            </div>
            <ul class="space-y-3 flex-1 overflow-y-auto" id="task-list">
                <?php foreach ($groupedTasks as $group => $tasks): ?>
                    <?php if (!empty($tasks)): ?>
                        <h2 class="text-lg font-semibold text-gray-300 mt-4"><?= ucfirst(str_replace('_', ' ', $group)) ?></h2>
                        <?php foreach ($tasks as $task): ?>
                            <li class="flex items-center justify-between bg-gray-700 p-3 rounded-md cursor-pointer hover:bg-gray-600">
                                <div class="flex items-center">
                                    <div class="w-1 h-full bg-green-500 mr-3"></div>
                                    <input type="checkbox" id="task-<?= $task['id'] ?>" class="mr-3 task-checkbox" data-task-id="<?= $task['id'] ?>" <?= $task['is_completed'] == 1 ? 'checked' : '' ?>>
                                    <label for="task-<?= $task['id'] ?>" class="task-label <?= $task['is_completed'] == 1 ? 'line-through' : '' ?>">
                                        <?= htmlspecialchars($task['task_name']) ?>
                                    </label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-gray-300"><?= (new DateTime($task['due_date']))->format('M d') ?></span>
                                    <button class="text-red-500 hover:text-red-700" onclick="deleteTask(<?= $task['id'] ?>, event)">Delete</button>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
            <button class="mt-4 p-2 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg" id="add-task-btn">+ Add task</button>
        </div>

        <div class="w-2/3 bg-gray-100 p-6 rounded-lg shadow-lg" id="task-details">
            <div class="flex justify-between">
                <h2 class="text-2xl font-semibold">Task Details</h2>
            </div>
            <div class="bg-white p-4 mt-6 rounded-lg shadow-lg">
                <p class="text-gray-600">Please select a task to see the details.</p>
            </div>
        </div>
    </div>

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
                            <p class="text-gray-600">Status: ${data.is_completed ? 'Completed' : 'Pending'}</p>
                            <p class="text-gray-600">Due Date: ${data.due_date}</p>
                            <h4 class="text-lg font-semibold mt-4">Notes</h4>
                            <p class="text-gray-600">${data.notes}</p>
                        </div>
                    `;
                });
        }

        function markTaskComplete(taskId) {
            fetch(`mark_complete.php?task_id=${taskId}`, { method: 'POST' })
                .then(() => {
                    const checkbox = document.getElementById(`task-${taskId}`);
                    checkbox.checked = true;
                    const label = document.querySelector(`label[for="task-${taskId}"]`);
                    label.classList.add('line-through');
                    loadTaskDetails(taskId);
                });
        }

        function markTaskUncomplete(taskId) {
            fetch(`mark_uncomplete.php?task_id=${taskId}`, { method: 'POST' })
                .then(() => {
                    const checkbox = document.getElementById(`task-${taskId}`);
                    checkbox.checked = false;
                    const label = document.querySelector(`label[for="task-${taskId}"]`);
                    label.classList.remove('line-through');
                    loadTaskDetails(taskId);
                });
        }

        document.getElementById('add-task-btn').addEventListener('click', () => {
            document.getElementById('add-task-modal').classList.remove('hidden');
        });

        document.getElementById('add-task-modal').addEventListener('click', (e) => {
            if (e.target === document.getElementById('add-task-modal')) {
                document.getElementById('add-task-modal').classList.add('hidden');
            }
        });

        document.getElementById('add-task-form').addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);

            fetch('add_task.php', {
                method: 'POST',
                body: formData,
            })
            .then(() => {
                location.reload();
            });
        });

        function deleteTask(taskId, event) {
            event.stopPropagation(); 
            Swal.fire({
                title: 'Are you sure?',
                text: "This task will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika user mengkonfirmasi, hapus task
                    fetch(`delete_task.php?task_id=${taskId}`, { method: 'POST' })
                        .then(response => response.json())
                        .then(result => {
                            if (result.success) {
                                const taskItem = document.getElementById(`task-${taskId}`).closest('li');
                                taskItem.remove(); // Hapus elemen task dari DOM

                                Swal.fire(
                                    'Deleted!',
                                    'Your task has been deleted.',
                                    'success'
                                );
                            } else {
                                Swal.fire(
                                    'Failed!',
                                    'There was an issue deleting the task.',
                                    'error'
                                );
                            }
                        });
                }
            });
        }





        document.querySelectorAll('.task-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', (e) => {
                const taskId = e.target.dataset.taskId;
                if (e.target.checked) {
                    markTaskComplete(taskId);
                } else {
                    markTaskUncomplete(taskId);
                }
            });
        });

        document.querySelectorAll('#task-list li').forEach(item => {
            item.addEventListener('click', () => {
                const taskId = item.querySelector('.task-checkbox').dataset.taskId;
                loadTaskDetails(taskId);
            });
        });
    </script>
</body>
</html>
