<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-600 h-screen">
    <!-- Main Container -->
    <div class="flex h-full">
        
        <!-- Sidebar -->
        <div class="w-1/3 bg-gray-900 text-white p-4 flex flex-col">
            <div class="flex justify-between mb-4">
                <h1 class="text-xl font-semibold">All my tasks</h1>
                <button class="text-gray-400">View</button>
            </div>
            <div class="mb-6">
                <input type="text" placeholder="Filter" class="w-full bg-gray-800 text-gray-400 p-2 rounded-lg">
            </div>
            <ul class="space-y-3">
                <li class="bg-gray-700 p-3 rounded-md">Today</li>
                <li class="hover:bg-gray-700 p-3 rounded-md cursor-pointer">Tomorrow</li>
                <li class="hover:bg-gray-700 p-3 rounded-md cursor-pointer">Upcoming</li>
                <li class="hover:bg-gray-700 p-3 rounded-md cursor-pointer">Someday</li>
            </ul>
            <button class="mt-auto p-2 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg">+ Add task</button>
        </div>
        
        <!-- Main Content -->
        <div class="w-2/3 bg-gray-100 p-6">
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
