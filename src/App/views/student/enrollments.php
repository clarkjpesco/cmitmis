<?php include $this->resolve("partials/_student_header.php"); ?>
<main class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">My Enrollments</h2>
            <p class="text-gray-600 mt-1">View your enrollment history</p>
        </div>

        <!-- Enrollments Table -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-4 sm:p-6 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Enrollment Details</h3>
                        <p class="text-sm text-gray-500 mt-1">All your enrolled subjects</p>
                    </div>
                    <?php if (!empty($semesters)): ?>
                        <select id="semesterFilter" class="rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary text-sm">
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

            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Schedule</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instructor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($enrollments)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                                    <p class="text-gray-500 text-lg font-medium mb-2">No enrollments found</p>
                                    <p class="text-gray-400 text-sm">You haven't been enrolled in any subjects yet</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($enrollments as $enrollment): ?>
                                <tr class="hover:bg-gray-50 transition-colors enrollment-row" data-semester="<?php echo e($enrollment['semester']); ?>" data-year="<?php echo e($enrollment['school_year']); ?>">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900"><?php echo e($enrollment['code']); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo e($enrollment['subject_name']); ?></div>
                                        <div class="text-xs text-gray-400 mt-1">
                                            <i class="fas fa-book mr-1"></i><?php echo e($enrollment['units']); ?> units
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <div class="flex items-center mb-1">
                                            <i class="fas fa-calendar text-gray-400 mr-2"></i>
                                            <?php echo e($enrollment['day']); ?>
                                        </div>
                                        <div class="flex items-center mb-1">
                                            <i class="fas fa-clock text-gray-400 mr-2"></i>
                                            <span class="text-gray-500"><?php echo e($enrollment['time']); ?></span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-door-open text-gray-400 mr-2"></i>
                                            <span class="text-gray-400"><?php echo e($enrollment['room']); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <i class="fas fa-user-tie text-gray-400 mr-2"></i>
                                        <?php echo e($enrollment['instructor']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <div class="font-medium"><?php echo e(ucfirst($enrollment['semester'])); ?></div>
                                        <div class="text-gray-500 text-xs"><?php echo e($enrollment['school_year']); ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if (isset($enrollment['grade']) && $enrollment['grade']): ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                <?php echo $enrollment['grade'] <= 3.0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Graded (<?php echo e(number_format($enrollment['grade'], 2)); ?>)
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>Ongoing
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden">
                <?php if (empty($enrollments)): ?>
                    <div class="p-6 text-center">
                        <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500 text-lg font-medium mb-2">No enrollments found</p>
                        <p class="text-gray-400 text-sm">You haven't been enrolled in any subjects yet</p>
                    </div>
                <?php else: ?>
                    <div class="divide-y divide-gray-200">
                        <?php foreach ($enrollments as $enrollment): ?>
                            <div class="p-4 hover:bg-gray-50 transition-colors enrollment-card" data-semester="<?php echo e($enrollment['semester']); ?>" data-year="<?php echo e($enrollment['school_year']); ?>">
                                <!-- Subject Header -->
                                <div class="mb-3 pb-3 border-b border-gray-100">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="text-sm font-bold text-gray-900"><?php echo e($enrollment['code']); ?></div>
                                            <div class="text-sm text-gray-600"><?php echo e($enrollment['subject_name']); ?></div>
                                            <div class="text-xs text-gray-400 mt-1">
                                                <i class="fas fa-book mr-1"></i><?php echo e($enrollment['units']); ?> units
                                            </div>
                                        </div>
                                        <div class="ml-2">
                                            <?php if (isset($enrollment['grade']) && $enrollment['grade']): ?>
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium 
                                        <?php echo $enrollment['grade'] <= 3.0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    <?php echo e(number_format($enrollment['grade'], 2)); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-1"></i>Ongoing
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Details -->
                                <div class="space-y-2">
                                    <!-- Schedule Group -->
                                    <div class="bg-blue-50 rounded-lg p-3 space-y-2">
                                        <div class="flex items-center text-sm text-gray-700">
                                            <i class="fas fa-calendar text-blue-500 mr-2 w-4 flex-shrink-0"></i>
                                            <span class="font-medium text-gray-600 w-16">Day:</span>
                                            <span class="text-gray-900"><?php echo e($enrollment['day']); ?></span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-700">
                                            <i class="fas fa-clock text-blue-500 mr-2 w-4 flex-shrink-0"></i>
                                            <span class="font-medium text-gray-600 w-16">Time:</span>
                                            <span class="text-gray-900"><?php echo e($enrollment['time']); ?></span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-700">
                                            <i class="fas fa-door-open text-blue-500 mr-2 w-4 flex-shrink-0"></i>
                                            <span class="font-medium text-gray-600 w-16">Room:</span>
                                            <span class="text-gray-900"><?php echo e($enrollment['room']); ?></span>
                                        </div>
                                    </div>

                                    <!-- Instructor -->
                                    <div class="flex items-center text-sm text-gray-700">
                                        <i class="fas fa-user-tie text-gray-400 mr-2 w-4 flex-shrink-0"></i>
                                        <span class="font-medium text-gray-500 w-20">Instructor:</span>
                                        <span class="truncate"><?php echo e($enrollment['instructor']); ?></span>
                                    </div>

                                    <!-- Semester -->
                                    <div class="flex items-center text-sm text-gray-700">
                                        <i class="fas fa-calendar-alt text-gray-400 mr-2 w-4 flex-shrink-0"></i>
                                        <span class="font-medium text-gray-500 w-20">Semester:</span>
                                        <span><?php echo e(ucfirst($enrollment['semester']) . ' - ' . $enrollment['school_year']); ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>


        <!-- Enrollment Summary -->
        <?php if (!empty($enrollments)): ?>
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h4 class="font-medium text-blue-900 mb-3">
                    <i class="fas fa-info-circle mr-2"></i>Enrollment Summary
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="bg-white rounded-lg p-4">
                        <div class="text-blue-600 font-medium mb-1">Total Enrollments</div>
                        <div class="text-2xl font-bold text-gray-900"><?php echo count($enrollments); ?></div>
                    </div>
                    <div class="bg-white rounded-lg p-4">
                        <div class="text-blue-600 font-medium mb-1">Semesters Attended</div>
                        <div class="text-2xl font-bold text-gray-900"><?php echo count($semesters ?? []); ?></div>
                    </div>
                    <div class="bg-white rounded-lg p-4">
                        <div class="text-blue-600 font-medium mb-1">Completed Subjects</div>
                        <div class="text-2xl font-bold text-gray-900">
                            <?php
                            $completed = 0;
                            foreach ($enrollments as $e) {
                                if (isset($e['grade']) && $e['grade'] >= 1.0 && $e['grade'] <= 3.0) $completed++;
                            }
                            echo $completed;
                            ?>
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
        // Get both desktop rows and mobile cards
        const desktopRows = document.querySelectorAll('.enrollment-row');
        const mobileCards = document.querySelectorAll('.enrollment-card');

        // Filter desktop rows
        desktopRows.forEach(row => {
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

        // Filter mobile cards
        mobileCards.forEach(card => {
            if (!value) {
                card.style.display = 'block';
            } else {
                const [semester, schoolYear] = value.split('|');
                const cardSemester = card.dataset.semester;
                const cardYear = card.dataset.year;

                if (cardSemester === semester && cardYear === schoolYear) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            }
        });
    }
</script>

<?php include $this->resolve("partials/_student_footer.php"); ?>