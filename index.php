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
        'later' => [],
    ];

    $today = new DateTime('now', new DateTimeZone('Asia/Jakarta')); // Mengatur timezone sesuai
    $tomorrow = (clone $today)->modify('+1 day');
    $endOfWeek = (clone $today)->modify('Sunday this week');

    foreach ($tasks as $task) {
        $dueDate = new DateTime($task['due_date']);
        
        if ($dueDate->format('Y-m-d') === $today->format('Y-m-d')) {
            $groupedTasks['today'][] = $task;
        } elseif ($dueDate->format('Y-m-d') === $tomorrow->format('Y-m-d')) {
            $groupedTasks['tomorrow'][] = $task;
        } elseif ($dueDate > $today && $dueDate <= $endOfWeek) {
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

        <!-- Task Grids -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Today's Tasks -->
            <div class="bg-white shadow-lg rounded-lg p-4 task-group">
                <h2 class="text-xl font-semibold text-gray-700">Today's Tasks</h2>
                <div class="relative pt-1">
                    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-green-200">
                        <div style="width:<?= calculateProgress($groupedTasks['today']) ?>%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-500"></div>
                    </div>
                    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-green-600">
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
                <h2 class="text-xl font-semibold text-gray-700">This Week's Tasks</h2>
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
                const taskId = this.closest('.task').dataset.id;  // Ambil task ID dari atribut data-id
                const isCompleted = this.checked ? 1 : 0;  // Cek status checkbox

                // Tentukan URL yang sesuai berdasarkan status is_completed
                const url = isCompleted ? 'mark_complete.php' : 'mark_uncomplete.php';

                // Kirimkan request ke server
                fetch(url + '?task_id=' + taskId, {
                    method: 'POST',
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const label = document.querySelector(`label[for="task-${taskId}"]`);
                        if (isCompleted) {
                            label.classList.add('line-through', 'text-gray-400');  // Ubah gaya teks jika selesai
                            console.log("Task marked as completed.");
                        } else {
                            label.classList.remove('line-through', 'text-gray-400');  // Kembalikan gaya teks jika belum selesai
                            console.log("Task marked as uncompleted.");
                        }
                    } else {
                        console.error("Failed to update task status.");
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>

</body>
</html>
