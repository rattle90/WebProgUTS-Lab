<?php
ob_start();
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

function groupTasksByDueDate($tasks)
{
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

function calculateProgress($tasks)
{
    if (empty($tasks))
        return 100;
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
        body {
            font-family: 'Poppins', sans-serif;
        }

        .task {
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: white;
            border-radius: 8px;
            cursor: pointer;
        }

        .task:hover {
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease-in-out;
        }
    </style>
</head>

<body class="bg-gray-100 pt-28">

    <div class="container mx-auto px-4">
        <div>
            <h1 class="text-3xl font-semibold text-gray-800 mb-2"><span class="auto-type"> </span></h1>
            <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
            <script>
        var typed = new Typed(".auto-type" , {
            strings : 
                    ["Halo, <?= htmlspecialchars($_SESSION['username']) ?>!",
                    "Hi, <?= htmlspecialchars($_SESSION['username']) ?>!",
                    "Hola, <?= htmlspecialchars($_SESSION['username']) ?>!",
                    "嗨, <?= htmlspecialchars($_SESSION['username']) ?>!",
                    ],   
            typeSpeed: 50,      
            backSpeed: 10,      
            loop: true,        
            startDelay: 50,    
            backDelay: 100,    
        })
    </script>
        </div>
        <p class="text-gray-600 text-left mb-4">“Tetapi kamu ini, kuatkanlah hatimu, jangan lemah semangatmu, karena ada
            upah bagi usahamu!”</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach (['today', 'tomorrow', 'this_week'] as $group): ?>
                <div class="bg-white shadow-lg rounded-lg p-4 task-group" data-group="<?= $group ?>">
                    <h2 class="text-xl font-semibold text-gray-700"><?= ucfirst(str_replace('_', ' ', $group)) ?></h2>

                    <?php $progress = calculateProgress($groupedTasks[$group]); ?>
                    <div class="relative mt-2">
                        <div class="bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: <?= $progress ?>%;"></div>
                        </div>
                        <span
                            class="absolute right-0 text-xs text-gray-500 font-semibold progress-text"><?= round($progress) ?>%
                            completed</span>
                    </div>

                    <ul class="mt-4 space-y-4">
                        <?php if (!empty($groupedTasks[$group])): ?>
                            <?php foreach ($groupedTasks[$group] as $task): ?>
                                <li class="task flex justify-between items-center" data-id="<?= $task['id'] ?>">
                                    <div class="flex items-center space-x-4">
                                        <input id="checkbox-<?= $task['id'] ?>" type="checkbox" <?= $task['is_completed'] ? 'checked' : '' ?> onchange="toggleTaskStatus(<?= $task['id'] ?>, '<?= $group ?>')">
                                        <label for="checkbox-<?= $task['id'] ?>"
                                            class="<?= $task['is_completed'] ? 'line-through text-gray-400' : 'text-gray-800' ?>">
                                            <?= htmlspecialchars($task['task_name']) ?>
                                        </label>
                                    </div>
                                    <span
                                        class="text-gray-500 text-sm"><?= (new DateTime($task['due_date']))->format('d M') ?></span>
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
        <div class="mt-6 bg-white shadow-lg rounded-lg p-4 task-group md:col-span-3" data-group="later">
            <h2 class="text-xl font-semibold text-gray-700">Later</h2>

            <?php $progress = calculateProgress($groupedTasks['later']); ?>
            <div class="relative mt-2">
                <div class="bg-gray-200 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full" style="width: <?= $progress ?>%;"></div>
                </div>
                <span
                    class="absolute right-0 text-xs text-gray-500 font-semibold progress-text"><?= round($progress) ?>%
                    completed</span>
            </div>

            <ul class="mt-4 space-y-4">
                <?php if (!empty($groupedTasks['later'])): ?>
                    <?php foreach ($groupedTasks['later'] as $task): ?>
                        <li class="task flex justify-between items-center" data-id="<?= $task['id'] ?>">
                            <div class="flex items-center space-x-4">
                                <input id="checkbox-<?= $task['id'] ?>" type="checkbox" <?= $task['is_completed'] ? 'checked' : '' ?> onchange="toggleTaskStatus(<?= $task['id'] ?>, 'later')">
                                <label for="checkbox-<?= $task['id'] ?>"
                                    class="<?= $task['is_completed'] ? 'line-through text-gray-400' : 'text-gray-800' ?>">
                                    <?= htmlspecialchars($task['task_name']) ?>
                                </label>
                            </div>
                            <span class="text-gray-500 text-sm"><?= (new DateTime($task['due_date']))->format('d M Y') ?></span>
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
        function toggleTaskStatus(taskId, group) {
            const checkbox = document.getElementById(`checkbox-${taskId}`);
            if (checkbox.checked) {
                markTaskComplete(taskId, group);
            } else {
                markTaskUncomplete(taskId, group);
            }
        }

        function markTaskComplete(taskId, group) {
            fetch(`mark_complete.php?task_id=${taskId}`, { method: 'POST' })
                .then(() => {
                    const checkbox = document.getElementById(`checkbox-${taskId}`);
                    checkbox.checked = true;
                    const label = document.querySelector(`label[for="checkbox-${taskId}"]`);
                    label.classList.add('line-through');
                    label.classList.add('text-gray-400');
                    updateProgress(group);
                })
                .catch(error => console.error('Error:', error));
        }

        function markTaskUncomplete(taskId, group) {
            fetch(`mark_uncomplete.php?task_id=${taskId}`, { method: 'POST' })
                .then(() => {
                    const checkbox = document.getElementById(`checkbox-${taskId}`);
                    checkbox.checked = false;
                    const label = document.querySelector(`label[for="checkbox-${taskId}"]`);
                    label.classList.remove('line-through');
                    label.classList.remove('text-gray-400');
                    updateProgress(group);
                })
                .catch(error => console.error('Error:', error));
        }

        function updateProgress(group) {
            const tasks = document.querySelectorAll(`.task-group[data-group="${group}"] .task input[type="checkbox"]`);
            let completedCount = 0;
            tasks.forEach(task => {
                if (task.checked) {
                    completedCount++;
                }
            });
            const progress = tasks.length > 0 ? (completedCount / tasks.length) * 100 : 0;
            const progressBar = document.querySelector(`.task-group[data-group="${group}"] .bg-green-500`);
            const progressText = document.querySelector(`.task-group[data-group="${group}"] .progress-text`);

            progressBar.style.width = `${progress}%`;
            progressText.innerText = `${Math.round(progress)}% completed`;
        }

        function openModal(taskId) {
            const modal = document.getElementById('task-modal');
            const content = document.getElementById('task-content');

            // Fetch task details from server (implement this part)
            fetch(`get_task.php?task_id=${taskId}`)
                .then(response => response.json())
                .then(data => {
                    content.innerHTML = `
                    <h3 class="text-lg font-semibold">${data.task_name}</h3>
                    <p>Due Date: ${data.due_date}</p>
                    <p>Status: ${data.is_completed ? 'Completed' : 'Pending'}</p>
                `;
                    modal.classList.remove('hidden');
                })
                .catch(error => console.error('Error fetching task details:', error));
        }

        function closeModal() {
            const modal = document.getElementById('task-modal');
            modal.classList.add('hidden');
        }
    </script>
</body>

</html>