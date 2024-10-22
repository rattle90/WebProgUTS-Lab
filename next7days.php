<?php
include 'db.php'; // Koneksi database
include 'component/navbar.php'; // Memanggil navbar di atas

// Mengambil tanggal hari ini
$today = new DateTime();

// Mengambil tugas yang jatuh tempo dalam 7 hari ke depan
$query = $pdo->prepare("SELECT * FROM tasks WHERE due_date BETWEEN ? AND DATE_ADD(?, INTERVAL 7 DAY)");
$query->execute([$today->format('Y-m-d'), $today->format('Y-m-d')]);
$tasks = $query->fetchAll(PDO::FETCH_ASSOC);

// Mengorganisir tugas ke dalam array berdasarkan tanggal
$tasksByDate = [];
foreach ($tasks as $task) {
    $dueDate = $task['due_date'];
    if (!isset($tasksByDate[$dueDate])) {
        $tasksByDate[$dueDate] = [];
    }
    $tasksByDate[$dueDate][] = $task;
}

// Fungsi untuk memperbarui status tugas di database
function updateTaskStatus($taskId, $isCompleted) {
    global $pdo; // Mengakses koneksi PDO dari luar fungsi
    $query = $pdo->prepare("UPDATE tasks SET is_completed = ? WHERE id = ?");
    $query->execute([$isCompleted, $taskId]);
    return $query->rowCount() > 0; // Mengembalikan true jika ada baris yang diperbarui
}

// Memproses permintaan pembaruan status tugas jika ada
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Memproses permintaan pembaruan status tugas
    if (isset($data['id']) && isset($data['is_completed'])) {
        $taskId = $data['id'];
        $isCompleted = $data['is_completed'] ? 1 : 0; // Mengubah nilai boolean ke integer
        if (updateTaskStatus($taskId, $isCompleted)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update task.']);
        }
        exit; // Menghentikan eksekusi setelah merespon permintaan
    }

    // Memproses permintaan penambahan tugas
    if (isset($data['task_name']) && isset($data['due_date'])) {
        $taskName = $data['task_name'];
        $dueDate = $data['due_date'];

        // Menyimpan tugas ke database
        $addQuery = $pdo->prepare("INSERT INTO tasks (task_name, due_date, is_completed) VALUES (?, ?, 0)");
        if ($addQuery->execute([$taskName, $dueDate])) {
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId(), 'task_name' => $taskName]); // Mengembalikan ID tugas yang baru ditambahkan
        } else {
            echo json_encode(['success' => false]);
        }
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
            margin: 0; 
            padding: 0; 
        }
        .completed {
            text-decoration: line-through;
            color: gray; /* Warna untuk tugas yang selesai */
        }
        .today-label {
            color: #FF6347; /* Warna untuk label 'Today' */
            opacity: 0.7; /* Mengurangi opacity */
            font-weight: bold;
            margin-left: 10px; /* Spasi antara nama hari dan label 'Today' */
        }
        
        /* Custom scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px; /* Width of the scrollbar */
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1; /* Background of the scrollbar track */
            border-radius: 10px; /* Rounded corners for the track */
        }

        ::-webkit-scrollbar-thumb {
            background: #FF6347; /* Color of the scrollbar thumb */
            border-radius: 10px; /* Rounded corners for the thumb */
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #FF4500; /* Darker color when hovering over the thumb */
        }
    </style>

    <script>
        function refreshTaskList() {
            fetch('next7days.php')
                .then(response => response.text())
                .then(html => {
                    const container = document.querySelector('.container');
                    container.innerHTML = html; // Replace the entire container content
                })
                .catch(error => console.error('Error fetching tasks:', error));
        }

        function toggleCompletion(taskId) {
            const checkbox = document.getElementById(`checkbox-${taskId}`);
            const isChecked = checkbox.checked;

            fetch('next7days.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: taskId, is_completed: isChecked }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    refreshTaskList(); // Refresh the task list
                } else {
                    console.error('Error updating task status:', data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function addTask(dueDate) {
            const inputField = document.getElementById(`add-task-${dueDate}`);
            const taskName = inputField.value.trim();

            if (taskName) {
                inputField.disabled = true;

                fetch('next7days.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ task_name: taskName, due_date: dueDate }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Menyisipkan task baru langsung ke DOM
                        const taskList = document.querySelector(`ul[data-date="${dueDate}"]`);
                        
                        const newTask = document.createElement('li');
                        newTask.id = `task-${data.id}`;
                        newTask.className = "flex items-center justify-between";

                        newTask.innerHTML = `
                            <input type="checkbox" id="checkbox-${data.id}" onchange="toggleCompletion(${data.id})" class="mr-2">
                            <span class="task-name">${taskName}</span>
                        `;

                        // Menambahkan task ke daftar (di atas "No tasks" jika ada)
                        const noTasksMessage = taskList.querySelector('.text-gray-500');
                        if (noTasksMessage) {
                            noTasksMessage.remove(); // Menghapus pesan "No tasks"
                        }

                        taskList.appendChild(newTask); // Tambahkan task baru ke daftar
                    } else {
                        console.error('Error adding task:', data.message);
                    }
                })
                .catch(error => console.error('Error:', error))
                .finally(() => {
                    inputField.value = '';
                    inputField.disabled = false;
                    inputField.focus();
                });
            } else {
                inputField.focus();
            }
        }
    </script>

</head>
<body class="bg-blue-600 h-screen p-5 pt-24">

    <div class="container mx-auto mt-5">
        <h1 class="text-white text-3xl font-bold mb-6">Tasks for the Next 7 Days</h1>
        <div class="flex space-x-4 overflow-x-auto">
            <?php
            // Menampilkan tugas untuk hari ini hingga tujuh hari ke depan
            for ($i = 0; $i < 7; $i++) {
                $date = clone $today;
                $date->modify("+$i day");

                $dayName = $date->format('l');
                $formattedDate = $date->format('Y-m-d');

                echo '<div class="bg-white p-4 rounded-lg shadow-md w-64 flex-none max-h-72 overflow-y-auto">';
                echo "<h2 class='text-lg font-semibold text-gray-800'>$dayName"; 
                if ($formattedDate === $today->format('Y-m-d')) {
                    echo "<span class='today-label'>Today</span>";
                }
                echo "</h2>";
                echo "<p class='text-gray-500'>$formattedDate</p>";
                echo '<ul class="mt-2 space-y-1" data-date="'.$formattedDate.'">';

                if (isset($tasksByDate[$formattedDate])) {
                    foreach ($tasksByDate[$formattedDate] as $task) {
                        $taskId = $task['id'];
                        echo '<li id="task-'.$taskId.'" class="flex items-center justify-between">';
                        echo '<input type="checkbox" id="checkbox-'.$taskId.'" onchange="toggleCompletion('.$taskId.')" class="mr-2" '.($task['is_completed'] ? 'checked' : '').'>';
                        echo "<span class='task-name'>".htmlspecialchars($task['task_name'])."</span>";
                        echo '</li>';
                    }
                } else {
                    echo '<li class="text-gray-500">No tasks</li>';
                }

                // Input field for adding a new task
                echo '<div class="mt-2">';
                echo '<input type="text" id="add-task-'.$formattedDate.'" class="p-1 border border-gray-300 rounded" placeholder="+ Add Task" onkeydown="if(event.key === \'Enter\') addTask(\''.$formattedDate.'\')">';
                echo '</div>';

                echo '</ul>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</body>
</html>
