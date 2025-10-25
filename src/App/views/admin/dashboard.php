<?php include $this->resolve("partials/_admin_header.php"); ?>
<div class="flex-1 overflow-auto bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-10">
        <div class="flex items-center justify-between p-4">
            <div class="flex items-center">
                <button
                    id="mobile-menu-toggle"
                    class="p-1 mr-4 text-gray-600 lg:hidden hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded"
                    aria-label="Toggle navigation menu">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Dashboard</h2>
                    <p class="text-sm text-gray-500">Welcome back, <?php echo e($_SESSION['user']['full_name']); ?>!</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <div class="relative">
                    <div class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center font-medium text-white shadow-sm">
                            <?php echo strtoupper(substr($_SESSION['user']['full_name'], 0, 1)); ?>
                        </div>
                        <span class="hidden md:block text-gray-700 font-medium">
                            <?php echo e($_SESSION['user']['full_name']); ?>
                        </span>
                    </div>
                </div>

                <a
                    href="/logout"
                    class="flex items-center gap-2 px-4 py-2 text-gray-600 hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors"
                    title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="hidden sm:inline text-sm font-medium">Logout</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Dashboard Content -->
    <main class="p-6">
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Students -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium mb-1">Total Students</p>
                        <h3 class="text-3xl font-bold"><?php echo number_format($stats['total_students']); ?></h3>
                        <p class="text-blue-100 text-xs mt-2">
                            <i class="fas fa-user-graduate mr-1"></i>
                            <?php echo $stats['active_students']; ?> Active
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-4 rounded-lg">
                        <i class="fas fa-users text-4xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Subjects -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium mb-1">Total Subjects</p>
                        <h3 class="text-3xl font-bold"><?php echo number_format($stats['total_subjects']); ?></h3>
                        <p class="text-green-100 text-xs mt-2">
                            <i class="fas fa-book-open mr-1"></i>
                            <?php echo $stats['total_units']; ?> Total Units
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-4 rounded-lg">
                        <i class="fas fa-book text-4xl"></i>
                    </div>
                </div>
            </div>

            <!-- Active Schedules -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium mb-1">Active Schedules</p>
                        <h3 class="text-3xl font-bold"><?php echo number_format($stats['total_schedules']); ?></h3>
                        <p class="text-purple-100 text-xs mt-2">
                            <i class="fas fa-clock mr-1"></i>
                            Current Semester
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-4 rounded-lg">
                        <i class="fas fa-calendar-alt text-4xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Enrollments -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium mb-1">Total Enrollments</p>
                        <h3 class="text-3xl font-bold"><?php echo number_format($stats['total_enrollments']); ?></h3>
                        <p class="text-orange-100 text-xs mt-2">
                            <i class="fas fa-chart-line mr-1"></i>
                            This Semester
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-4 rounded-lg">
                        <i class="fas fa-clipboard-list text-4xl"></i>
                    </div>
                </div>
            </div>
        </div>


        <!-- Secondary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Courses Distribution -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Courses</h3>
                    <i class="fas fa-graduation-cap text-gray-400"></i>
                </div>
                <?php foreach ($stats['courses_distribution'] as $course): ?>
                    <div class="mb-3">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700"><?php echo e(strtoupper($course['code'])); ?></span>
                            <span class="text-sm text-gray-600"><?php echo $course['student_count']; ?> students</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: <?php echo ($course['student_count'] / $stats['total_students']) * 100; ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Year Level Distribution -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Year Levels</h3>
                    <i class="fas fa-layer-group text-gray-400"></i>
                </div>
                <?php
                $yearColors = ['bg-green-500', 'bg-blue-500', 'bg-purple-500', 'bg-orange-500'];
                foreach ($stats['year_distribution'] as $index => $year):
                ?>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                        <div class="flex items-center">
                            <div class="w-8 h-8 <?php echo $yearColors[$index % 4]; ?> rounded-lg flex items-center justify-center text-white font-bold text-sm mr-3">
                                <?php echo $year['year_level']; ?>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Year <?php echo $year['year_level']; ?></span>
                        </div>
                        <span class="text-sm font-semibold text-gray-800"><?php echo $year['student_count']; ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Grade Statistics -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Grade Status</h3>
                    <i class="fas fa-chart-pie text-gray-400"></i>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                            <span class="text-sm font-medium text-gray-700">Passed</span>
                        </div>
                        <span class="text-sm font-bold text-green-600"><?php echo $stats['grade_stats']['passed']; ?></span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                            <span class="text-sm font-medium text-gray-700">Pending</span>
                        </div>
                        <span class="text-sm font-bold text-yellow-600"><?php echo $stats['grade_stats']['pending']; ?></span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                            <span class="text-sm font-medium text-gray-700">Failed</span>
                        </div>
                        <span class="text-sm font-bold text-red-600"><?php echo $stats['grade_stats']['failed']; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities and Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Students -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-800">Recent Students</h2>
                            <a href="/admin/students" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                View All <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Student</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Course</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Year</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <?php foreach ($recent_students as $student): ?>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-4">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold shadow-sm">
                                                            <?php echo strtoupper(substr($student['full_name'], 0, 1)); ?>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900"><?php echo e($student['full_name']); ?></div>
                                                        <div class="text-xs text-gray-500"><?php echo e($student['student_number']); ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4">
                                                <span class="text-sm text-gray-900 font-medium"><?php echo e(strtoupper($student['course_code'])); ?></span>
                                            </td>
                                            <td class="px-4 py-4">
                                                <span class="text-sm text-gray-900"><?php echo $student['year_level']; ?></span>
                                            </td>
                                            <td class="px-4 py-4">
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?php echo $student['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                                                    <?php echo ucfirst($student['status']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-800">Quick Actions</h2>
                    </div>
                    <div class="p-4">
                        <a href="/admin/students/create" class="flex items-center p-4 rounded-lg hover:bg-blue-50 mb-2 transition-all group">
                            <div class="p-3 rounded-lg bg-blue-100 text-blue-600 mr-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">Add Student</div>
                                <div class="text-xs text-gray-500">Register new student</div>
                            </div>
                        </a>
                        <a href="/admin/subjects/create" class="flex items-center p-4 rounded-lg hover:bg-green-50 mb-2 transition-all group">
                            <div class="p-3 rounded-lg bg-green-100 text-green-600 mr-4 group-hover:bg-green-600 group-hover:text-white transition-colors">
                                <i class="fas fa-book-medical"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">Create Subject</div>
                                <div class="text-xs text-gray-500">Add new subject</div>
                            </div>
                        </a>
                        <a href="/admin/classes/create" class="flex items-center p-4 rounded-lg hover:bg-purple-50 mb-2 transition-all group">
                            <div class="p-3 rounded-lg bg-purple-100 text-purple-600 mr-4 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                                <i class="fas fa-calendar-plus"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">Schedule Class</div>
                                <div class="text-xs text-gray-500">Create class schedule</div>
                            </div>
                        </a>
                        <a href="/admin/enrollments" class="flex items-center p-4 rounded-lg hover:bg-orange-50 mb-2 transition-all group">
                            <div class="p-3 rounded-lg bg-orange-100 text-orange-600 mr-4 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">Enrollments</div>
                                <div class="text-xs text-gray-500">Manage enrollments</div>
                            </div>
                        </a>
                        <a href="/admin/grades" class="flex items-center p-4 rounded-lg hover:bg-red-50 transition-all group">
                            <div class="p-3 rounded-lg bg-red-100 text-red-600 mr-4 group-hover:bg-red-600 group-hover:text-white transition-colors">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">Input Grades</div>
                                <div class="text-xs text-gray-500">Manage student grades</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include $this->resolve("partials/_admin_footer.php"); ?>