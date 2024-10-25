<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Mengnugas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-6">

    <div class="absolute inset-0 bg-[url('https://source.unsplash.com/featured/?abstract,signup')] bg-cover opacity-20 pointer-events-none"></div>

    <div class="relative w-full max-w-md bg-white rounded-lg shadow-lg p-8 space-y-6">
        <div class="text-center">
            <h1 class="text-3xl font-semibold text-gray-800">Create an Account</h1>
            <p class="text-gray-500 mt-1">Join the Mengnugas community</p>
        </div>

        <form action="process_signup.php" method="POST" class="space-y-4">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-600">Username</label>
                <input 
                    type="text" 
                    name="username" 
                    id="username" 
                    required 
                    class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150" 
                    placeholder="Your username">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    required 
                    class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150" 
                    placeholder="your@example.com">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-600">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    required 
                    class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150" 
                    placeholder="Create a password">
            </div>
            <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                Sign Up
            </button>
        </form>

        <p class="text-center text-sm text-gray-500">
            Already have an account? <a href="login.php" class="text-blue-500 hover:underline">Login</a>
        </p>
    </div>

</body>
</html>
