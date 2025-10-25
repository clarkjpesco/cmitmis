<?php include $this->resolve("partials/_admin_header.php"); ?>
<div class="flex-1 overflow-auto">
    <main class="p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="/admin/grades" class="inline-flex items-center text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Students List
                </a>
            </div>

            <!-- Student Info Card -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-20 w-20 bg-primary rounded-full flex items-center justify-center text-white text-3xl font-semibold">
                                <?php echo strtoupper(substr($student['full_name'], 0, 1)); ?>
                            </div>
                            <div class="ml-6">
                                <h2 class="text-2xl font-bold text-gray-800"><?php echo e($student['full_name']); ?></h2>
                                <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                                    <span><i class="fas fa-id-card mr-1"></i><?php echo e($student['student_number']); ?></span>
                                    <span><i class="fas fa-graduation-cap mr-1"></i><?php echo e($student['course']); ?></span>
                                    <span><i class="fas fa-layer-group mr-1"></i>Year <?php echo e($student['year_level']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grade Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                            <i class="fas fa-book-open text-blue-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Subjects</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo $statistics['total_subjects'] ?? 0; ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Passed</p>
                            <p class="text-2xl font-bold text-green-600"><?php echo $statistics['passed_subjects'] ?? 0; ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-3">
                            <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Pending</p>
                            <p class="text-2xl font-bold text-yellow-600"><?php echo $statistics['pending_subjects'] ?? 0; ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                            <i class="fas fa-star text-purple-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Average</p>
                            <p class="text-2xl font-bold text-gray-900">
                                <?php
                                if (isset($statistics['average_grade']) && $statistics['average_grade']) {
                                    echo number_format($statistics['average_grade'], 2);
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grades List -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Grade Details</h3>
                            <p class="text-sm text-gray-500 mt-1">View and manage student grades</p>
                        </div>
                        <?php if (!empty($semesters)): ?>
                            <select id="semesterFilter" class="rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="">All Semesters</option>
                                <?php foreach ($semesters as $sem): ?>
                                    <option value="<?php echo e($sem['semester'] . '|' . $sem['school_year']); ?>">
                                        <?php echo e(ucfirst($sem['semester']) . ' - ' . $sem['school_year']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Schedule</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (empty($grades)): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                                        <p class="text-gray-500 text-lg font-medium mb-2">No grades found</p>
                                        <p class="text-gray-400 text-sm">This student hasn't been enrolled in any subjects yet</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($grades as $grade): ?>
                                    <tr class="hover:bg-gray-50 transition-colors" data-semester="<?php echo e($grade['semester']); ?>" data-year="<?php echo e($grade['school_year']); ?>">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900"><?php echo e($grade['subject_code']); ?></div>
                                            <div class="text-sm text-gray-500"><?php echo e($grade['subject_name']); ?></div>
                                            <div class="text-xs text-gray-400 mt-1">
                                                <i class="fas fa-book mr-1"></i><?php echo e($grade['units']); ?> units
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            <div class="flex items-center mb-1">
                                                <i class="fas fa-calendar text-gray-400 mr-2"></i>
                                                <?php echo e($grade['day']); ?>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-clock text-gray-400 mr-2"></i>
                                                <span class="text-gray-500"><?php echo e($grade['time']); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            <div class="font-medium"><?php echo e(ucfirst($grade['semester'])); ?></div>
                                            <div class="text-gray-500 text-xs"><?php echo e($grade['school_year']); ?></div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php if (isset($grade['grade']) && $grade['grade']): ?>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                        <?php echo $grade['grade'] <= 3.0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                                    <?php echo e(number_format($grade['grade'], 2)); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600">
                                                    <i class="fas fa-minus mr-1"></i>No Grade
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            <?php echo e($grade['remarks'] ?? '-'); ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <a href="/admin/grades/input/<?php echo $grade['enrollment_id']; ?>"
                                                class="inline-flex items-center px-4 py-2 <?php echo (isset($grade['grade']) && $grade['grade']) ? 'bg-blue-600 hover:bg-blue-700' : 'bg-green-600 hover:bg-green-700'; ?> text-white rounded-lg transition-colors">
                                                <i class="fas <?php echo (isset($grade['grade']) && $grade['grade']) ? 'fa-edit' : 'fa-plus'; ?> mr-2"></i>
                                                <?php echo (isset($grade['grade']) && $grade['grade']) ? 'Update' : 'Input Grade'; ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Grade Distribution Chart -->
            <?php if (!empty($grades) && isset($statistics['average_grade'])): ?>
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Performance Summary -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h4 class="font-medium text-gray-800 mb-4">
                            <i class="fas fa-chart-pie mr-2 text-primary"></i>Performance Summary
                        </h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Highest Grade:</span>
                                <span class="text-lg font-bold text-green-600">
                                    <?php echo isset($statistics['highest_grade']) ? number_format($statistics['highest_grade'], 2) : 'N/A'; ?>
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Lowest Grade:</span>
                                <span class="text-lg font-bold text-red-600">
                                    <?php echo isset($statistics['lowest_grade']) ? number_format($statistics['lowest_grade'], 2) : 'N/A'; ?>
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Average Grade (GWA):</span>
                                <span class="text-lg font-bold text-blue-600">
                                    <?php echo number_format($statistics['average_grade'], 2); ?>
                                </span>
                            </div>
                            <div class="pt-3 border-t border-gray-200">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-600">Pass Rate:</span>
                                    <span class="text-lg font-bold text-gray-900">
                                        <?php
                                        $passRate = ($statistics['graded_subjects'] > 0)
                                            ? ($statistics['passed_subjects'] / $statistics['graded_subjects'] * 100)
                                            : 0;
                                        echo number_format($passRate, 1) . '%';
                                        ?>
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: <?php echo $passRate; ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grade Breakdown -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h4 class="font-medium text-gray-800 mb-4">
                            <i class="fas fa-list-ul mr-2 text-primary"></i>Grade Breakdown
                        </h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-600 rounded-full mr-3"></div>
                                    <span class="text-sm font-medium text-gray-700">Passed (1.0-3.0)</span>
                                </div>
                                <span class="text-lg font-bold text-green-600"><?php echo $statistics['passed_subjects'] ?? 0; ?></span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-red-600 rounded-full mr-3"></div>
                                    <span class="text-sm font-medium text-gray-700">Failed (5.0)</span>
                                </div>
                                <span class="text-lg font-bold text-red-600"><?php echo $statistics['failed_subjects'] ?? 0; ?></span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-yellow-600 rounded-full mr-3"></div>
                                    <span class="text-sm font-medium text-gray-700">Pending</span>
                                </div>
                                <span class="text-lg font-bold text-yellow-600"><?php echo $statistics['pending_subjects'] ?? 0; ?></span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg border-t-2 border-blue-600 mt-3">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-blue-600 rounded-full mr-3"></div>
                                    <span class="text-sm font-medium text-gray-700">Total Subjects</span>
                                </div>
                                <span class="text-lg font-bold text-blue-600"><?php echo $statistics['total_subjects'] ?? 0; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Semester filter dropdown
            const semesterFilter = document.getElementById('semesterFilter');
            if (semesterFilter) {
                semesterFilter.addEventListener('change', function() {
                    filterBySemester(this.value);
                });
            }
        });

        function filterBySemester(value) {
            const rows = document.querySelectorAll('tbody tr[data-semester]');

            rows.forEach(row => {
                if (!value) {
                    row.style.display = '';
                } else {
                    const [semester, schoolYear] = value.split('|');
                    const rowSemester = row.dataset.semester;
                    const rowYear = row.dataset.year;

                    if (rowSemester === semester && rowYear === schoolYear) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        }
    </script>

    <?php include $this->resolve("partials/_admin_footer.php"); ?>