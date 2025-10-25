<?php include $this->resolve("partials/_admin_header.php"); ?>
<div class="flex-1 overflow-auto">

    <main class="p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="/admin/enrollments" class="inline-flex items-center text-gray-600 hover:text-gray-900">
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
                        <a href="/admin/enrollments/create/<?php echo $student['id']; ?>"
                            class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                            <i class="fas fa-plus mr-2"></i>Process New Enrollment
                        </a>
                    </div>
                </div>
            </div>

            <!-- Semester Tabs -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-8 px-6" aria-label="Tabs">
                        <button class="semester-tab border-b-2 border-primary text-primary py-4 px-1 text-sm font-medium" data-semester="current">
                            Current Semester
                        </button>
                        <button class="semester-tab border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 text-sm font-medium" data-semester="all">
                            All Enrollments
                        </button>
                        <button class="semester-tab border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 text-sm font-medium" data-semester="history">
                            History
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Enrollment Summary -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                            <i class="fas fa-book-open text-blue-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Current Subjects</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo $summary['current_subjects'] ?? 0; ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                            <i class="fas fa-clipboard-list text-green-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Current Units</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo $summary['current_units'] ?? 0; ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                            <i class="fas fa-chart-line text-purple-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Subjects</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo $summary['total_subjects'] ?? 0; ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-3">
                            <i class="fas fa-star text-yellow-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Average Grade</p>
                            <p class="text-2xl font-bold text-gray-900">
                                <?php
                                if (isset($summary['average_grade']) && $summary['average_grade']) {
                                    echo number_format($summary['average_grade'], 2);
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enrollments List -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Enrollment Details</h3>
                            <p class="text-sm text-gray-500 mt-1">View and manage student enrollments</p>
                        </div>
                        <?php if (!empty($semesters)): ?>
                            <select id="semesterFilter" class="rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="">All Semesters</option>
                                <?php foreach ($semesters as $sem): ?>
                                    <option value="<?php echo e($sem['semester'] . '|' . $sem['school_year']); ?>">
                                        <?php echo e($sem['semester'] . ' - ' . $sem['school_year']); ?>
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instructor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (empty($enrollments)): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                                        <p class="text-gray-500 text-lg font-medium mb-2">No enrollments found</p>
                                        <p class="text-gray-400 text-sm mb-4">This student hasn't been enrolled in any subjects yet</p>
                                        <a href="/admin/enrollments/create/<?php echo $student['id']; ?>"
                                            class="inline-flex items-center mt-4 px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-lg font-medium transition-colors">
                                            <i class="fas fa-plus mr-2"></i>Add First Enrollment
                                        </a>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($enrollments as $enrollment): ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900"><?php echo e($enrollment['subject_code']); ?></div>
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
                                                    <?php echo e(number_format($enrollment['grade'], 2)); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600">
                                                    <i class="fas fa-minus mr-1"></i>No Grade
                                                </span>
                                            <?php endif; ?>
                                            <?php if (isset($enrollment['remarks']) && $enrollment['remarks']): ?>
                                                <div class="text-xs text-gray-500 mt-1"><?php echo e($enrollment['remarks']); ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <div class="flex space-x-2">
                                                <button onclick="viewDetails(<?php echo $enrollment['id']; ?>)"
                                                    class="text-blue-600 hover:text-blue-800 p-2 hover:bg-blue-50 rounded transition-colors"
                                                    title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button onclick="dropEnrollment(<?php echo $enrollment['id']; ?>, '<?php echo e($enrollment['subject_code']); ?>')"
                                                    class="text-red-600 hover:text-red-800 p-2 hover:bg-red-50 rounded transition-colors"
                                                    title="Drop Enrollment">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Summary Stats (if has enrollments) -->
            <?php if (!empty($enrollments)): ?>
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h4 class="font-medium text-blue-900 mb-3">
                        <i class="fas fa-info-circle mr-2"></i>Quick Stats
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
                                    if (isset($e['grade']) && $e['grade'] <= 3.0) $completed++;
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

    <!-- Drop Enrollment Modal -->
    <div id="dropModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 text-center mt-4">Drop Enrollment</h3>
                <p class="text-sm text-gray-500 text-center mt-2" id="dropModalText">
                    Are you sure you want to drop this enrollment?
                </p>
                <div class="mt-6 flex space-x-3">
                    <button onclick="closeDropModal()"
                        class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium transition-colors">
                        Cancel
                    </button>
                    <button onclick="confirmDrop()"
                        class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                        Drop Enrollment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let enrollmentToDrop = null;

        document.addEventListener('DOMContentLoaded', function() {
            // Semester tabs
            const tabs = document.querySelectorAll('.semester-tab');
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    tabs.forEach(t => {
                        t.classList.remove('border-primary', 'text-primary');
                        t.classList.add('border-transparent', 'text-gray-500');
                    });
                    this.classList.remove('border-transparent', 'text-gray-500');
                    this.classList.add('border-primary', 'text-primary');

                    // Load data based on selected tab
                    const semester = this.dataset.semester;
                    filterBySemesterTab(semester);
                });
            });

            // Semester filter dropdown
            const semesterFilter = document.getElementById('semesterFilter');
            if (semesterFilter) {
                semesterFilter.addEventListener('change', function() {
                    filterBySemester(this.value);
                });
            }
        });

        function filterBySemesterTab(type) {
            // This would normally make an AJAX call to load data
            // For now, we'll just filter the existing table
            console.log('Loading enrollments:', type);

            // You can implement AJAX here if needed
            // fetch(`/admin/enrollments/student?id=<?php echo $student['id']; ?>&filter=${type}`)
        }

        function filterBySemester(value) {
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                // Skip the "no enrollments" row
                if (row.querySelector('td[colspan]')) {
                    return;
                }

                if (!value) {
                    row.style.display = '';
                } else {
                    const [semester, schoolYear] = value.split('|');
                    const semesterCell = row.querySelector('td:nth-child(4)');
                    const rowSemester = semesterCell?.querySelector('div:first-child')?.textContent.toLowerCase();
                    const rowYear = semesterCell?.querySelector('div:last-child')?.textContent;

                    if (rowSemester === semester.toLowerCase() && rowYear === schoolYear) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        }

        function viewDetails(enrollmentId) {
            // Implement view details functionality
            // You could show a modal with more details or navigate to a detail page
            console.log('View details for enrollment:', enrollmentId);
            alert('View details functionality - coming soon!\nEnrollment ID: ' + enrollmentId);
        }

        function dropEnrollment(enrollmentId, subjectCode) {
            enrollmentToDrop = enrollmentId;
            document.getElementById('dropModalText').innerHTML =
                `Are you sure you want to drop <strong>${subjectCode}</strong>?<br><span class="text-red-600">This action cannot be undone.</span>`;
            document.getElementById('dropModal').classList.remove('hidden');
        }

        function closeDropModal() {
            document.getElementById('dropModal').classList.add('hidden');
            enrollmentToDrop = null;
        }

        function confirmDrop() {
            if (!enrollmentToDrop) return;

            fetch('/admin/enrollments/drop', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        enrollment_id: enrollmentToDrop
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeDropModal();
                        location.reload();
                    } else {
                        alert(data.message || 'Failed to drop enrollment');
                        closeDropModal();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while dropping the enrollment');
                    closeDropModal();
                });
        }

        // Close modal when clicking outside
        document.getElementById('dropModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDropModal();
            }
        });
    </script>

    <?php include $this->resolve("partials/_admin_footer.php"); ?>