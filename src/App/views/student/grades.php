<?php include $this->resolve("partials/_student_header.php"); ?>
<main class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">My Grades</h2>
            <p class="text-gray-600 mt-1">View your academic performance</p>
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
                        <p class="text-sm font-medium text-gray-500">GPA</p>
                        <p class="text-2xl font-bold text-gray-900">
                            <?php
                            if (isset($statistics['gpa']) && $statistics['gpa']) {
                                echo number_format($statistics['gpa'], 2);
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grades Table -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Grade Details</h3>
                        <p class="text-sm text-gray-500 mt-1">Your academic performance by subject</p>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instructor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Units</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($grades)): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                                    <p class="text-gray-500 text-lg font-medium mb-2">No grades available</p>
                                    <p class="text-gray-400 text-sm">You don't have any enrolled subjects yet</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($grades as $grade): ?>
                                <tr class="hover:bg-gray-50 transition-colors" data-semester="<?php echo e($grade['semester']); ?>" data-year="<?php echo e($grade['school_year']); ?>">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900"><?php echo e($grade['code']); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo e($grade['subject_name']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <i class="fas fa-user-tie text-gray-400 mr-2"></i>
                                        <?php echo e($grade['instructor']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <div class="font-medium"><?php echo e(ucfirst($grade['semester'])); ?></div>
                                        <div class="text-gray-500 text-xs"><?php echo e($grade['school_year']); ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <?php echo e($grade['units']); ?> units
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if (isset($grade['grade']) && $grade['grade']): ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold 
                                        <?php echo $grade['grade'] <= 3.0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                                <?php echo e(number_format($grade['grade'], 2)); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600">
                                                <i class="fas fa-clock mr-1"></i>Pending
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <?php if (isset($grade['remarks']) && $grade['remarks']): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium
                                            <?php
                                            if ($grade['remarks'] === 'Passed') echo 'bg-green-100 text-green-800';
                                            elseif ($grade['remarks'] === 'Failed') echo 'bg-red-100 text-red-800';
                                            else echo 'bg-yellow-100 text-yellow-800';
                                            ?>">
                                                <?php echo e($grade['remarks']); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-gray-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Performance Summary -->
        <?php if (!empty($grades) && isset($statistics['gpa'])): ?>
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Performance Details -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h4 class="font-medium text-gray-800 mb-4">
                        <i class="fas fa-chart-line mr-2 text-primary"></i>Performance Details
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
                            <span class="text-sm text-gray-600">Average (GPA):</span>
                            <span class="text-lg font-bold text-blue-600">
                                <?php echo number_format($statistics['gpa'], 2); ?>
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
                                <div class="bg-green-600 h-2 rounded-full transition-all" style="width: <?php echo $passRate; ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Units Summary -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h4 class="font-medium text-gray-800 mb-4">
                        <i class="fas fa-book mr-2 text-primary"></i>Units Summary
                    </h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                            <div>
                                <span class="text-sm text-blue-700">Total Units Taken</span>
                                <div class="text-2xl font-bold text-blue-900"><?php echo $statistics['total_units'] ?? 0; ?></div>
                            </div>
                            <i class="fas fa-clipboard-list text-blue-600 text-3xl"></i>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                            <div>
                                <span class="text-sm text-green-700">Units Earned</span>
                                <div class="text-2xl font-bold text-green-900"><?php echo $statistics['earned_units'] ?? 0; ?></div>
                            </div>
                            <i class="fas fa-check-circle text-green-600 text-3xl"></i>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                            <div>
                                <span class="text-sm text-purple-700">Completion Rate</span>
                                <div class="text-2xl font-bold text-purple-900">
                                    <?php
                                    $completionRate = ($statistics['total_units'] > 0)
                                        ? ($statistics['earned_units'] / $statistics['total_units'] * 100)
                                        : 0;
                                    echo number_format($completionRate, 1) . '%';
                                    ?>
                                </div>
                            </div>
                            <i class="fas fa-percentage text-purple-600 text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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

<?php include $this->resolve("partials/_student_footer.php"); ?>