<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Mengnugas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-6">

    <!-- Background pattern -->
    <div class="absolute inset-0 bg-[url('https://source.unsplash.com/featured/?abstract,security')] bg-cover opacity-10 pointer-events-none"></div>

    <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8 space-y-6 transform transition duration-300">
        <h1 class="text-3xl font-semibold text-center text-gray-800">Reset Password</h1>
        <p class="text-center text-gray-500 text-sm">Regain access to your Mengnugas account</p>

        <form action="process_forgot_password.php" method="POST" class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    required 
                    class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 transition duration-150"
                    placeholder="Your email">
            </div>
            <div>
                <label for="username" class="block text-sm font-medium text-gray-600">Username</label>
                <input 
                    type="text" 
                    name="username" 
                    id="username" 
                    required 
                    class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 transition duration-150"
                    placeholder="Your username">
            </div>
            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-600">New Password</label>
                <input 
                    type="password" 
                    name="new_password" 
                    id="new_password" 
                    required 
                    class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 transition duration-150"
                    placeholder="Enter new password">
            </div>
            <button type="submit" class="w-full py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition duration-200">
                Reset Password
            </button>
        </form>
        
        <p class="mt-6 text-center text-sm text-gray-500">
            <a href="login.php" class="text-yellow-500 hover:underline">Back to Login</a>
        </p>
    </div>

</body>
</html>
