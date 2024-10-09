<!-- navbar.php -->
<nav class="bg-gray-900 p-4">
    <!-- Search Bar -->
    <div class="flex justify-center mb-4">
        <div class="relative w-1/3">
            <input type="text" placeholder="Search" class="bg-gray-800 text-gray-400 px-4 py-2 rounded-full w-full pl-10" />
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.4-1.4l4.28 4.3a1 1 0 01-1.42 1.4l-4.26-4.3zm-5.4-3.32a6 6 0 1112 0 6 6 0 01-12 0z" clip-rule="evenodd" />
                </svg>
            </span>
        </div>
    </div>

    <!-- Main Navbar -->
    <div class="flex items-center justify-between">
        <!-- Left Section: Logo -->
        <div class="flex items-center">
            <img src="logo.png" alt="Logo" class="h-8 w-8" />
        </div>

        <!-- Navigation Links Centered -->
        <div class="flex-grow flex justify-center space-x-16">
            <a href="#" class="text-white font-medium bg-gray-800 px-4 py-3 rounded-lg text-lg flex items-center">All Task</a>
            <a href="#" class="text-gray-400 font-medium hover:text-white text-lg flex items-center">Today</a>
            <a href="#" class="text-gray-400 font-medium hover:text-white text-lg flex items-center">Next 7 Days</a>
            <a href="#" class="text-gray-400 font-medium hover:text-white text-lg flex items-center">About Us</a>
        </div>

        <!-- Right Section: Profile -->
        <div class="relative dropdown">
            <button class="focus:outline-none">
                <img src="profile.jpg" alt="Profile" class="h-10 w-10 rounded-full border-2 border-gray-800 cursor-pointer" />
            </button>
            <div class="dropdown-content absolute right-0 mt-2 hidden bg-gray-800 rounded-md shadow-lg w-48">
                <a href="#" class="block text-lg px-6 py-3 text-gray-300 hover:bg-gray-700 rounded-md">Your Profile</a>
                <a href="#" class="block text-lg px-6 py-3 text-gray-300 hover:bg-gray-700 rounded-md">Sign Out</a>
            </div>
        </div>
    </div>
</nav>

<script>
    // Toggle dropdown on click
    document.querySelector('.dropdown button').addEventListener('click', function () {
        const dropdownContent = document.querySelector('.dropdown-content');
        dropdownContent.classList.toggle('hidden');
    });

    // Close dropdown if clicking outside
    window.addEventListener('click', function (event) {
        const dropdown = document.querySelector('.dropdown');
        if (!dropdown.contains(event.target)) {
            document.querySelector('.dropdown-content').classList.add('hidden');
        }
    });
</script>
