<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">

    <!-- Define Font Family in Style -->
    <style>
        body {
            font-family: 'Inter', 'Helvetica Neue', Helvetica, Arial, 'Roboto', sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';
        }
    </style>
</head>

<body class="bg-blue-600 h-full">

    <!-- Main Container with Flex for Centering -->
    <div class="flex h-full w-11/12 space-x-6 mx-auto">

        <!-- Bubble for Sidebar -->
        <div class="w-1/3 bg-gray-900 text-white p-4 rounded-lg shadow-lg mb-6">
            <div class="flex justify-between mb-4">
                <h1 class="text-xl font-semibold">All my tasks</h1>
                <button class="text-gray-400">View</button>
            </div>
            <ul class="space-y-3">
                <li class="bg-gray-700 p-3 rounded-md">Today</li>
                <li class="hover:bg-gray-700 p-3 rounded-md cursor-pointer">Tomorrow</li>
                <li class="hover:bg-gray-700 p-3 rounded-md cursor-pointer">Upcoming</li>
                <li class="hover:bg-gray-700 p-3 rounded-md cursor-pointer">Someday</li>
            </ul>
            <button class="mt-auto p-2 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg">+ Add task</button>
        </div>

        <!-- Bubble for Task Details -->
        <div class="w-2/3 bg-gray-100 p-6 rounded-lg shadow-lg mb-6">
            <!-- Task Header -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold">Do laundry</h2>
                <button class="bg-blue-500 text-white px-4 py-2 rounded-md">Mark as complete</button>
            </div>

            <!-- Task Details -->
            <div class="bg-white p-4 rounded-lg shadow-lg">
                <!-- Tags -->
                <div class="flex items-center space-x-3 mb-4">
                    <button class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full">Remind me</button>
                    <button class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full">Personal</button>
                    <button class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full">Tags</button>
                </div>

                <!-- Notes -->
                <div class="mb-4">
                    <h3 class="text-xl font-semibold">Notes</h3>
                    <textarea class="w-full p-2 border border-gray-300 rounded-md" placeholder="Insert your notes here"></textarea>
                </div>

                <!-- Subtasks -->
                <div>
                    <h3 class="text-xl font-semibold">Subtasks</h3>
                    <div class="mb-2 flex items-center space-x-2">
                        <input type="checkbox" class="h-4 w-4">
                        <span>Do laundry</span>
                    </div>
                    <button class="text-blue-500">Add a new subtask</button>
                </div>

                <!-- Attachments -->
                <div class="mt-6">
                    <h3 class="text-xl font-semibold">Attachments</h3>
                    <div class="border border-dashed border-gray-400 p-4 rounded-lg text-center">
                        <p class="text-gray-500">Click to add / drop your files here</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
