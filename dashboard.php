<?php
include 'db.php'; // Koneksi database
include 'component/navbar.php';

// Ambil semua task dari database dan urutkan berdasarkan kategori
$query = $pdo->query("SELECT * FROM tasks ORDER BY category");
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

// Fungsi untuk menghitung progress
function calculateProgress($tasks) {
    $total = count($tasks);
    $completed = array_filter($tasks, fn($task) => $task['is_completed']);
    return $total > 0 ? (count($completed) / $total) * 100 : 0;
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .dark-mode { background-color: #1a202c; color: white; }
        .task { padding: 1rem; margin-bottom: 1rem; background-color: white; border-radius: 8px; cursor: pointer; }
        .task:hover { box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1); transition: box-shadow 0.3s ease-in-out; }
    </style>
</head>
<body class="bg-gray-100 pt-28">

    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-semibold text-gray-800 mb-6">My Task Overview</h1>

        <!-- Filter -->
        <div class="flex justify-between mb-6">
            <select class="form-select" id="categoryFilter">
                <option value="">All Categories</option>
                <option value="work">Work</option>
                <option value="personal">Personal</option>
            </select>
        </div>

        <!-- Dark Mode Toggle -->
        <button id="toggleDarkMode" class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded mb-4">Toggle Dark Mode</button>

        <!-- Search Bar -->
        <input type="text" id="taskSearch" placeholder="Search tasks..." class="p-2 border rounded mb-4 w-full">

        <!-- Task Grids -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Today's Tasks -->
            <div class="bg-white shadow-lg rounded-lg p-4 task-group">
                <h2 class="text-xl font-semibold text-gray-700">Today's Tasks</h2>
                <div class="relative pt-1">
                    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-green-200">
                        <div style="width:<?= calculateProgress($groupedTasks['today']) ?>%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-500"></div>
                    </div>
                    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-green-600 bg-green-200">
                        <?= round(calculateProgress($groupedTasks['today'])) ?>% Completed
                    </span>
                </div>
                <ul id="sortable-today" class="mt-4 space-y-4">
                    <?php if (!empty($groupedTasks['today'])): ?>
                        <?php foreach ($groupedTasks['today'] as $task): ?>
                            <li draggable="true" class="flex items-center justify-between p-3 border-b task" data-id="<?= $task['id'] ?>">
                                <input type="checkbox" id="task-<?= $task['id'] ?>" <?= $task['is_completed'] ? 'checked' : '' ?>>
                                <label for="task-<?= $task['id'] ?>" class="<?= $task['is_completed'] ? 'line-through text-gray-400' : 'text-gray-800' ?>"><?= htmlspecialchars($task['task_name']) ?></label>
                                <span class="text-gray-500"><?= (new DateTime($task['due_date']))->format('M d') ?></span>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="text-gray-400">No tasks for today</li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Tomorrow's Tasks -->
            <div class="bg-white shadow-lg rounded-lg p-4 task-group">
                <h2 class="text-xl font-semibold text-gray-700">Tomorrow's Tasks</h2>
                <ul id="sortable-tomorrow" class="mt-4 space-y-4">
                    <?php if (!empty($groupedTasks['tomorrow'])): ?>
                        <?php foreach ($groupedTasks['tomorrow'] as $task): ?>
                            <li draggable="true" class="flex items-center justify-between p-3 border-b task" data-id="<?= $task['id'] ?>">
                                <input type="checkbox" id="task-<?= $task['id'] ?>" <?= $task['is_completed'] ? 'checked' : '' ?>>
                                <label for="task-<?= $task['id'] ?>" class="<?= $task['is_completed'] ? 'line-through text-gray-400' : 'text-gray-800' ?>"><?= htmlspecialchars($task['task_name']) ?></label>
                                <span class="text-gray-500"><?= (new DateTime($task['due_date']))->format('M d') ?></span>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="text-gray-400">No tasks for tomorrow</li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Upcoming Tasks -->
            <div class="bg-white shadow-lg rounded-lg p-4 task-group">
                <h2 class="text-xl font-semibold text-gray-700">Upcoming Tasks</h2>
                <ul id="sortable-upcoming" class="mt-4 space-y-4">
                    <?php if (!empty($groupedTasks['this_week'])): ?>
                        <?php foreach ($groupedTasks['this_week'] as $task): ?>
                            <li draggable="true" class="flex items-center justify-between p-3 border-b task" data-id="<?= $task['id'] ?>">
                                <input type="checkbox" id="task-<?= $task['id'] ?>" <?= $task['is_completed'] ? 'checked' : '' ?>>
                                <label for="task-<?= $task['id'] ?>" class="<?= $task['is_completed'] ? 'line-through text-gray-400' : 'text-gray-800' ?>"><?= htmlspecialchars($task['task_name']) ?></label>
                                <span class="text-gray-500"><?= (new DateTime($task['due_date']))->format('M d') ?></span>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="text-gray-400">No upcoming tasks</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <!-- Later Tasks -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Later Tasks</h2>
            <ul id="sortable-later" class="space-y-4">
                <?php if (!empty($groupedTasks['later'])): ?>
                    <?php foreach ($groupedTasks['later'] as $task): ?>
                        <li draggable="true" class="flex items-center justify-between p-3 border-b task" data-id="<?= $task['id'] ?>">
                            <input type="checkbox" id="task-<?= $task['id'] ?>" <?= $task['is_completed'] ? 'checked' : '' ?>>
                            <label for="task-<?= $task['id'] ?>" class="<?= $task['is_completed'] ? 'line-through text-gray-400' : 'text-gray-800' ?>"><?= htmlspecialchars($task['task_name']) ?></label>
                            <span class="text-gray-500"><?= (new DateTime($task['due_date']))->format('M d') ?></span>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="text-gray-400">No later tasks</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <script>
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const taskId = this.closest('.task').dataset.id;
                const isCompleted = this.checked ? 1 : 0;

                fetch('mark_complete.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + taskId + '&is_completed=' + isCompleted
                })
                .then(response => response.text())
                .then(data => console.log(data))
                .catch(error => console.error('Error:', error));
            });
        });

        document.getElementById('toggleDarkMode').addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
        });
    </script>
</body>
</html>
