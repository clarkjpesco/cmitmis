<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Student Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        //primary: '#11c27eff',
                        primary: '#4F46E5',
                        secondary: '#6B7280',
                        success: '#10B981',
                        danger: '#EF4444',
                        warning: '#F59E0B',
                        info: '#3B82F6'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-gray-100">
            <div class="p-4 text-2xl font-bold border-b border-gray-700">
                <div class="w-20 h-20 rounded-full flex items-center justify-center shadow-md mx-auto">
                    <img src="/assets/img/logo.png" />
                </div>
            </div>
            <nav class="p-4">
                <div class="mb-6">
                    <p class="text-gray-400 text-sm uppercase mb-2">Main</p>

                    <a href="/admin/dashboard" class="block py-2 px-4 rounded <?php echo e(($active ?? '') === 'dashboard' ? 'bg-primary text-white' : 'hover:bg-gray-700'); ?> mb-1">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                </div>
                <div class="mb-6">
                    <p class="text-gray-400 text-sm uppercase mb-2">Management</p>
                    <a href="/admin/students" class="block py-2 px-4 rounded <?php echo e(($active ?? '') === 'students' ? 'bg-primary text-white' : 'hover:bg-gray-700'); ?> mb-1">
                        <i class="fas fa-users mr-2"></i>Students
                    </a>
                    <a href="/admin/courses" class="block py-2 px-4 rounded <?php echo e(($active ?? '') === 'courses' ? 'bg-primary text-white' : 'hover:bg-gray-700'); ?> mb-1">
                        <i class="fas fa-graduation-cap mr-2"></i>Courses
                    </a>
                    <a href="/admin/subjects" class="block py-2 px-4 rounded <?php echo e(($active ?? '') === 'subjects' ? 'bg-primary text-white' : 'hover:bg-gray-700'); ?> mb-1">
                        <i class="fas fa-book mr-2"></i>Subjects
                    </a>
                    <a href="/admin/classes" class="block py-2 px-4 rounded <?php echo e(($active ?? '') === 'classes' ? 'bg-primary text-white' : 'hover:bg-gray-700'); ?> mb-1">
                        <i class="fas fa-calendar-alt mr-2"></i>Classes
                    </a>
                    <a href="/admin/enrollments" class="block py-2 px-4 rounded <?php echo e(($active ?? '') === 'enrollments' ? 'bg-primary text-white' : 'hover:bg-gray-700'); ?> mb-1">
                        <i class="fas fa-clipboard-list mr-2"></i>Enrollments
                    </a>
                    <a href="/admin/grades" class="block py-2 px-4 rounded <?php echo e(($active ?? '') === 'grades' ? 'bg-primary text-white' : 'hover:bg-gray-700'); ?> mb-1">
                        <i class="fas fa-chart-bar mr-2"></i>Grades
                    </a>
                </div>

            </nav>
        </div>