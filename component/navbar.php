<!-- navbar.php -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet"> <!-- Tambahkan link font Poppins -->

<style>
    /* Tambahkan CSS untuk menyoroti halaman aktif */
    .active {
        background-color: #3b82f6; /* Warna latar belakang untuk halaman aktif */
        color: white; /* Warna teks untuk halaman aktif */
        border-radius: 0.5rem; /* Menambahkan border-radius untuk konsistensi */
    }

    /* CSS untuk efek hover */
    .nav-link {
        transition: background-color 0.3s, color 0.3s; /* Transisi untuk efek hover */
        padding: 0.5rem 1rem; /* Mengatur padding yang konsisten */
        border-radius: 0.5rem; /* Mengatur border-radius agar sesuai */
    }

    .nav-link:hover {
        background-color: grey; /* Warna latar belakang saat hover */
        color: white; /* Warna teks saat hover */
    }
</style>

<nav class="bg-gray-900 p-2 fixed top-0 left-0 right-0 z-10"> <!-- Memastikan navbar tetap di atas -->
    <!-- Search Bar -->
    <div class="flex justify-center mb-2"> <!-- Mengatur margin bawah -->
        <div class="relative w-1/3">
            <input type="text" placeholder="Search" class="bg-gray-800 text-gray-400 px-4 py-1 rounded-full w-full pl-10" /> <!-- Padding vertical -->
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
        <div class="flex-grow flex justify-center space-x-2"> <!-- Mengatur jarak antar link -->
            <a href="./index.php" class="nav-link text-gray-400 font-medium text-lg flex items-center">All Task</a> 
            <a href="./next7days.php" class="nav-link text-gray-400 font-medium text-lg flex items-center">Next 7 Days</a>
            <a href="./upcoming.php" class="nav-link text-gray-400 font-medium text-lg flex items-center">upcoming</a>

            <a href="about_us.php" class="nav-link text-gray-400 font-medium text-lg flex items-center">About Us</a>
        </div>

        <!-- Right Section: Profile -->
        <div class="relative dropdown">
            <button class="focus:outline-none">
                <img src="profile.jpg" alt="Profile" class="h-8 w-8 rounded-full border-2 border-gray-800 cursor-pointer" /> <!-- Ukuran gambar -->
            </button>
            <div class="dropdown-content absolute right-0 mt-2 hidden bg-gray-800 rounded-md shadow-lg w-48">
                <a href="#" class="block text-lg px-6 py-2 text-gray-300 hover:bg-gray-700 rounded-md">Your Profile</a> <!-- Padding vertical -->
                <a href="#" class="block text-lg px-6 py-2 text-gray-300 hover:bg-gray-700 rounded-md">Sign Out</a> <!-- Padding vertical -->
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

    // Highlight current page
    const currentLocation = window.location.href; // Mendapatkan URL saat ini
    const navLinks = document.querySelectorAll('.nav-link'); // Mendapatkan semua tautan navbar

    navLinks.forEach(link => {
        if (link.href === currentLocation) {
            link.classList.add('active'); // Menandai tautan aktif
            link.classList.remove('text-gray-400'); // Menghapus warna teks default
            link.classList.add('text-white'); // Menambahkan warna teks aktif
        }
    });
</script>
