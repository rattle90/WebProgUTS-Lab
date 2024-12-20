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

        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            padding: 0;
            margin: 0;
        }

        .navbar-hidden {
            transform: translateY(-100%);
        }

        #mobileMenu {
            position: absolute;
            top: 60px;
            right: 0;
            left: 0;
            z-index: 49;
            background-color: white;
            padding-top: 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        #mobileMenu a {
            padding: 8px 16px;
            border-bottom: 0.005px solid black;
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
            margin-top: 2.5rem;
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


        .logo {
            display: flex;
            align-items: center;
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            margin-left: 8px;
            transition: color 0.3s;
        }

        .logo-text:hover {
            color: #3b82f6;
        }

        @media (max-width: 768px) {
        #search-bar-mobile {
            width: 100%;  
            padding-left: 40px;
        }

        #search-results-mobile {
            width: 100%; 
            max-height: 200px;  
            overflow-y: auto;  
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: absolute;
            z-index: 50;
            border-radius: 0.5rem;
            margin-top: 0.5rem;
        }

        #search-results-mobile a {
            display: block;
            padding: 0.5rem;
            color: black;
            text-decoration: none;
        }

        #search-results-mobile a:hover {
            background-color: #f3f4f6;
        }

        .navbar {
            padding: 1rem;
            position: fixed;
            width: 100%;
        }
        
        .flex {
            margin: 0;
            padding: 0;
        }

        #mobileMenu {
            padding-top: 10px;
        }
    }

    @media (min-width: 769px) {
        #search-bar-mobile {
            display: none; 
        }

        #search-results-mobile {
            display: none;  
        }
    }

    #dropdown-content {
            z-index: 100; 
        }
    </style>
</head>

