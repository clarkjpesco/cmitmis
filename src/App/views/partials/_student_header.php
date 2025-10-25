<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        'primary-dark': '#2563eb',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-down {
            animation: slideDown 0.3s ease-out;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <!-- Navigation Bar -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center">
                            <?php
                            // Cache-busting: adds version based on file modification time
                            $logoPath = __DIR__ . '/../public/assets/img/logo.png';
                            $version = file_exists($logoPath) ? filemtime($logoPath) : '1';
                            ?>
                            <img src="/assets/img/logo.png?v=<?php echo $version; ?>" alt="Logo" />
                        </div>
                        <span class="ml-3 text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Student Portal</span>
                    </div>
                    <div class="hidden md:ml-10 md:flex md:space-x-1">
                        <a href="/student/dashboard" class="<?php echo e(($active ?? '') === 'dashboard' ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600'); ?>  px-4 py-2 rounded-lg text-sm font-medium transition-all">
                            <i class="fas fa-home mr-2"></i>Dashboard
                        </a>
                        <a href="/student/schedule" class="<?php echo e(($active ?? '') === 'schedule' ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600'); ?>  px-4 py-2 rounded-lg text-sm font-medium transition-all">
                            <i class="fas fa-calendar-alt mr-2"></i>Schedule
                        </a>
                        <a href="/student/grades" class="<?php echo e(($active ?? '') === 'grades' ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600'); ?>  px-4 py-2 rounded-lg text-sm font-medium transition-all">
                            <i class="fas fa-chart-line mr-2"></i>Grades
                        </a>
                        <a href="/student/enrollments" class="<?php echo e(($active ?? '') === 'enrollments' ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600'); ?>  px-4 py-2 rounded-lg text-sm font-medium transition-all">
                            <i class="fas fa-book-open mr-2"></i>Enrollments
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="text-gray-500 hover:text-gray-700 relative">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                    </button>
                    <div class="relative group">
                        <button class="flex items-center space-x-3 focus:outline-none">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                                <?php echo strtoupper(substr($student['full_name'], 0, 1)); ?>
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-semibold text-gray-700"><?php echo e(explode(' ', $student['full_name'])[0]); ?></p>
                                <p class="text-xs text-gray-500">Student</p>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </button>
                        <div class="hidden group-hover:block absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 animate-slide-down">
                            <a href="/student/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 transition-colors">
                                <i class="fas fa-user mr-2"></i>My Profile
                            </a>
                            <hr class="my-2">
                            <a href="/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </a>
                        </div>
                    </div>
                    <!-- Hamburger Menu Button -->
                    <button id="mobileMenuBtn" class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div id="mobileMenu" class="hidden md:hidden border-t border-gray-200">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="/student/dashboard" class="<?php echo e(($active ?? '') === 'dashboard' ? 'bg-blue-50 text-blue-600 ' : 'text-gray-700 hover:bg-gray-50'); ?>  block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-home mr-2"></i>Dashboard
                </a>
                <a href="/student/schedule" class="<?php echo e(($active ?? '') === 'schedule' ? 'bg-blue-50 text-blue-600 ' : 'text-gray-700 hover:bg-gray-50'); ?>  block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-calendar-alt mr-2"></i>Schedule
                </a>
                <a href="/student/grades" class="<?php echo e(($active ?? '') === 'grades' ? 'bg-blue-50 text-blue-600 ' : 'text-gray-700 hover:bg-gray-50'); ?>  block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-chart-line mr-2"></i>Grades
                </a>
                <a href="/student/enrollments" class="<?php echo e(($active ?? '') === 'enrollments' ? 'bg-blue-50 text-blue-600 ' : 'text-gray-700 hover:bg-gray-50'); ?>  block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-book-open mr-2"></i>Enrollments
                </a>
            </div>
        </div>
    </nav>