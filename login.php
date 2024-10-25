<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4 sm:p-0">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-lg md:max-w-md">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6">
            <h1 class="text-white text-2xl sm:text-3xl font-semibold text-center">Welcome Back</h1>
        </div>
        <div class="p-8">
            <form action="process_login.php" method="POST">
                <div class="mb-6">
                    <label for="login" class="block text-sm font-medium text-gray-700">Email or Username</label>
                    <input 
                        type="text" 
                        name="login" 
                        id="login" 
                        required 
                        class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150" 
                        placeholder="Your email or username">
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required 
                        class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150" 
                        placeholder="Your password">
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition duration-200">
                    Login
                </button>
            </form>
            <p class="mt-4 text-center text-sm text-gray-600">
                Don't have an account? <a href="signup.php" class="text-blue-500 hover:underline">Sign up</a>
            </p>
        </div>
    </div>
</body>
</html>
