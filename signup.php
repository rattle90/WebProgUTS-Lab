<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Mengnugas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-white-100 to-blue-200 flex items-center justify-center min-h-screen p-6">

    <div class="absolute inset-0 bg-[url('https://source.unsplash.com/featured/?abstract,signup')] bg-cover opacity-10 pointer-events-none"></div>

    <div class="bg-white shadow-2xl rounded-lg overflow-hidden w-full max-w-md transform transition duration-500">
        <div class="bg-gradient-to-r from-yellow-600 to-orange-600 p-8 text-center">
            <h1 class="text-white text-3xl font-bold">Create an Account</h1>
            <p class="text-gray-200 mt-2 text-sm">Join the Mengnugas community</p>
        </div>
        <div class="p-10">
            <form action="process_signup.php" method="POST">
                <div class="mb-6">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input 
                        type="text" 
                        name="username" 
                        id="username" 
                        required 
                        class="mt-2 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 transition duration-150" 
                        placeholder="Your username">
                </div>
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        required 
                        class="mt-2 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 transition duration-150" 
                        placeholder="your@example.com">
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required 
                        class="mt-2 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 transition duration-150" 
                        placeholder="Create a password">
                </div>
                <button type="submit" class="w-full bg-orange-600 text-white py-3 rounded-lg hover:bg-orange-700 transition duration-200">
                    Sign Up
                </button>
            </form>
            <p class="mt-6 text-center text-sm text-gray-600">
                Already have an account? <a href="login.php" class="text-orange-500 hover:underline">Login</a>
            </p>
        </div>
    </div>

</body>
</html>
