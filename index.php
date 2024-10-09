<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <!-- Link Tailwind CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
  <!-- Navbar Component -->
  <nav class="bg-gray-900 p-4 flex items-center justify-between">
    <!-- Logo -->
    <div class="flex items-center">
      <img src="logo.png" alt="Logo" class="h-6 w-6 mr-4" />
    </div>

    <!-- Navigation Links -->
    <div class="flex space-x-6">
      <a href="#" class="text-white font-medium hover:text-gray-400">Dashboard</a>
      <a href="#" class="text-gray-400 font-medium hover:text-white">Team</a>
      <a href="#" class="text-gray-400 font-medium hover:text-white">Projects</a>
      <a href="#" class="text-gray-400 font-medium hover:text-white">Calendar</a>
    </div>

    <!-- Search Bar -->
    <div class="relative flex-1 max-w-xs mx-4">
      <input type="text" placeholder="Search" class="bg-gray-800 text-gray-400 px-4 py-2 rounded-lg w-full" />
      <span class="absolute inset-y-0 right-3 flex items-center text-gray-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.4-1.4l4.28 4.3a1 1 0 01-1.42 1.4l-4.26-4.3zm-5.4-3.32a6 6 0 1112 0 6 6 0 01-12 0z" clip-rule="evenodd" />
        </svg>
      </span>
    </div>

    <!-- Notification and Profile -->
    <div class="flex items-center space-x-4">
      <button class="text-gray-400 hover:text-white">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path d="M10 2a6 6 0 00-6 6v4H3a1 1 0 000 2h14a1 1 0 000-2h-1V8a6 6 0 00-6-6zM9 18a2 2 0 004 0H9z" />
        </svg>
      </button>
      <img src="profile.jpg" alt="Profile" class="h-8 w-8 rounded-full border-2 border-gray-800" />
    </div>
  </nav>

  <!-- Main Content -->
  <div class="p-6">
    <h1 class="text-2xl font-bold text-gray-800">Welcome to the Dashboard</h1>
    <p class="mt-2 text-gray-600">This is the main content area. Customize it as needed.</p>
  </div>
</body>
</html>
