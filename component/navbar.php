<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

<style>
    .active {
        background-color: #3b82f6;
        color: white;
        border-radius: 0.5rem;
    }

    .nav-link {
        transition: background-color 0.3s, color 0.3s;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
    }

    .nav-link:hover {
        background-color: grey;
        color: white;
    }
</style>

<nav class="bg-gray-900 p-2 fixed top-0 left-0 right-0 z-10">
    <div class="flex justify-center mb-2">
        <div class="relative w-1/3">
            <input type="text" placeholder="Search" class="bg-gray-800 text-gray-400 px-4 py-1 rounded-full w-full pl-10" />
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.4-1.4l4.28 4.3a1 1 0 01-1.42 1.4l-4.26-4.3zm-5.4-3.32a6 6 0 1112 0 6 6 0 01-12 0z" clip-rule="evenodd" />
                </svg>
            </span>
        </div>
    </div>
</nav>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .active {
            background-color: #3b82f6;
            color: white;
            border-radius: 0.5rem;
        }

        .nav-link {
            transition: background-color 0.3s, color 0.3s;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
        }

        .nav-link:hover {
            background-color: grey;
            color: white;
        }

        #search-results {
            display: none;
            position: absolute;
            background-color: white;
            border-radius: 0.5rem;
            margin-top: 0.5rem;
            max-height: 200px;
            overflow-y: auto;
            width: 100%;
            z-index: 50;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #search-results a {
            display: block;
            padding: 0.5rem;
            color: black;
            text-decoration: none;
        }

        #search-results a:hover {
            background-color: #f3f4f6;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
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
</head>
<body>
    <nav class="bg-gray-900 p-2 fixed top-0 left-0 right-0 z-10">
        <div class="flex justify-center mb-2">
            <div class="relative w-1/3">
                <input id="search-bar" type="text" placeholder="Search tasks..." class="bg-gray-800 text-gray-400 px-4 py-1 rounded-full w-full pl-10" />
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.4-1.4l4.28 4.3a1 1 0 01-1.42 1.4l-4.26-4.3zm-5.4-3.32a6 6 0 1112 0 6 6 0 01-12 0z" clip-rule="evenodd" />
                    </svg>
                </span>
                <div id="search-results"></div>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="">
                    <img src="logo.png" alt="Logo" class="h-8 w-8" />
                </a>
            </div>

            <div class="flex-grow flex justify-center space-x-2">
                <a href="./index.php" class="nav-link text-gray-400 font-medium text-lg flex items-center">My Dashboard</a>
                <a href="./alltask.php" class="nav-link text-gray-400 font-medium text-lg flex items-center">All Task</a>
                <a href="./next7days.php" class="nav-link text-gray-400 font-medium text-lg flex items-center">Next 7 Days</a>
                <a href="./upcoming.php" class="nav-link text-gray-400 font-medium text-lg flex items-center">Upcoming</a>
            </div>

            <div class="relative dropdown">
                <button class="focus:outline-none">
                    <img src="profile.jpg" alt="Profile" class="h-8 w-8 rounded-full border-2 border-gray-800 cursor-pointer" />
                </button>
                <div class="dropdown-content absolute right-0 mt-2 hidden bg-gray-800 rounded-md shadow-lg w-48">
                    <a href="./profile.php" class="block text-lg px-6 py-2 text-gray-300 hover:bg-gray-700 rounded-md">Your Profile</a>
                    <a href="./logout.php" class="block text-lg px-6 py-2 text-gray-300 hover:bg-gray-700 rounded-md">Sign Out</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Modal Structure -->
    <div id="taskModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="task-title" class="text-lg font-semibold"></h2>
            <p id="task-notes" class="text-lg mb-4"></p>
            <p id="task-due-date" class="text-lg mb-4"></p>
            <p id="task-status" class="text-lg mb-4"></p>

            <div class="mt-4">
                <button id="mark-complete" onclick="markTaskComplete(currentTaskId)" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Mark Complete</button>
                <button id="mark-uncomplete" onclick="markTaskUncomplete(currentTaskId)" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600" style="display: none;">Mark Uncomplete</button>
            </div>
        </div>
    </div>

    <script>
        let currentTaskId = null; // Store the currently viewed task ID

        // Debouncing function to limit how often we make requests
        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func.apply(this, args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // AJAX Search
        const searchBar = document.getElementById('search-bar');
        const searchResults = document.getElementById('search-results');

        searchBar.addEventListener('input', debounce(function() {
            const query = searchBar.value.trim();

            if (query.length > 0) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', './search_tasks.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onload = function() {
                    if (this.status === 200) {
                        const results = JSON.parse(this.responseText);
                        searchResults.innerHTML = '';

                        if (results.length > 0) {
                            results.forEach(function(task) {
                                const taskLink = document.createElement('a');
                                taskLink.href = '#'; // Prevent default navigation
                                taskLink.textContent = task.task_name;
                                taskLink.classList.add('block', 'hover:bg-gray-200', 'p-2'); // Add classes for styling
                                taskLink.dataset.taskId = task.id; // Store task ID in data attribute
                                searchResults.appendChild(taskLink);
                            });
                            searchResults.style.display = 'block';
                        } else {
                            searchResults.innerHTML = '<p class="text-center text-gray-600">No tasks found</p>';
                        }
                    }
                };

                xhr.send('query=' + encodeURIComponent(query));
            } else {
                searchResults.innerHTML = '';
                searchResults.style.display = 'none';
            }
        }, 300)); // Delay 300ms to wait before firing the AJAX request

        // Handle task click to open modal
        searchResults.addEventListener('click', function(e) {
            if (e.target.tagName === 'A') {
                e.preventDefault(); // Prevent default action
                const taskId = e.target.dataset.taskId; // Get task ID from data attribute
                fetchTaskDetails(taskId); // Fetch task details
            }
        });

        // Fetch task details and display in modal
        function fetchTaskDetails(taskId) {
            currentTaskId = taskId; // Store the current task ID
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `get_task.php?task_id=${taskId}`, true);

            xhr.onload = function() {
                if (this.status === 200) {
                    const task = JSON.parse(this.responseText);
                    document.getElementById('task-title').textContent = task.task_name;
                    document.getElementById('task-notes').textContent = task.notes || 'No notes available';
                    document.getElementById('task-due-date').textContent = 'Due Date: ' + (task.due_date ? task.due_date : 'No due date');
                    const taskStatus = task.is_completed ? 'Completed' : 'Not Completed';
                    document.getElementById('task-status').textContent = 'Status: ' + taskStatus;

                    // Update button visibility based on task status
                    document.getElementById('mark-complete').style.display = task.is_completed ? 'none' : 'inline-block';
                    document.getElementById('mark-uncomplete').style.display = task.is_completed ? 'inline-block' : 'none';

                    // Display modal
                    document.getElementById('taskModal').style.display = 'block';
                }
            };

            xhr.send();
        }

        function markTaskComplete(taskId) {
            fetch(`mark_complete.php?task_id=${taskId}`, { method: 'POST' })
                .then(() => {
                    // Update the task status in the modal
                    document.getElementById('task-status').textContent = 'Status: Completed';
                    document.getElementById('mark-complete').style.display = 'none';
                    document.getElementById('mark-uncomplete').style.display = 'inline-block';

                    // Update checkbox and label for the task if needed
                    const checkbox = document.getElementById(`checkbox-${taskId}`);
                    if (checkbox) {
                        checkbox.checked = true; // Assuming there's a checkbox element for this task
                        const label = document.querySelector(`label[for="checkbox-${taskId}"]`);
                        if (label) {
                            label.classList.add('line-through'); // Add line-through effect to label
                        }
                    }
                });
        }

        // Mark task as uncomplete
        function markTaskUncomplete(taskId) {
            fetch(`mark_uncomplete.php?task_id=${taskId}`, { method: 'POST' })
                .then(() => {
                    // Update the task status in the modal
                    document.getElementById('task-status').textContent = 'Status: Not Completed';
                    document.getElementById('mark-complete').style.display = 'inline-block';
                    document.getElementById('mark-uncomplete').style.display = 'none';

                    // Update checkbox and label for the task if needed
                    const checkbox = document.getElementById(`checkbox-${taskId}`);
                    if (checkbox) {
                        checkbox.checked = false; // Assuming there's a checkbox element for this task
                        const label = document.querySelector(`label[for="checkbox-${taskId}"]`);
                        if (label) {
                            label.classList.remove('line-through'); // Remove line-through effect from label
                        }
                    }
                });
        }

        // Close modal
        document.querySelector('.close').onclick = function() {
            document.getElementById('taskModal').style.display = 'none';
        };

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('taskModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        };
    </script>
</body>
</html>
