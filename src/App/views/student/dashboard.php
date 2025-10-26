<?php include $this->resolve("partials/_student_header.php"); ?>
<main class="p-4 sm:p-6 lg:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Welcome Banner -->
        <div class="gradient-bg text-white rounded-2xl shadow-2xl p-8 mb-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-5 rounded-full -ml-24 -mb-24"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">Welcome back, <?php echo e(explode(' ', $student['full_name'])[0]); ?>! 👋</h1>
                    <p class="text-lg text-purple-100 flex items-center flex-wrap gap-3">
                        <span class="inline-flex items-center">
                            <i class="fas fa-id-card mr-2"></i>
                            <?php echo e($student['student_number']); ?>
                        </span>
                        <span>•</span>
                        <span class="inline-flex items-center">
                            <i class="fas fa-graduation-cap mr-2"></i>
                            <?php echo e($student['course']); ?>
                        </span>
                        <span>•</span>
                        <span class="inline-flex items-center">
                            <i class="fas fa-layer-group mr-2"></i>
                            Year <?php echo e($student['year_level']); ?>
                        </span>
                    </p>
                </div>
                <div class="hidden lg:block">
                    <div class="w-24 h-24 bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl flex items-center justify-center text-5xl font-bold shadow-xl">
                        <?php echo strtoupper(substr($student['full_name'], 0, 1)); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Current Subjects</p>
                        <p class="text-3xl font-bold text-gray-900"><?php echo $stats['current_enrollments']; ?></p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-book-open text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 card-hover border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Current Units</p>
                        <p class="text-3xl font-bold text-gray-900"><?php echo $stats['current_units']; ?></p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-clipboard-list text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 card-hover border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Completed</p>
                        <p class="text-3xl font-bold text-gray-900"><?php echo $stats['completed_subjects']; ?></p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-check-circle text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 card-hover border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">GPA</p>
                        <p class="text-3xl font-bold text-gray-900">
                            <?php echo isset($stats['gpa']) && $stats['gpa'] ? number_format($stats['gpa'], 2) : 'N/A'; ?>
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-star text-white text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Semester Info -->
        <?php if (!empty($stats['current_semester'])): ?>
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 rounded-xl p-5 mb-8 shadow-md">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mr-4 shadow-lg">
                        <i class="fas fa-calendar-alt text-white text-xl"></i>
                    </div>
                    <div>
                        <span class="font-semibold text-blue-900 text-lg">Current Semester:</span>
                        <span class="text-blue-700 ml-2 font-medium">
                            <?php echo ucfirst($stats['current_semester']['semester']); ?> -
                            <?php echo $stats['current_semester']['school_year']; ?>
                        </span>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Today's Schedule -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-calendar-day mr-3"></i>
                            Current Schedule
                        </h3>
                    </div>
                    <div class="p-6">
                        <?php if (empty($schedule)): ?>
                            <div class="text-center py-12">
                                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-calendar-times text-gray-300 text-5xl"></i>
                                </div>
                                <p class="text-gray-500 text-lg font-medium">No classes scheduled</p>
                                <p class="text-gray-400 text-sm mt-2">You don't have any enrolled subjects for this semester</p>
                            </div>
                        <?php else: ?>
                            <div class="space-y-4">
                                <?php foreach ($schedule as $class): ?>
                                    <div class="border-2 border-gray-100 rounded-xl p-4 sm:p-5 hover:border-blue-300 hover:shadow-lg transition-all duration-300 bg-gradient-to-r from-white to-gray-50">
                                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                            <div class="flex-1">
                                                <div class="flex items-start gap-3">
                                                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                                        <i class="fas fa-book text-blue-600 text-base sm:text-lg"></i>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <h4 class="font-bold text-gray-900 text-base sm:text-lg mb-3 leading-tight">
                                                            <?php echo e($class['code']); ?> - <?php echo e($class['subject_name']); ?>
                                                        </h4>
                                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3">
                                                            <div class="flex items-center text-sm text-gray-600 bg-white px-3 py-2 rounded-lg">
                                                                <i class="fas fa-calendar text-blue-500 mr-2 w-4 flex-shrink-0"></i>
                                                                <span class="font-medium"><?php echo e($class['day']); ?></span>
                                                            </div>
                                                            <div class="flex items-center text-sm text-gray-600 bg-white px-3 py-2 rounded-lg">
                                                                <i class="fas fa-clock text-green-500 mr-2 w-4 flex-shrink-0"></i>
                                                                <span class="font-medium"><?php echo e($class['time']); ?></span>
                                                            </div>
                                                            <div class="flex items-center text-sm text-gray-600 bg-white px-3 py-2 rounded-lg">
                                                                <i class="fas fa-door-open text-purple-500 mr-2 w-4 flex-shrink-0"></i>
                                                                <span class="font-medium">Room <?php echo e($class['room']); ?></span>
                                                            </div>
                                                            <div class="flex items-center text-sm text-gray-600 bg-white px-3 py-2 rounded-lg">
                                                                <i class="fas fa-user-tie text-orange-500 mr-2 w-4 flex-shrink-0"></i>
                                                                <span class="font-medium truncate"><?php echo e($class['instructor']); ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex justify-end sm:ml-4">
                                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md whitespace-nowrap">
                                                    <?php echo e($class['units']); ?> units
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-bolt mr-3"></i>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="/student/schedule" class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 rounded-xl transition-all duration-300 card-hover group">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-calendar-alt text-white text-lg"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-bold text-gray-900">View Schedule</p>
                                <p class="text-xs text-gray-600">Check your class times</p>
                            </div>
                        </a>

                        <a href="/student/grades" class="flex items-center p-4 bg-gradient-to-r from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 rounded-xl transition-all duration-300 card-hover group">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-chart-line text-white text-lg"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-bold text-gray-900">View Grades</p>
                                <p class="text-xs text-gray-600">Check your academic performance</p>
                            </div>
                        </a>

                        <a href="/student/enrollments" class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 hover:from-purple-100 hover:to-purple-200 rounded-xl transition-all duration-300 card-hover group">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-book-open text-white text-lg"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-bold text-gray-900">My Enrollments</p>
                                <p class="text-xs text-gray-600">View enrolled subjects</p>
                            </div>
                        </a>

                        <a href="/student/profile" class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 hover:from-gray-100 hover:to-gray-200 rounded-xl transition-all duration-300 card-hover group">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-user text-white text-lg"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-bold text-gray-900">My Profile</p>
                                <p class="text-xs text-gray-600">View your information</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Announcements -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-6">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-bullhorn mr-3"></i>
                            Announcements
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-start bg-blue-50 rounded-xl p-4 border-l-4 border-blue-500">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-info-circle text-white"></i>
                                    </div>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm text-gray-700 font-medium">Welcome to the Student Portal! View your schedule, grades, and enrollment information here.</p>
                                    <p class="text-xs text-gray-500 mt-2 flex items-center">
                                        <i class="fas fa-clock mr-1"></i>
                                        Just now
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>


<?php include $this->resolve("partials/_student_footer.php"); ?>