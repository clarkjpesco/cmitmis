<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | Student Monitoring Information System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-blue-100 to-blue-200 flex items-center justify-center min-h-screen">

    <!-- Login Card -->
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">

        <div class="flex justify-center mb-6">
            <div class="w-20 h-20 rounded-full flex items-center justify-center shadow-md">
                <img src="/assets/img/logo.png" />
            </div>
        </div>

        <!-- Title -->
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">
            TCM Student Monitoring Information System
        </h1>
        <h2 class="text-lg text-center text-gray-600 mb-6">
            Please log in to continue
        </h2>

        <!-- Login Form -->
        <form method="POST" class="space-y-5">
            <?php include $this->resolve("partials/_csrf.php"); ?>

            <!-- Username -->
            <div>
                <label for="username" class="block text-gray-700 font-medium mb-1">Username</label>
                <input type="text" id="username" name="username"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Enter your username" required>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
                <input type="password" id="password" name="password"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Enter your password" required>
            </div>

            <!-- Remember Me + Forgot -->
            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="h-4 w-4 text-blue-500 border-gray-300 rounded">
                    <span class="ml-2 text-gray-600 text-sm">Remember me</span>
                </label>
                <a href="#" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-xl shadow hover:bg-blue-700 transition">
                Login
            </button>
        </form>

        <!-- Footer -->
        <p class="mt-6 text-center text-sm text-gray-500">
            &copy; 2025 TCM Student Monitoring Information System
        </p>
    </div>

</body>

</html>