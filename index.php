<?php
session_start(); 
include 'db.php'; 
include 'component/navbar.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); 
    exit();
}

$user_id = $_SESSION['user_id']; 

$query = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ?");
$query->execute([$user_id]);
$tasks = $query->fetchAll(PDO::FETCH_ASSOC);

function groupTasksByDueDate($tasks) {
    $groupedTasks = ['today' => [], 'tomorrow' => [], 'this_week' => [], 'later' => []];
    $today = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
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

function calculateProgress($tasks) {
    if (empty($tasks)) return 100;
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
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .task { padding: 1rem; margin-bottom: 1rem; background-color: white; border-radius: 8px; cursor: pointer; }
        .task:hover { box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1); transition: box-shadow 0.3s ease-in-out; }
    </style>
</head>
<body class="bg-gray-100 pt-28">

<div class="container mx-auto px-4">
    <h1 class="text-3xl font-semibold text-gray-800 mb-2">Halo, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
    <p class="text-gray-600 text-left mb-4">“Tetapi kamu ini, kuatkanlah hatimu, jangan lemah semangatmu, karena ada upah bagi usahamu!”</p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php foreach (['today', 'tomorrow', 'this_week'] as $group): ?>
            <div class="bg-white shadow-lg rounded-lg p-4 task-group">
                <h2 class="text-xl font-semibold text-gray-700"><?= ucfirst(str_replace('_', ' ', $group)) ?></h2>
                
                <?php $progress = calculateProgress($groupedTasks[$group]); ?>
                <div class="relative mt-2">
                    <div class="bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: <?= $progress ?>%;"></div>
                    </div>
                    <span class="absolute right-0 text-xs text-gray-500 font-semibold"><?= round($progress) ?>% completed</span>
                </div>
                
                <ul class="mt-4 space-y-4">
                    <?php if (!empty($groupedTasks[$group])): ?>
                        <?php foreach ($groupedTasks[$group] as $task): ?>
                            <li class="task flex justify-between items-center" data-id="<?= $task['id'] ?>">
                                <input type="checkbox" <?= $task['is_completed'] ? 'checked' : '' ?>>
                                <label class="<?= $task['is_completed'] ? 'line-through text-gray-400' : 'text-gray-800' ?>">
                                    <?= htmlspecialchars($task['task_name']) ?>
                                </label>
                                <button onclick="openModal(<?= $task['id'] ?>)" class="text-blue-500">View</button>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="text-gray-400">No tasks</li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Later Task Group -->
    <div class="mt-6 bg-white shadow-lg rounded-lg p-4 task-group md:col-span-3">
        <h2 class="text-xl font-semibold text-gray-700">Later</h2>
        
        <?php $progress = calculateProgress($groupedTasks['later']); ?>
        <div class="relative mt-2">
            <div class="bg-gray-200 rounded-full h-2">
                <div class="bg-green-500 h-2 rounded-full" style="width: <?= $progress ?>%;"></div>
            </div>
            <span class="absolute right-0 text-xs text-gray-500 font-semibold"><?= round($progress) ?>% completed</span>
        </div>
        
        <ul class="mt-4 space-y-4">
            <?php if (!empty($groupedTasks['later'])): ?>
                <?php foreach ($groupedTasks['later'] as $task): ?>
                    <li class="task flex justify-between items-center" data-id="<?= $task['id'] ?>">
                        <input type="checkbox" <?= $task['is_completed'] ? 'checked' : '' ?>>
                        <label class="<?= $task['is_completed'] ? 'line-through text-gray-400' : 'text-gray-800' ?>">
                            <?= htmlspecialchars($task['task_name']) ?>
                        </label>
                        <button onclick="openModal(<?= $task['id'] ?>)" class="text-blue-500">View</button>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="text-gray-400">No tasks</li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<!-- Modal Popup -->
<div id="task-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
        <h2 class="text-xl font-semibold mb-4">Task Details</h2>
        <div id="task-content">
            <p>Select a task to view details.</p>
        </div>
        <button onclick="closeModal()" class="bg-red-500 text-white px-4 py-2 mt-4">Close</button>
    </div>
</div>

<script>
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const taskId = this.closest('.task').dataset.id;
            const isCompleted = this.checked ? 1 : 0;
            const url = isCompleted ? 'mark_complete.php' : 'mark_uncomplete.php';

            fetch(url + '?task_id=' + taskId, { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const label = this.nextElementSibling;
                        label.classList.toggle('line-through', isCompleted);
                        label.classList.toggle('text-gray-400', isCompleted);
                    } else {
                        alert('Failed to update task status.');
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });

    function openModal(taskId) {
        fetch('get_task.php?task_id=' + taskId)
            .then(response => response.json())
            .then(task => {
                document.getElementById('task-content').innerHTML = `
                    <p><strong>Name:</strong> ${task.task_name}</p>
                    <p><strong>Notes:</strong> ${task.notes || 'No notes.'}</p>
                    <p><strong>Due Date:</strong> ${new Date(task.due_date).toLocaleDateString()}</p>
                `;
                document.getElementById('task-modal').classList.remove('hidden');
            })
            .catch(error => console.error('Error:', error));
    }

    function closeModal() {
        document.getElementById('task-modal').classList.add('hidden');
    }
</script>

</body>
</html>