<body>
    <nav class="navbar bg-gray-900 p-2 fixed top-0 left-0 right-0 z-10">
        <div class="flex justify-center mb-2">
            <div class="hidden lg:flex lg:flex-row relative w-1/3">
                <input id="search-bar" type="text" placeholder="Search tasks..."
                    class="bg-gray-800 text-gray-400 px-4 py-1 rounded-full w-full pl-10" />
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M12.9 14.32a8 8 0 111.4-1.4l4.28 4.3a1 1 0 01-1.42 1.4l-4.26-4.3zm-5.4-3.32a6 6 0 1112 0 6 6 0 01-12 0z"
                            clip-rule="evenodd" />
                    </svg>
                </span>
                <div id="search-results"></div>
            </div>
        </div>

        <div class="flex justify-between items-center">
            <div class="lg:hidden">
                <button id="burgerMenu" class="text-white pl-2">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <div class="flex items-center">
            <span class="logo">
                <a href="./index.php" class="logo-text">Mengnugas</a>
            </span>
            </div>
            <div id="menuItems" class="hidden lg:flex items-center justify-center space-x-2">
                <a href="./index.php"
                    class="nav-link text-gray-400 font-medium text-lg flex items-center hover:text-white active:text-white <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">
                    My Dashboard
                </a>
                <a href="./alltask.php"
                    class="nav-link text-gray-400 font-medium text-lg flex items-center <?php echo (basename($_SERVER['PHP_SELF']) == 'alltask.php') ? 'active' : ''; ?>">
                    All Task
                </a>
                <a href="./next7days.php"
                    class="nav-link text-gray-400 font-medium text-lg flex items-center <?php echo (basename($_SERVER['PHP_SELF']) == 'next7days.php') ? 'active' : ''; ?>">
                    Next 7 Days
                </a>
                <a href="./upcoming.php"
                    class="nav-link text-gray-400 font-medium text-lg flex items-center <?php echo (basename($_SERVER['PHP_SELF']) == 'upcoming.php') ? 'active' : ''; ?>">
                    Upcoming
                </a>
            </div>

            <!-- Profile di kanan -->
            <div class="relative dropdown pr-2">
                <button id="profile-dropdown-toggle" class="focus:outline-none">
                    <img src="assets/profile.png" alt="Profile"
                        class="h-8 w-8 rounded-full border-2 border-gray-800 cursor-pointer" />
                </button>
                <div id="dropdown-content" class="absolute right-0 mt-2 hidden bg-gray-800 rounded-md shadow-lg w-48">
                    <a href="./profile.php" class="block text-lg px-6 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                        Your Profile
                    </a>
                    <a href="./logout.php" class="block text-lg px-6 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                        Sign Out
                    </a>
                </div>
            </div>
        </div>

        <div class="flex justify-center lg:hidden mt-2 pb-2">
            <div class="relative w-60 px-4">
                <input id="search-bar-mobile" type="text" placeholder="Search tasks..."
                    class="bg-gray-800 text-gray-400 px-4 py-1 rounded-full w-full pl-10" />
                <span class="absolute inset-y-0 left-8 flex items-center text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M12.9 14.32a8 8 0 111.4-1.4l4.28 4.3a1 1 0 01-1.42 1.4l-4.26-4.3zm-5.4-3.32a6 6 0 1112 0 6 6 0 01-12 0z"
                            clip-rule="evenodd" />
                    </svg>
                </span>
                <div id="search-results-mobile"></div>
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
                <button id="mark-complete" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                    onclick="toggleTaskComplete()">Mark Complete</button>
            </div>
        </div>
    </div>

    <div id="mobileMenu" class="hidden lg:hidden flex flex-col space-y-4 bg-white p-4 shadow-lg">
        <a href="./index.php"
            class="text-black <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">My
            Dashboard</a>
        <a href="./alltask.php"
            class="text-black <?php echo (basename($_SERVER['PHP_SELF']) == 'alltask.php') ? 'active' : ''; ?>">All
            Task</a>
        <a href="./next7days.php"
            class="text-black <?php echo (basename($_SERVER['PHP_SELF']) == 'next7days.php') ? 'active' : ''; ?>">Next
            7 Days</a>
        <a href="./upcoming.php"
            class="text-black <?php echo (basename($_SERVER['PHP_SELF']) == 'upcoming.php') ? 'active' : ''; ?>">Upcoming</a>
    </div>


    <script>
        document.getElementById('profile-dropdown-toggle').addEventListener('click', function () {
            const dropdownContent = document.getElementById('dropdown-content');
            dropdownContent.classList.toggle('hidden');
        });

        window.addEventListener('click', function (event) {
            const dropdownContent = document.getElementById('dropdown-content');
            const profileButton = document.getElementById('profile-dropdown-toggle');

            if (!dropdownContent.contains(event.target) && !profileButton.contains(event.target)) {
                dropdownContent.classList.add('hidden'); 
            }
        });

        let currentTaskId = null; 

        function debounce(func, wait) {
            let timeout;
            return function (...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func.apply(this, args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        document.getElementById('burgerMenu').addEventListener('click', function () {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('hidden');
        });

        let lastScrollTop = 0;
        window.addEventListener('scroll', function () {
            const navbar = document.querySelector('.navbar');
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            if (scrollTop > lastScrollTop) {
                navbar.classList.add('navbar-hidden');
            } else {
                navbar.classList.remove('navbar-hidden');
            }

            lastScrollTop = scrollTop;
        });

        // AJAX Search 
        const searchBar = document.getElementById('search-bar');
        const searchResults = document.getElementById('search-results');
        const searchBarMobile = document.getElementById('search-bar-mobile');
        const searchResultsMobile = document.getElementById('search-results-mobile');

        searchBar.addEventListener('input', debounce(function () {
            const query = searchBar.value.trim();

            if (query.length > 0) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', './search_tasks.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onload = function () {
                    if (this.status === 200) {
                        const results = JSON.parse(this.responseText);
                        searchResults.innerHTML = '';

                        if (results.length > 0) {
                            results.forEach(function (task) {
                                const taskLink = document.createElement('a');
                                taskLink.href = '#'; 
                                taskLink.innerHTML = `<strong>${task.task_name}</strong> <br> <small>${task.due_date}</small>`;
                                taskLink.classList.add('text-gray-800', 'block', 'px-4', 'py-2');
                                taskLink.onclick = function () {
                                    // Populate modal with task details
                                    currentTaskId = task.id;
                                    currentTaskStatus = task.is_completed == 1 ? "Completed" : "Uncompleted";
                                    document.getElementById('task-title').innerText = task.task_name;
                                    document.getElementById('task-notes').innerText = task.notes || "No notes provided.";
                                    document.getElementById('task-due-date').innerText = `Due: ${task.due_date}`;
                                    document.getElementById('task-status').innerText = `Status: ${currentTaskStatus}`;
                                    document.getElementById('mark-complete').innerText = currentTaskStatus === "Uncompleted" ? "Mark Complete" : "Mark Uncomplete";
                                    document.getElementById('taskModal').style.display = 'block';
                                };
                                searchResults.appendChild(taskLink);
                            });
                            searchResults.style.display = 'block';
                        } else {
                            searchResults.style.display = 'none';
                        }
                    }
                };

                xhr.send(`query=${encodeURIComponent(query)}`);
            } else {
                searchResults.style.display = 'none';
            }
        }, 300));

        searchBarMobile.addEventListener('input', debounce(function () {
            const query = searchBarMobile.value.trim();

            if (query.length > 0) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', './search_tasks.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onload = function () {
                    if (this.status === 200) {
                        const results = JSON.parse(this.responseText);
                        searchResultsMobile.innerHTML = '';

                        if (results.length > 0) {
                            results.forEach(function (task) {
                                const taskLink = document.createElement('a');
                                taskLink.href = '#'; 
                                taskLink.innerHTML = `<strong>${task.task_name}</strong> <br> <small>${task.due_date}</small>`;
                                taskLink.classList.add('text-gray-800', 'block', 'px-4', 'py-2');

                              
                                taskLink.onclick = function () {                            
                                    currentTaskId = task.id;
                                    currentTaskStatus = task.is_completed == 1 ? "Completed" : "Uncompleted";
                                    document.getElementById('task-title').innerText = task.task_name;
                                    document.getElementById('task-notes').innerText = task.notes || "No notes provided.";
                                    document.getElementById('task-due-date').innerText = `Due: ${task.due_date}`;
                                    document.getElementById('task-status').innerText = `Status: ${currentTaskStatus}`;
                                    document.getElementById('mark-complete').innerText = currentTaskStatus === "Uncompleted" ? "Mark Complete" : "Mark Uncomplete";
                                    document.getElementById('taskModal').style.display = 'block';
                                };
                                searchResultsMobile.appendChild(taskLink);
                            });
                            searchResultsMobile.style.display = 'block';
                        } else {
                            searchResultsMobile.innerHTML = '<p>No results found</p>';
                            searchResultsMobile.style.display = 'block';
                        }
                    }
                };
                xhr.send(`query=${query}`);
            } else {
                searchResultsMobile.style.display = 'none';
            }
        }, 300));


        document.querySelector('.close').onclick = function () {
            document.getElementById('taskModal').style.display = 'none';
        };

        window.onclick = function (event) {
            const modal = document.getElementById('taskModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        };


        function toggleTaskComplete() {
            const xhr = new XMLHttpRequest();
            const url = currentTaskStatus === "Uncompleted" ? './mark_complete.php' : './mark_uncomplete.php';
            xhr.open('POST', url + `?task_id=${currentTaskId}`, true);

            xhr.onload = function () {
                if (this.status === 200) {
                    const response = JSON.parse(this.responseText);
                    if (response.success) {
                        currentTaskStatus = currentTaskStatus === "Uncompleted" ? "Completed" : "Uncompleted";
                        document.getElementById('mark-complete').innerText = currentTaskStatus === "Uncompleted" ? "Mark Complete" : "Mark Uncomplete";
                        document.getElementById('task-status').innerText = `Status: ${currentTaskStatus}`;
                    }
                }
            };

            xhr.send();
        }
    </script>
</body>

</html>