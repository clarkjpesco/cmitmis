<?php include $this->resolve("partials/_admin_header.php"); ?>
<div class="flex-1 overflow-auto">
    <main class="p-6">
        <?php include $this->resolve("partials/_csrf.php"); ?>
        <div id="notification-container" class="fixed top-4 right-4 z-50 max-w-sm w-full"></div>

        <div class="max-w-4xl mx-auto">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="/admin/grades/student/<?php echo $enrollment['student_id']; ?>/details" class="inline-flex items-center text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Student Grades
                </a>
            </div>

            <!-- Student & Subject Info Banner -->
            <div class="bg-gradient-to-r from-primary to-primary-dark text-white rounded-lg shadow-lg p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-16 w-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-2xl font-bold">
                            <?php echo strtoupper(substr($enrollment['student_name'], 0, 1)); ?>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-2xl font-bold"><?php echo e($enrollment['student_name']); ?></h2>
                            <div class="flex items-center space-x-4 mt-1 text-sm opacity-90">
                                <span><i class="fas fa-id-card mr-1"></i><?php echo e($enrollment['student_number']); ?></span>
                                <span><i class="fas fa-graduation-cap mr-1"></i><?php echo e($enrollment['course']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-white border-opacity-30">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="opacity-75">Subject:</span>
                            <div class="font-semibold"><?php echo e($enrollment['subject_code']); ?></div>
                        </div>
                        <div>
                            <span class="opacity-75">Units:</span>
                            <div class="font-semibold"><?php echo e($enrollment['units']); ?> units</div>
                        </div>
                        <div>
                            <span class="opacity-75">Semester:</span>
                            <div class="font-semibold"><?php echo e(ucfirst($enrollment['semester'])); ?></div>
                        </div>
                        <div>
                            <span class="opacity-75">School Year:</span>
                            <div class="font-semibold"><?php echo e($enrollment['school_year']); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subject Details Card -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-book-open mr-2 text-primary"></i>
                        <?php echo e($enrollment['subject_name']); ?>
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div class="flex items-center">
                            <i class="fas fa-calendar text-gray-400 text-lg mr-3"></i>
                            <div>
                                <div class="text-xs text-gray-500">Day</div>
                                <div class="font-medium text-gray-900"><?php echo e($enrollment['day']); ?></div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock text-gray-400 text-lg mr-3"></i>
                            <div>
                                <div class="text-xs text-gray-500">Time</div>
                                <div class="font-medium text-gray-900"><?php echo e($enrollment['time']); ?></div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-door-open text-gray-400 text-lg mr-3"></i>
                            <div>
                                <div class="text-xs text-gray-500">Room</div>
                                <div class="font-medium text-gray-900"><?php echo e($enrollment['room']); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex items-center">
                            <i class="fas fa-user-tie text-gray-400 text-lg mr-3"></i>
                            <div>
                                <div class="text-xs text-gray-500">Instructor</div>
                                <div class="font-medium text-gray-900"><?php echo e($enrollment['instructor']); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grade Input Form -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <?php echo (isset($enrollment['grade']) && $enrollment['grade']) ? 'Update Grade' : 'Input Grade'; ?>
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Enter the final grade and remarks for this subject</p>
                </div>

                <form id="gradeForm" class="p-6">
                    <input type="hidden" name="enrollment_id" value="<?php echo $enrollment['enrollment_id']; ?>">

                    <div class="space-y-6">
                        <!-- Current Grade Display -->
                        <?php if (isset($enrollment['grade']) && $enrollment['grade']): ?>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h4 class="font-medium text-blue-900 mb-2">Current Grade Information</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="text-sm text-blue-700">Current Grade:</span>
                                        <div class="text-2xl font-bold text-blue-900"><?php echo number_format($enrollment['grade'], 2); ?></div>
                                    </div>
                                    <div>
                                        <span class="text-sm text-blue-700">Current Remarks:</span>
                                        <div class="text-lg font-medium text-blue-900"><?php echo e($enrollment['remarks'] ?? 'N/A'); ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Grade Input -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="grade">
                                <i class="fas fa-chart-line mr-2 text-primary"></i>Grade *
                            </label>
                            <select
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-lg"
                                id="grade"
                                name="grade"
                                required>
                                <option value="">Select grade...</option>
                                <optgroup label="Excellent (1.0-1.2)">
                                    <option value="1.0" <?php echo (isset($enrollment['grade']) && $enrollment['grade'] == 1.0) ? 'selected' : ''; ?>>1.0 (99-100%)</option>
                                    <option value="1.1" <?php echo (isset($enrollment['grade']) && $enrollment['grade'] == 1.1) ? 'selected' : ''; ?>>1.1 (97-98%)</option>
                                    <option value="1.2" <?php echo (isset($enrollment['grade']) && $enrollment['grade'] == 1.2) ? 'selected' : ''; ?>>1.2 (95-96%)</option>
                                </optgroup>
                                <optgroup label="Very Good (1.3-1.5)">
                                    <option value="1.3" <?php echo (isset($enrollment['grade']) && $enrollment['grade'] == 1.3) ? 'selected' : ''; ?>>1.3 (93-94%)</option>
                                    <option value="1.4" <?php echo (isset($enrollment['grade']) && $enrollment['grade'] == 1.4) ? 'selected' : ''; ?>>1.4 (91-92%)</option>
                                    <option value="1.5" <?php echo (isset($enrollment['grade']) && $enrollment['grade'] == 1.5) ? 'selected' : ''; ?>>1.5 (90%)</option>
                                </optgroup>
                                <optgroup label="Good (1.6-2.0)">
                                    <option value="1.6" <?php echo (isset($enrollment['grade']) && $enrollment['grade'] == 1.6) ? 'selected' : ''; ?>>1.6 (89%)</option>
                                    <option value="1.7" <?php echo (isset($enrollment['grade']) && $enrollment['grade'] == 1.7) ? 'selected' : ''; ?>>1.7 (88%)</option>
                                    <option value="1.8" <?php echo (isset($enrollment['grade']) && $enrollment['grade'] == 1.8) ? 'selected' : ''; ?>>1.8 (87%)</option>
                                    <option value="1.9" <?php echo (isset($enrollment['grade']) && $enrollment['grade'] == 1.9) ? 'selected' : ''; ?>>1.9 (86%)</option>
                                    <option value="2.0" <?php echo (isset($enrollment['grade']) && $enrollment['grade'] == 2.0) ? 'selected' : ''; ?>>2.0 (85%)</option>
                                </optgroup>
                                <optgroup label="Satisfactory (2.1-2.5)">
                                    <option value="2.1" <?php echo (isset($enrollment['grade']) && $enrollment['grade'] == 2.1) ? 'selected' : ''; ?>>2.1 (84%)</option>
                                    <option value="2.2" <?php echo (isset($enrollment['grade']) && $enrollment['grade'] == 2.2) ? 'selected' : ''; ?>>2.2 (83%)</option>
                                    <option value="2.3" <?php echo (isset($enrollment['grade']) && $enrollment['grade'] == 2.3) ? 'selected' : ''; ?>>2.3 (82%)</option>
                                    <option value="2.4" <?php echo (isset($enrollment['grade']) && $enrollment['grade'] == 2.4) ? 'selected' : ''; ?>>2.4 (81%)</option>
                                    <option value="2.5" <?php echo (isset($enrollment['grade']) && $enrollment['grade'] == 2.5) ? 'selected' : ''; ?>>2.5 (80%)</option>
                                </optgroup>
                                <optgroup label="Fair (2.6-3.0)">
                                    <option value="2.6" <?php echo (isset($enrollment['grade']) && $enrollment['grade'] == 2.6) ? 'selected' : ''; ?>>2.6 (79%)</option>
                                    <option value="2.7" <?php echo (isset($enrollment['grade']) && $enrollment['grade'] == 2.7) ? 'selected' : ''; ?>>2.7 (78%)</option>
                                    <option value="2.8" <?php echo (isset($enrollment['grade']) && $enrollment['grade'] == 2.8) ? 'selected' : ''; ?>>2.8 (77%)</option>
                                    <option value="2.9" <?php echo (isset($enrollment['grade']) && $enrollment['grade'] == 2.9) ? 'selected' : ''; ?>>2.9 (76%)</option>
                                    <option value="3.0" <?php echo (isset($enrollment['grade']) && $enrollment['grade'] == 3.0) ? 'selected' : ''; ?>>3.0 (75%)</option>
                                </optgroup>
                                <optgroup label="Special Grades">
                                    <option value="4.0" <?php echo (isset($enrollment['grade']) && $enrollment['grade'] == 4.0) ? 'selected' : ''; ?>>4.0 (INC - Incomplete)</option>
                                    <option value="5.0" <?php echo (isset($enrollment['grade']) && $enrollment['grade'] == 5.0) ? 'selected' : ''; ?>>5.0 (Failed - Below 75%)</option>
                                    <option value="7.0" <?php echo (isset($enrollment['grade']) && $enrollment['grade'] == 7.0) ? 'selected' : ''; ?>>7.0 (WD - Withdrawn)</option>
                                    <option value="9.0" <?php echo (isset($enrollment['grade']) && $enrollment['grade'] == 9.0) ? 'selected' : ''; ?>>9.0 (DRP - Dropped)</option>
                                </optgroup>
                            </select>
                            <p class="mt-2 text-sm text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Passing grades are 1.0 to 3.0
                            </p>
                        </div>

                        <!-- Grade Preview -->
                        <div id="gradePreview" class="hidden">
                            <div class="p-4 rounded-lg border-2" id="gradePreviewBox">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-sm text-gray-600">Grade Status:</span>
                                        <div class="text-xl font-bold" id="gradeStatus"></div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-sm text-gray-600">Entered Grade:</span>
                                        <div class="text-3xl font-bold" id="gradeValue"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Remarks -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="remarks">
                                <i class="fas fa-comment mr-2 text-primary"></i>Remarks *
                            </label>
                            <select
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                id="remarks"
                                name="remarks"
                                required>
                                <option value="">Select remarks...</option>
                                <option value="Passed" <?php echo (isset($enrollment['remarks']) && $enrollment['remarks'] === 'Passed') ? 'selected' : ''; ?>>Passed</option>
                                <option value="Failed" <?php echo (isset($enrollment['remarks']) && $enrollment['remarks'] === 'Failed') ? 'selected' : ''; ?>>Failed</option>
                                <option value="Incomplete" <?php echo (isset($enrollment['remarks']) && $enrollment['remarks'] === 'Incomplete') ? 'selected' : ''; ?>>Incomplete</option>
                                <option value="Withdrawn" <?php echo (isset($enrollment['remarks']) && $enrollment['remarks'] === 'Withdrawn') ? 'selected' : ''; ?>>Withdrawn</option>
                                <option value="Dropped" <?php echo (isset($enrollment['remarks']) && $enrollment['remarks'] === 'Dropped') ? 'selected' : ''; ?>>Dropped</option>
                            </select>
                        </div>

                        <!-- Grading Guidelines -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-800 mb-3">
                                <i class="fas fa-graduation-cap mr-2"></i>Grading System
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-6 gap-2 text-xs">
                                <div class="bg-green-100 rounded p-2 text-center border border-green-200">
                                    <div class="font-bold text-green-800">1.0-1.2</div>
                                    <div class="text-green-600">99-95%</div>
                                    <div class="text-green-700">Excellent</div>
                                </div>
                                <div class="bg-blue-100 rounded p-2 text-center border border-blue-200">
                                    <div class="font-bold text-blue-800">1.3-1.5</div>
                                    <div class="text-blue-600">94-90%</div>
                                    <div class="text-blue-700">Very Good</div>
                                </div>
                                <div class="bg-cyan-100 rounded p-2 text-center border border-cyan-200">
                                    <div class="font-bold text-cyan-800">1.6-2.0</div>
                                    <div class="text-cyan-600">89-85%</div>
                                    <div class="text-cyan-700">Good</div>
                                </div>
                                <div class="bg-yellow-100 rounded p-2 text-center border border-yellow-200">
                                    <div class="font-bold text-yellow-800">2.1-2.5</div>
                                    <div class="text-yellow-600">84-80%</div>
                                    <div class="text-yellow-700">Satisfactory</div>
                                </div>
                                <div class="bg-orange-100 rounded p-2 text-center border border-orange-200">
                                    <div class="font-bold text-orange-800">2.6-3.0</div>
                                    <div class="text-orange-600">79-75%</div>
                                    <div class="text-orange-700">Fair</div>
                                </div>
                                <div class="bg-red-100 rounded p-2 text-center border border-red-200">
                                    <div class="font-bold text-red-800">5.0</div>
                                    <div class="text-red-600">&lt;75%</div>
                                    <div class="text-red-700">Failed</div>
                                </div>
                            </div>
                            <div class="mt-3 grid grid-cols-3 gap-2 text-xs">
                                <div class="bg-purple-50 rounded p-2 text-center border border-purple-200">
                                    <div class="font-bold text-purple-800">4.0</div>
                                    <div class="text-purple-700">Incomplete</div>
                                </div>
                                <div class="bg-gray-100 rounded p-2 text-center border border-gray-300">
                                    <div class="font-bold text-gray-800">7.0</div>
                                    <div class="text-gray-700">Withdrawn</div>
                                </div>
                                <div class="bg-gray-100 rounded p-2 text-center border border-gray-300">
                                    <div class="font-bold text-gray-800">9.0</div>
                                    <div class="text-gray-700">Dropped</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-2"></i>
                            All fields marked with * are required
                        </div>
                        <div class="flex space-x-3">
                            <a href="/admin/grades/student/<?php echo $enrollment['student_id']; ?>/details"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 px-6 rounded-lg font-medium transition duration-200">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </a>
                            <button
                                type="button"
                                id="submitGradeBtn"
                                class="bg-primary hover:bg-primary-dark text-white py-2 px-6 rounded-lg font-medium transition duration-200">
                                <i class="fas fa-save mr-2"></i>
                                <?php echo (isset($enrollment['grade']) && $enrollment['grade']) ? 'Update Grade' : 'Submit Grade'; ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script>
        <?php include $this->resolve("partials/_csrf_helpers.js"); ?>

        document.addEventListener('DOMContentLoaded', function() {
            const gradeSelect = document.getElementById('grade');
            const remarksSelect = document.getElementById('remarks');
            const gradePreview = document.getElementById('gradePreview');
            const gradePreviewBox = document.getElementById('gradePreviewBox');
            const gradeStatus = document.getElementById('gradeStatus');
            const gradeValue = document.getElementById('gradeValue');
            const submitBtn = document.getElementById('submitGradeBtn');

            // Grade descriptions and colors
            const gradeInfo = {
                '1.0': {
                    desc: 'Excellent',
                    color: 'green',
                    percentage: '99-100%'
                },
                '1.1': {
                    desc: 'Excellent',
                    color: 'green',
                    percentage: '97-98%'
                },
                '1.2': {
                    desc: 'Excellent',
                    color: 'green',
                    percentage: '95-96%'
                },
                '1.3': {
                    desc: 'Very Good',
                    color: 'blue',
                    percentage: '93-94%'
                },
                '1.4': {
                    desc: 'Very Good',
                    color: 'blue',
                    percentage: '91-92%'
                },
                '1.5': {
                    desc: 'Very Good',
                    color: 'blue',
                    percentage: '90%'
                },
                '1.6': {
                    desc: 'Good',
                    color: 'cyan',
                    percentage: '89%'
                },
                '1.7': {
                    desc: 'Good',
                    color: 'cyan',
                    percentage: '88%'
                },
                '1.8': {
                    desc: 'Good',
                    color: 'cyan',
                    percentage: '87%'
                },
                '1.9': {
                    desc: 'Good',
                    color: 'cyan',
                    percentage: '86%'
                },
                '2.0': {
                    desc: 'Good',
                    color: 'cyan',
                    percentage: '85%'
                },
                '2.1': {
                    desc: 'Satisfactory',
                    color: 'yellow',
                    percentage: '84%'
                },
                '2.2': {
                    desc: 'Satisfactory',
                    color: 'yellow',
                    percentage: '83%'
                },
                '2.3': {
                    desc: 'Satisfactory',
                    color: 'yellow',
                    percentage: '82%'
                },
                '2.4': {
                    desc: 'Satisfactory',
                    color: 'yellow',
                    percentage: '81%'
                },
                '2.5': {
                    desc: 'Satisfactory',
                    color: 'yellow',
                    percentage: '80%'
                },
                '2.6': {
                    desc: 'Fair',
                    color: 'orange',
                    percentage: '79%'
                },
                '2.7': {
                    desc: 'Fair',
                    color: 'orange',
                    percentage: '78%'
                },
                '2.8': {
                    desc: 'Fair',
                    color: 'orange',
                    percentage: '77%'
                },
                '2.9': {
                    desc: 'Fair',
                    color: 'orange',
                    percentage: '76%'
                },
                '3.0': {
                    desc: 'Fair',
                    color: 'orange',
                    percentage: '75%'
                },
                '4.0': {
                    desc: 'Incomplete',
                    color: 'purple',
                    percentage: 'INC'
                },
                '5.0': {
                    desc: 'Failed',
                    color: 'red',
                    percentage: 'Below 75%'
                },
                '7.0': {
                    desc: 'Withdrawn',
                    color: 'gray',
                    percentage: 'WD'
                },
                '9.0': {
                    desc: 'Dropped',
                    color: 'gray',
                    percentage: 'DRP'
                }
            };

            // Update grade preview
            function updateGradePreview() {
                const grade = gradeSelect.value;

                if (!grade) {
                    gradePreview.classList.add('hidden');
                    return;
                }

                const info = gradeInfo[grade];
                gradeValue.textContent = grade;

                // Set color scheme
                const colorClasses = {
                    'green': {
                        bg: 'bg-green-50',
                        border: 'border-green-500',
                        text: 'text-green-800',
                        valueText: 'text-green-600'
                    },
                    'blue': {
                        bg: 'bg-blue-50',
                        border: 'border-blue-500',
                        text: 'text-blue-800',
                        valueText: 'text-blue-600'
                    },
                    'cyan': {
                        bg: 'bg-cyan-50',
                        border: 'border-cyan-500',
                        text: 'text-cyan-800',
                        valueText: 'text-cyan-600'
                    },
                    'yellow': {
                        bg: 'bg-yellow-50',
                        border: 'border-yellow-500',
                        text: 'text-yellow-800',
                        valueText: 'text-yellow-600'
                    },
                    'orange': {
                        bg: 'bg-orange-50',
                        border: 'border-orange-500',
                        text: 'text-orange-800',
                        valueText: 'text-orange-600'
                    },
                    'purple': {
                        bg: 'bg-purple-50',
                        border: 'border-purple-500',
                        text: 'text-purple-800',
                        valueText: 'text-purple-600'
                    },
                    'red': {
                        bg: 'bg-red-50',
                        border: 'border-red-500',
                        text: 'text-red-800',
                        valueText: 'text-red-600'
                    },
                    'gray': {
                        bg: 'bg-gray-50',
                        border: 'border-gray-500',
                        text: 'text-gray-800',
                        valueText: 'text-gray-600'
                    }
                };

                const colors = colorClasses[info.color];
                gradePreviewBox.className = `p-4 rounded-lg border-2 ${colors.bg} ${colors.border}`;
                gradeStatus.className = `text-xl font-bold ${colors.text}`;
                gradeValue.className = `text-3xl font-bold ${colors.valueText}`;

                gradeStatus.textContent = `${info.desc} (${info.percentage})`;

                // Auto-set remarks based on grade
                const gradeNum = parseFloat(grade);
                if (gradeNum >= 1.0 && gradeNum <= 3.0) {
                    remarksSelect.value = 'Passed';
                } else if (gradeNum === 4.0) {
                    remarksSelect.value = 'Incomplete';
                } else if (gradeNum === 5.0) {
                    remarksSelect.value = 'Failed';
                } else if (gradeNum === 7.0) {
                    remarksSelect.value = 'Withdrawn';
                } else if (gradeNum === 9.0) {
                    remarksSelect.value = 'Dropped';
                }

                gradePreview.classList.remove('hidden');
            }

            gradeSelect.addEventListener('change', updateGradePreview);

            // Trigger preview on load if grade exists
            if (gradeSelect.value) {
                updateGradePreview();
            }

            // Submit grade
            submitBtn.addEventListener('click', async function() {
                const grade = parseFloat(gradeSelect.value);
                const remarks = remarksSelect.value;

                if (!remarks) {
                    showNotification('Please select remarks', 'error');
                    return;
                }

                if (!grade) {
                    showNotification('Please select a grade', 'error');
                    return;
                }

                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';

                try {
                    const data = {
                        enrollment_id: <?php echo $enrollment['enrollment_id']; ?>,
                        grade: grade,
                        remarks: remarks
                    };

                    const response = await csrfManager.csrfFetch('/admin/grades/update', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();

                    if (result.new_token) {
                        csrfManager.updateToken(result.new_token);
                    }

                    if (result.success) {
                        showNotification(result.message || 'Grade saved successfully!', 'success');
                        setTimeout(() => {
                            window.location.href = '/admin/grades/student/<?php echo $enrollment['student_id']; ?>/details';
                        }, 1500);
                    } else {
                        showNotification(result.message || 'Failed to save grade.', 'error');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                } catch (error) {
                    console.error('Error saving grade:', error);
                    showNotification('An error occurred while saving the grade.', 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        });

        // Function to show notifications
        function showNotification(message, type = 'success') {
            const container = document.getElementById('notification-container');
            const notification = document.createElement('div');

            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';

            notification.className = `${bgColor} text-white p-4 rounded-lg shadow-lg mb-3 transition-all duration-300 transform`;
            notification.innerHTML = `
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                    <span>${message}</span>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

            container.appendChild(notification);

            setTimeout(() => {
                if (notification.parentElement) {
                    notification.classList.add('opacity-0', 'translate-x-full');
                    setTimeout(() => notification.remove(), 300);
                }
            }, 5000);
        }
    </script>


    <?php include $this->resolve("partials/_admin_footer.php"); ?>