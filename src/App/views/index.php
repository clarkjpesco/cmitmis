<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Monitoring Information System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">

    <!-- Container -->
    <div class="w-full max-w-4xl bg-white rounded-2xl shadow-lg p-6 sm:p-8 flex flex-col md:flex-row items-center gap-6 sm:gap-8">

        <!-- Left Side (Text Content) -->
        <div class="flex-1 text-center md:text-left">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-3 sm:mb-4">
                TCM Student Monitoring Information System
            </h1>
            <p class="text-sm sm:text-base text-gray-600 mb-4 sm:mb-6 leading-relaxed">
                Welcome to our student monitoring information system. Students can easily view their
                <span class="font-semibold">grades</span> and
                <span class="font-semibold">class schedules</span>, while administrators can manage
                <span class="font-semibold">accounts, schedules, and grades</span>.
            </p>

            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center md:justify-start">
                <a href="/login"
                    class="px-6 py-3 bg-blue-600 text-white rounded-xl shadow hover:bg-blue-700 transition text-sm sm:text-base font-medium">
                    Login
                </a>
                <a href="#about"
                    class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl shadow hover:bg-gray-100 transition text-sm sm:text-base font-medium">
                    Learn More
                </a>
            </div>
        </div>

        <!-- Right Side (Illustration / Image) -->
        <div class="flex-1 flex justify-center mt-4 md:mt-0">
            <img src="/assets/img/logo.png"
                alt="Students Illustration"
                class="w-48 sm:w-64 md:w-72 lg:w-80 max-w-full h-auto">
        </div>
    </div>

</body>

</html>