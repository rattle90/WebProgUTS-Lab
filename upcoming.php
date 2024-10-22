<?php
include 'db.php';
include 'component/navbar.php';

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Get selected month or default to the current month
$selected_month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
$start_date = $selected_month . '-01';
$end_date = date('Y-m-t', strtotime($start_date)); // Get the last date of the selected month

// Fetch tasks for the selected month from the database
$query = $pdo->prepare("SELECT * FROM tasks WHERE due_date BETWEEN :start_date AND :end_date ORDER BY due_date ASC");
$query->execute(['start_date' => $start_date, 'end_date' => $end_date]);
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
for ($i = -6; $i <= 6; $i++) { // Show 6 months before and after the current month
    $months[] = date('Y-m', strtotime("$i month"));
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
            /* Changed to Poppins */
        }

        .scroll-to {
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .scroll-to:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body class="bg-white h-screen">

    <div class="max-w-4xl mx-auto py-10">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-black">Upcoming Tasks</h1>

            <!-- Dropdown to select month -->
            <form action="" method="GET">
                <label for="month" class="text-lg font-bold">Select Month: </label>
                <select name="month" id="month" class="p-2 border border-gray-300 rounded-md"
                    onchange="this.form.submit()">
                    <?php foreach ($months as $month): ?>
                        <option value="<?= $month ?>" <?= $selected_month == $month ? 'selected' : '' ?>>
                            <?= date('F Y', strtotime($month)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <!-- Taskbar with days of the selected month -->
        <div class="flex space-x-4 overflow-x-auto pb-4">
            <?php
            $current_day = strtotime(date('Y-m-d')); // Today
            $end_day = strtotime('+6 days', $current_day); // 6 days ahead
            
            // Display dates from today to 6 days ahead
            while ($current_day <= $end_day) {
                $formatted_day = date('D j', $current_day); // Format display: Mon 21
                $id_day = date('Y-m-d', $current_day); // Used for scroll ID
            
                echo '<div class="scroll-to p-2" data-target="#task-' . $id_day . '">';
                echo '<div class="text-center text-sm font-bold">' . date('D', $current_day) . '</div>';
                echo '<div class="text-center text-lg font-bold">' . date('j', $current_day) . '</div>';
                echo '</div>';

                // Increment day by 1
                $current_day = strtotime('+1 day', $current_day);
            }
            ?>
        </div>

        <?php foreach ($grouped_tasks as $date => $tasks_for_date): ?>
            <div class="mt-8" id="task-<?= htmlspecialchars($date) ?>-group">
                <?php
                // Format the date to display day name, date, and month abbreviation (e.g., Senin, 21 Okt)
                $formatted_date = date('l, j M', strtotime($date)); // 'l' is for full day name
                ?>
                <h2 class="text-base font-black">
                    <?= htmlspecialchars($formatted_date) ?> <!-- Display day, date, and month abbreviation -->
                </h2>
                <hr class="border-t border-gray-300 w-full my-2">
                <ul class="space-y-4">
                    <?php foreach ($tasks_for_date as $task): ?>
                        <li class="flex items-center justify-between bg-white p-4 rounded-lg shadow-md">
                            <div>
                                <input type="checkbox" id="task-<?= $task['id'] ?>" class="mr-3 task-checkbox"
                                    data-task-id="<?= $task['id'] ?>" <?= $task['is_completed'] ? 'checked' : '' ?>>
                                <label for="task-<?= $task['id'] ?>"
                                    class="text-gray-700"><?= htmlspecialchars($task['task_name']) ?></label>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <button class="flex items-center text-gray-500 hover:text-gray-700 mt-4" id="add-task-btn-2024-10-20">
                    <span class="text-red-500 text-xl mr-2">+</span>
                    <span>Add task</span>
                </button>

            </div>
        <?php endforeach; ?>


        <!-- If no tasks for selected month -->
        <?php if (empty($grouped_tasks)): ?>
            <p class="text-gray-500 mt-10">No tasks found for this month.</p>
        <?php endif; ?>
    </div>

    <!-- Add Task Modal -->
    <div id="add-task-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-2/3 relative" id="modal-content">
            <form id="add-task-form">
                <div class="mb-4">
                    <input type="text" name="task_name" placeholder="Task name"
                        class="p-2 text-lg font-semibold border-b w-full focus:outline-none mb-2" required>
                    <textarea id="notes" placeholder="Notes" name="notes" class="p-2 border rounded-md w-full"></textarea>

                    <input type="hidden" id="due-date-input" class="p-2 bg-transparent border-none opacity-0 pointer-events-none" name="due_date" readonly>
                    </div>
                <div class="flex space-x-2">
                    <button type="button" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-md"
                        id="cancel-task-btn">Cancel</button>
                    <button type="submit" class="bg-red-400 text-white px-4 py-2 rounded-md">Add task</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Scroll to task section when date is clicked in the taskbar
        document.querySelectorAll('.scroll-to').forEach(item => {
            item.addEventListener('click', function () {
                const target = document.querySelector(this.getAttribute('data-target'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });

        document.querySelectorAll('[id^="add-task-btn-"]').forEach(button => {
            button.addEventListener('click', function () {
                const dateId = this.id.split('-').slice(3).join('-'); // Mengambil tanggal dari ID tombol
                document.getElementById('due-date-input').value = dateId; // Mengatur nilai due date di input
                document.getElementById('add-task-modal').classList.remove('hidden'); // Menampilkan modal
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
            fetch('add_task.php', {
                method: 'POST',
                body: formData
            }).then(response => response.json()).then(data => {
                if (data.success) {
                    window.location.reload();
                }
            });
        });

        // Update task completion status
        document.querySelectorAll('.task-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const taskId = this.getAttribute('data-task-id');
                const isCompleted = this.checked ? 1 : 0;

                fetch('update_task.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: taskId, is_completed: isCompleted })
                }).then(response => response.json()).then(data => {
                    if (!data.success) {
                        alert('Error updating task status');
                    }
                });
            });
        });
    </script>
</body>

</html>