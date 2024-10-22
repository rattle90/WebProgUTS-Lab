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

    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <a href="">
                <img src="logo.png" alt="Logo" class="h-8 w-8" />
            </a>
        </div>

        <div class="flex-grow flex justify-center space-x-2">
            <a href="./index.php" class="nav-link text-gray-400 font-medium text-lg flex items-center">My Dashboard</a> 
            <a href="./all_task.php" class="nav-link text-gray-400 font-medium text-lg flex items-center">All Task</a> 
            <a href="./next7days.php" class="nav-link text-gray-400 font-medium text-lg flex items-center">Next 7 Days</a>
            <a href="./upcoming.php" class="nav-link text-gray-400 font-medium text-lg flex items-center">Upcoming</a>
        </div>

        <div class="relative dropdown">
            <button class="focus:outline-none">
                <img src="profile.jpg" alt="Profile" class="h-8 w-8 rounded-full border-2 border-gray-800 cursor-pointer" />
            </button>
            <div class="dropdown-content absolute right-0 mt-2 hidden bg-gray-800 rounded-md shadow-lg w-48">
                <a href="#" class="block text-lg px-6 py-2 text-gray-300 hover:bg-gray-700 rounded-md">Your Profile</a>
                <a href="#" class="block text-lg px-6 py-2 text-gray-300 hover:bg-gray-700 rounded-md">Sign Out</a>
            </div>
        </div>
    </div>
</nav>

<script>
    document.querySelector('.dropdown button').addEventListener('click', function () {
        const dropdownContent = document.querySelector('.dropdown-content');
        dropdownContent.classList.toggle('hidden');
    });

    window.addEventListener('click', function (event) {
        const dropdown = document.querySelector('.dropdown');
        if (!dropdown.contains(event.target)) {
            document.querySelector('.dropdown-content').classList.add('hidden');
        }
    });

    const currentLocation = window.location.href; // Ambil URL lengkap
    const navLinks = document.querySelectorAll('.nav-link');

    navLinks.forEach(link => {
        // Cek apakah href dari link sama dengan currentLocation
        if (link.href === currentLocation) {
            link.classList.add('active');
            link.classList.remove('text-gray-400');
            link.classList.add('text-white');
        }
    });
</script>
