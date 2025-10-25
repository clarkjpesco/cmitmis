<?php include $this->resolve("partials/_student_header.php"); ?>
<main class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">My Profile</h2>
            <p class="text-gray-600 mt-1">View your personal and academic information</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 text-center border-b border-gray-200">
                        <div class="inline-flex items-center justify-center w-24 h-24 bg-primary rounded-full text-white text-4xl font-bold mb-4">
                            <?php echo strtoupper(substr($student['full_name'], 0, 1)); ?>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900"><?php echo e($student['full_name']); ?></h3>
                        <p class="text-gray-500 mt-1"><?php echo e($student['student_number']); ?></p>
                        <div class="mt-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Active Student
                            </span>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Username</label>
                            <p class="mt-1 text-gray-900"><?php echo e($student['username']); ?></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Course</label>
                            <p class="mt-1 text-gray-900"><?php echo e($student['course']); ?></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Year Level</label>
                            <p class="mt-1 text-gray-900">Year <?php echo e($student['year_level']); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Academic Summary -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-graduation-cap mr-2 text-primary"></i>
                            Academic Summary
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-blue-700">Current Enrollments</p>
                                        <p class="text-3xl font-bold text-blue-900 mt-2"><?php echo $stats['current_enrollments'] ?? 0; ?></p>
                                    </div>
                                    <div class="bg-blue-500 rounded-lg p-3">
                                        <i class="fas fa-book-open text-white text-2xl"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-green-50 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-green-700">Current Units</p>
                                        <p class="text-3xl font-bold text-green-900 mt-2"><?php echo $stats['current_units'] ?? 0; ?></p>
                                    </div>
                                    <div class="bg-green-500 rounded-lg p-3">
                                        <i class="fas fa-clipboard-list text-white text-2xl"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-purple-50 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-purple-700">Completed Subjects</p>
                                        <p class="text-3xl font-bold text-purple-900 mt-2"><?php echo $stats['completed_subjects'] ?? 0; ?></p>
                                    </div>
                                    <div class="bg-purple-500 rounded-lg p-3">
                                        <i class="fas fa-check-circle text-white text-2xl"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-yellow-50 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-yellow-700">GPA</p>
                                        <p class="text-3xl font-bold text-yellow-900 mt-2">
                                            <?php echo isset($stats['gpa']) && $stats['gpa'] ? number_format($stats['gpa'], 2) : 'N/A'; ?>
                                        </p>
                                    </div>
                                    <div class="bg-yellow-500 rounded-lg p-3">
                                        <i class="fas fa-star text-white text-2xl"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Statistics -->
                <?php if (isset($statistics['gpa']) && $statistics['gpa']): ?>
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800">
                                <i class="fas fa-chart-bar mr-2 text-primary"></i>
                                Performance Statistics
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-600">Total Subjects Enrolled</span>
                                    <span class="text-lg font-bold text-gray-900"><?php echo $statistics['total_subjects'] ?? 0; ?></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-600">Subjects Graded</span>
                                    <span class="text-lg font-bold text-gray-900"><?php echo $statistics['graded_subjects'] ?? 0; ?></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-600">Subjects Passed</span>
                                    <span class="text-lg font-bold text-green-600"><?php echo $statistics['passed_subjects'] ?? 0; ?></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-600">Subjects Failed</span>
                                    <span class="text-lg font-bold text-red-600"><?php echo $statistics['failed_subjects'] ?? 0; ?></span>
                                </div>
                                <div class="pt-4 border-t border-gray-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-600">Pass Rate</span>
                                        <span class="text-lg font-bold text-gray-900">
                                            <?php
                                            $passRate = ($statistics['graded_subjects'] > 0)
                                                ? ($statistics['passed_subjects'] / $statistics['graded_subjects'] * 100)
                                                : 0;
                                            echo number_format($passRate, 1) . '%';
                                            ?>
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                        <div class="bg-gradient-to-r from-green-400 to-green-600 h-3 rounded-full transition-all" style="width: <?php echo $passRate; ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grade Distribution -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800">
                                <i class="fas fa-trophy mr-2 text-primary"></i>
                                Academic Achievements
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="text-center p-4 bg-green-50 rounded-lg border-2 border-green-200">
                                    <i class="fas fa-medal text-green-600 text-3xl mb-2"></i>
                                    <p class="text-sm font-medium text-green-700">Highest Grade</p>
                                    <p class="text-2xl font-bold text-green-900 mt-1">
                                        <?php echo isset($statistics['highest_grade']) ? number_format($statistics['highest_grade'], 2) : 'N/A'; ?>
                                    </p>
                                </div>
                                <div class="text-center p-4 bg-blue-50 rounded-lg border-2 border-blue-200">
                                    <i class="fas fa-chart-line text-blue-600 text-3xl mb-2"></i>
                                    <p class="text-sm font-medium text-blue-700">Average Grade</p>
                                    <p class="text-2xl font-bold text-blue-900 mt-1">
                                        <?php echo number_format($statistics['gpa'], 2); ?>
                                    </p>
                                </div>
                                <div class="text-center p-4 bg-purple-50 rounded-lg border-2 border-purple-200">
                                    <i class="fas fa-book text-purple-600 text-3xl mb-2"></i>
                                    <p class="text-sm font-medium text-purple-700">Units Earned</p>
                                    <p class="text-2xl font-bold text-purple-900 mt-1">
                                        <?php echo $statistics['earned_units'] ?? 0; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Current Semester -->
                <?php if (!empty($stats['current_semester'])): ?>
                    <div class="bg-gradient-to-r from-primary to-primary-dark text-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">
                            <i class="fas fa-calendar-check mr-2"></i>
                            Current Semester Information
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-blue-100 text-sm">Semester</p>
                                <p class="text-xl font-bold"><?php echo ucfirst($stats['current_semester']['semester']); ?></p>
                            </div>
                            <div>
                                <p class="text-blue-100 text-sm">School Year</p>
                                <p class="text-xl font-bold"><?php echo $stats['current_semester']['school_year']; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include $this->resolve("partials/_student_footer.php"); ?>