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
        /* Modal Styles */
        .modal {
            display: none; /* Modal tersembunyi secara default */
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4); /* Background semi-transparan */
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Lebar modal */
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <script>
        let currentTaskId = null; // Menyimpan ID tugas saat ini

        function toggleCompletion(taskId) {
            const taskElement = document.getElementById(`task-${taskId}`);
            const checkbox = document.getElementById(`checkbox-${taskId}`);
            const isChecked = checkbox.checked;

            // Mengubah status tugas di tampilan
            if (isChecked) {
                taskElement.classList.add('completed'); // Menandai tugas sebagai selesai
            } else {
                taskElement.classList.remove('completed'); // Menghapus tanda selesai
            }

            // Mengupdate status tugas di database
            fetch('next7days.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: taskId, is_completed: isChecked }),
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.error('Error updating task status:', data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function showDetails(taskId) {
            currentTaskId = taskId; // Menyimpan ID tugas yang diklik
            const checkbox = document.getElementById(`checkbox-${taskId}`);
            const modalContent = document.getElementById('modal-content');

            // Memeriksa status tugas
            if (checkbox.checked) {
                modalContent.innerText = "Task is completed.";
            } else {
                modalContent.innerText = "Task is not completed.";
            }

            // Menampilkan modal
            const modal = document.getElementById('myModal');
            modal.style.display = "block";
        }

        // Menutup modal
        function closeModal() {
            const modal = document.getElementById('myModal');
            modal.style.display = "none";
        }

        // Menutup modal saat pengguna mengklik di luar modal
        window.onclick = function(event) {
            const modal = document.getElementById('myModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</head>
<body class="bg-blue-600 h-screen p-5 pt-24"> <!-- Tambahkan padding top -->

    <div class="container mx-auto mt-5 h-96"> <!-- Menambahkan kelas h-full untuk kontainer -->
        <h1 class="text-white text-3xl font-bold mb-6">Tasks for the Next 7 Days</h1>
        <div class="flex space-x-4 overflow-x-auto h-full"> <!-- Scrollbar tetap ada dan tinggi penuh -->
            <?php
            // Menampilkan tugas untuk hari ini hingga tujuh hari ke depan
            for ($i = 0; $i < 7; $i++) {
                $date = clone $today; // Clone objek tanggal
                $date->modify("+$i day"); // Menambah hari

                // Mengatur format tanggal untuk tampilan
                $dayName = $date->format('l'); // Nama hari
                $formattedDate = $date->format('Y-m-d'); // Format tanggal

                // Menampilkan card dengan tinggi maksimum
                echo '<div class="bg-white p-4 rounded-lg shadow-md w-64 flex-none max-h-72 h-64 overflow-hidden">'; // Kartu dengan tinggi maksimum dan overflow
                echo "<h2 class='text-lg font-semibold text-gray-800'>$dayName</h2>";
                echo "<p class='text-gray-500'>$formattedDate</p>";
                echo '<ul class="mt-2 space-y-1 h-full overflow-y-auto">'; // Mengatur agar daftar tugas memanjang dan scrollable

                // Menampilkan tugas untuk tanggal ini
                if (isset($tasksByDate[$formattedDate])) {
                    foreach ($tasksByDate[$formattedDate] as $task) {
                        $taskId = $task['id']; // Asumsikan 'id' adalah kolom ID tugas
                        echo '<li id="task-'.$taskId.'" class="flex items-center justify-between">'; // Kartu tugas tanpa efek hover
                        echo '<input type="checkbox" id="checkbox-'.$taskId.'" onchange="toggleCompletion('.$taskId.')" class="mr-2" '.($task['is_completed'] ? 'checked' : '').'>'; // Checkbox untuk tugas
                        echo "<span class='task-name' onclick='showDetails($taskId)' style='cursor:pointer;'>".htmlspecialchars($task['task_name'])."</span>";
                        echo '</li>';
                    }
                } else {
                    echo '<li class="text-gray-500">No tasks</li>';
                }

                echo '</ul>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p id="modal-content"></p> <!-- Tempat untuk menampilkan status tugas -->
        </div>
    </div>
</body>
</html>
