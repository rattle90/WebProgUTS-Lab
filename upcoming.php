<?php
ob_start();
session_start(); // Mulai session
include 'db.php';
include 'component/navbar.php';

// Pastikan pengguna telah login dan ID pengguna ada di session
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id']; // Ambil ID pengguna dari session

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Get the current date and set the start and end date
$today = date('Y-m-d');
$start_date = $today; // Set start date to today
$end_date = date('Y-m-d', strtotime('+1 month', strtotime($start_date))); // One month ahead

// Get selected month or default to the current month
$selected_month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');

// If a month is selected, adjust the start and end date accordingly
if ($selected_month) {
    $start_date = $selected_month . '-01';
    $end_date = date('Y-m-t', strtotime($start_date)); // Last date of the selected month
    if ($selected_month === date('Y-m')) {
        $start_date = $today;
    }
}

// Fetch tasks for the selected range from the database for the logged-in user
$query = $pdo->prepare("SELECT * FROM tasks WHERE user_id = :user_id AND due_date BETWEEN :start_date AND :end_date ORDER BY due_date ASC");
$query->execute(['user_id' => $userId, 'start_date' => $start_date, 'end_date' => $end_date]);
$tasks = $query->fetchAll(PDO::FETCH_ASSOC);

// Group tasks by date
$grouped_tasks = [];
foreach ($tasks as $task) {
    $due_date = $task['due_date'];
    if (!isset($grouped_tasks[$due_date])) {
        $grouped_tasks[$due_date] = [];
    }
    $grouped_tasks[$due_date][] = $task;
}

// Get all months for the dropdown
$months = [];
$current_year_month = date('Y-m');
for ($i = 0; $i <= 6; $i++) { // Show current month and 6 months ahead
    $next_month = date('Y-m', strtotime("+$i month", strtotime($current_year_month)));
    $months[] = $next_month;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Dashboard - Upcoming</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .scroll-to {
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .scroll-to:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }
        .main-content {
            padding-top: 110px;
        }
        .disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>

<body class="bg-white h-screen">
    <div class="main-content max-w-4xl mx-auto py-10">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-black">Upcoming Tasks</h1>
            <form action="" method="GET">
                <label for="month" class="text-lg font-bold">Select Month: </label>
                <select name="month" id="month" class="p-2 border border-gray-300 rounded-md" onchange="this.form.submit()">
                    <?php foreach ($months as $month): ?>
                        <option value="<?= $month ?>" <?= $selected_month == $month ? 'selected' : '' ?>>
                            <?= date('F Y', strtotime($month)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <div class="flex justify-start space-x-2 overflow-x-auto pb-4">
        <?php
        $current_day = strtotime($start_date);
        while ($current_day <= strtotime($end_date)) {
            $formatted_day = date('D j', $current_day);
            $id_day = date('Y-m-d', $current_day);
            $is_disabled = ($current_day < strtotime('today')) ? 'disabled' : '';

            echo '<div class="w-20 text-center scroll-to ' . $is_disabled . '" data-target="#task-' . $id_day . '-group" ' . ($is_disabled ? 'style="pointer-events:none;"' : '') . '>';
            echo '<div class="text-center text-sm font-bold">' . date('D', $current_day) . '</div>';
            echo '<div class="text-center text-lg font-bold">' . date('j', $current_day) . '</div>';
            echo '</div>';

            $current_day = strtotime('+1 day', $current_day);
        }
        ?>
    </div>


        <?php
        $current_day = strtotime($start_date);
        while ($current_day <= strtotime($end_date)) {
            $id_day = date('Y-m-d', $current_day);
            ?>
            <div class="mt-8" id="task-<?= htmlspecialchars($id_day) ?>-group">
                <h2 class="text-base font-black"><?= date('l, j M', $current_day) ?></h2>
                <hr class="border-t border-gray-300 w-full my-2">
                <ul class="space-y-4">
                    <?php if (isset($grouped_tasks[$id_day])): ?>
                        <?php foreach ($grouped_tasks[$id_day] as $task): ?>
                            <li class="flex items-center justify-between bg-white p-4 rounded-lg shadow-md">
                                <div>
                                    <input type="checkbox" id="task-<?= $task['id'] ?>" class="mr-3 task-checkbox" data-task-id="<?= $task['id'] ?>" <?= $task['is_completed'] ? 'checked' : '' ?>>
                                    <label for="task-<?= $task['id'] ?>" class="text-gray-700 <?= $task['is_completed'] ? 'line-through text-gray-400' : '' ?>"><?= htmlspecialchars($task['task_name']) ?></label>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="flex items-center justify-between bg-white p-4 rounded-lg shadow-md">
                            <div class="text-gray-500">No tasks for this day.</div>
                        </li>
                    <?php endif; ?>
                </ul>
                <button class="flex items-center text-gray-500 hover:text-gray-700 mt-4" id="add-task-btn-<?= htmlspecialchars($id_day) ?>">
                    <span class="text-red-500 text-xl mr-2">+</span>
                    <span>Add task</span>
                </button>
            </div>
            <?php
            $current_day = strtotime('+1 day', $current_day);
        }
        ?>
    </div>

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
        document.querySelectorAll('.scroll-to').forEach(item => {
            item.addEventListener('click', function () {
                const target = document.querySelector(this.getAttribute('data-target'));
                if (target) {
                    const offset = 110; 
                    const elementPosition = target.getBoundingClientRect().top + window.scrollY;
                    window.scrollTo({
                        top: elementPosition - offset,
                        behavior: 'smooth'
                    });
                }
            });
        });

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
            formData.append('user_id', '<?= $userId ?>'); // Tambahkan ID pengguna ke form data

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

        document.querySelectorAll('.task-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const taskId = this.getAttribute('data-task-id');
                const url = this.checked ? 'mark_complete.php?task_id=' + taskId : 'mark_uncomplete.php?task_id=' + taskId;

                fetch(url, {
                    method: 'POST',
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const label = document.querySelector(`label[for="task-${taskId}"]`);
                        if (this.checked) {
                            label.classList.add('line-through', 'text-gray-400');
                        } else {
                            label.classList.remove('line-through', 'text-gray-400');
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
