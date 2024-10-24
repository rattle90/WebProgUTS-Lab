<?php
ob_start();
session_start(); 
include 'db.php';
include 'component/navbar.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$today = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
$todayFormatted = $today->format('Y-m-d');

$userId = $_SESSION['user_id'];
$query = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? AND due_date BETWEEN ? AND DATE_ADD(?, INTERVAL 7 DAY)");
$query->execute([$userId, $todayFormatted, $todayFormatted]);
$tasks = $query->fetchAll(PDO::FETCH_ASSOC);

$tasksByDate = [];
foreach ($tasks as $task) {
    $dueDate = $task['due_date'];
    if (!isset($tasksByDate[$dueDate])) {
        $tasksByDate[$dueDate] = [];
    }
    $tasksByDate[$dueDate][] = $task;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['id']) && isset($input['is_completed'])) {
        $taskId = $input['id'];
        $isCompleted = $input['is_completed'] ? 1 : 0;

        $updateQuery = $pdo->prepare("UPDATE tasks SET is_completed = ? WHERE id = ? AND user_id = ?");
        $updateQuery->execute([$isCompleted, $taskId, $userId]);

        echo json_encode(['success' => true]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Next 7 Days Tasks</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .completed {
            text-decoration: line-through;
            color: gray;
        }
        .today-label {
            color: #FF6347;
            font-weight: bold;
            margin-left: 10px;
        }
    </style>
</head>
<body class="bg-blue-600 h-screen p-5 pt-24">

    <div class="container mx-auto mt-5">
        <h1 class="text-white text-3xl font-bold mb-6">Tasks for the Next 7 Days</h1>
        <div class="flex space-x-4 overflow-x-auto">
            <?php
            for ($i = 0; $i < 7; $i++) {
                $date = clone $today;
                $date->modify("+$i day");

                $dayName = $date->format('l');
                $formattedDate = $date->format('Y-m-d');

                echo '<div class="bg-white p-4 rounded-lg shadow-md w-64 flex-none max-h-72 overflow-y-auto">';
                echo "<h2 class='text-lg font-semibold text-gray-800'>$dayName"; 
                if ($formattedDate === $todayFormatted) {
                    echo "<span class='today-label'> Today</span>";
                }
                echo "</h2>";
                echo "<p class='text-gray-500'>$formattedDate</p>";
                echo "<ul data-date='$formattedDate'>";

                if (isset($tasksByDate[$formattedDate]) && count($tasksByDate[$formattedDate]) > 0) {
                    foreach ($tasksByDate[$formattedDate] as $task) {
                        $isChecked = $task['is_completed'] ? 'checked' : '';
                        $completedClass = $task['is_completed'] ? 'completed' : '';
                        echo "<li id='task-{$task['id']}' class='flex items-center justify-between'>";
                        echo "<input type='checkbox' id='checkbox-{$task['id']}' onchange='toggleCompletion({$task['id']})' class='mr-2' $isChecked>";
                        echo "<span class='task-name $completedClass'>{$task['task_name']}</span>";
                        echo "</li>";
                    }
                } else {
                    echo "<li class='text-gray-500'>No tasks</li>";
                }
                echo "</ul>";
                echo "<button class='flex items-center text-gray-500 hover:text-gray-700 mt-4' id='add-task-btn-$formattedDate'>";
                echo "<span class='text-red-500 text-xl mr-2'>+</span><span>Add task</span>";
                echo "</button>";
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <!-- Modal -->
    <div id="add-task-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-2/3 relative" id="modal-content">
            <form id="add-task-form">
                <div class="mb-4">
                    <input type="text" name="task_name" placeholder="Task name" class="p-2 text-lg font-semibold border-b w-full focus:outline-none mb-2" required>
                    <textarea id="notes" placeholder="Notes" name="notes" class="p-2 border rounded-md w-full"></textarea>
                    <input type="hidden" id="due-date-input" class="p-2 bg-transparent border-none opacity-0 pointer-events-none" name="due_date" readonly>
                </div>
                <div class="flex space-x-2">
                    <button type="button" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-md" id="cancel-task-btn">Cancel</button>
                    <button type="submit" class="bg-red-400 text-white px-4 py-2 rounded-md">Add task</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('[id^="add-task-btn-"]').forEach(button => {
            button.addEventListener('click', function () {
                const dateId = this.id.split('-').slice(3).join('-');
                document.getElementById('due-date-input').value = dateId;
                document.getElementById('add-task-modal').classList.remove('hidden');
            });
        });

        document.getElementById('add-task-modal').addEventListener('click', (e) => {
            const modalContent = document.getElementById('modal-content');
            if (!modalContent.contains(e.target)) {
                document.getElementById('add-task-modal').classList.add('hidden');
            }
        });

        document.getElementById('cancel-task-btn').addEventListener('click', function () {
            document.getElementById('add-task-modal').classList.add('hidden');
        });

        document.getElementById('add-task-form').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            formData.append('user_id', '<?= $userId ?>');

            fetch('add_task.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error adding task: ' + data.message);
                }
            });
        });

        function toggleCompletion(taskId) {
            const checkbox = document.getElementById(`checkbox-${taskId}`);
            const isChecked = checkbox.checked;
            const taskNameElement = document.querySelector(`#task-${taskId} .task-name`);

            // Update UI directly
            if (isChecked) {
                markTaskComplete(taskId);
            } else {
                markTaskUncomplete(taskId);
            }
        }

        function markTaskComplete(taskId) {
            fetch(`mark_complete.php?task_id=${taskId}`, { method: 'POST' })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    const checkbox = document.getElementById(`checkbox-${taskId}`);
                    checkbox.checked = true; // Check the checkbox
                    const taskNameElement = document.querySelector(`#task-${taskId} .task-name`);
                    taskNameElement.classList.add('completed'); // Add line-through class
                })
                .catch(error => {
                    console.error('Error marking task complete:', error);
                    const checkbox = document.getElementById(`checkbox-${taskId}`);
                    checkbox.checked = false; // Revert checkbox if there's an error
                    const taskNameElement = document.querySelector(`#task-${taskId} .task-name`);
                    taskNameElement.classList.remove('completed'); // Revert line-through class
                });
        }

        function markTaskUncomplete(taskId) {
            fetch(`mark_uncomplete.php?task_id=${taskId}`, { method: 'POST' })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    const checkbox = document.getElementById(`checkbox-${taskId}`);
                    checkbox.checked = false; // Uncheck the checkbox
                    const taskNameElement = document.querySelector(`#task-${taskId} .task-name`);
                    taskNameElement.classList.remove('completed'); // Remove line-through class
                })
                .catch(error => {
                    console.error('Error marking task uncomplete:', error);
                    const checkbox = document.getElementById(`checkbox-${taskId}`);
                    checkbox.checked = true; // Revert checkbox if there's an error
                    const taskNameElement = document.querySelector(`#task-${taskId} .task-name`);
                    taskNameElement.classList.add('completed'); // Revert line-through class
                });
        }


    </script>

</body>
</html>
