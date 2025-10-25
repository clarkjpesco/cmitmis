<?php include $this->resolve("partials/_admin_header.php"); ?>
<div class="flex-1 overflow-auto">
    <!-- Header -->

    <main class="p-6">
        <?php include $this->resolve("partials/_csrf.php"); ?>
        <div id="notification-container" class="fixed top-4 right-4 z-50 max-w-sm w-full"></div>
        <div class="max-w-4xl mx-auto">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="/admin/enrollments/student/<?php echo $student['id']; ?>/details" class="inline-flex items-center text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Student Enrollments
                </a>
            </div>

            <!-- Student Info Banner -->
            <div class="bg-gradient-to-r from-primary to-primary-dark text-white rounded-lg shadow-lg p-6 mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-16 w-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-2xl font-bold">
                        <?php echo strtoupper(substr($student['full_name'], 0, 1)); ?>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold">Enrolling: <?php echo e($student['full_name']); ?></h2>
                        <div class="flex items-center space-x-4 mt-1 text-sm opacity-90">
                            <span><i class="fas fa-id-card mr-1"></i><?php echo e($student['student_number']); ?></span>
                            <span><i class="fas fa-graduation-cap mr-1"></i><?php echo e($student['course']); ?></span>
                            <span><i class="fas fa-layer-group mr-1"></i>Year <?php echo e($student['year_level']); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enrollment Form -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Process New Enrollment</h3>
                    <p class="text-sm text-gray-500 mt-1">Select a subject and schedule to enroll this student</p>
                </div>

                <form id="addEnrollmentForm" class="p-6">
                    <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Semester & School Year -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="semester">
                                <i class="fas fa-calendar mr-2 text-primary"></i>Semester *
                            </label>
                            <select class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" id="semester" required>
                                <option value="">Select semester...</option>
                                <option value="1st">1st Semester</option>
                                <option value="2nd">2nd Semester</option>
                                <option value="summer">Summer</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="schoolYear">
                                <i class="fas fa-graduation-cap mr-2 text-primary"></i>School Year *
                            </label>
                            <input class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                id="schoolYear"
                                type="text"
                                placeholder="e.g., 2025-2026"
                                pattern="[0-9]{4}-[0-9]{4}"
                                title="Format: YYYY-YYYY"
                                required>
                        </div>

                        <!-- Subject Selection -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="subject">
                                <i class="fas fa-book mr-2 text-primary"></i>Subject *
                            </label>
                            <select class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" id="subject" required>
                                <option value="">Select subject...</option>
                                <?php foreach ($subjects as $subject): ?>
                                    <option value="<?php echo $subject['id']; ?>" data-units="<?php echo $subject['units']; ?>">
                                        <?php echo e($subject['code']); ?> - <?php echo e($subject['name']); ?> (<?php echo $subject['units']; ?> units)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Current Enrollment Info -->
                    <div id="studentInfo" class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200 hidden">
                        <h4 class="font-medium text-blue-900 mb-3">Current Semester Info</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                            <div>
                                <span class="text-blue-700">Enrolled Subjects:</span>
                                <div class="text-xl font-bold text-blue-900" id="currentEnrollments">0</div>
                            </div>
                            <div>
                                <span class="text-blue-700">Total Units:</span>
                                <div class="text-xl font-bold text-blue-900" id="totalUnits">0</div>
                            </div>
                            <div>
                                <span class="text-blue-700">Adding Units:</span>
                                <div class="text-xl font-bold text-blue-900" id="addingUnits">0</div>
                            </div>
                            <div>
                                <span class="text-blue-700">New Total:</span>
                                <div class="text-xl font-bold text-blue-900" id="newTotal">0</div>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule Selection -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-2 text-primary"></i>Available Schedules *
                        </label>
                        <div id="schedulesListDiv" class="space-y-3">
                            <div class="text-gray-500 text-center py-8 bg-gray-50 rounded-lg">
                                <i class="fas fa-info-circle text-3xl mb-2"></i>
                                <p>Please select semester, school year, and subject first</p>
                            </div>
                        </div>
                    </div>

                    <!-- Warnings & Conflicts -->
                    <div id="warningsSection" class="hidden mt-6 space-y-3">
                        <div id="scheduleConflict" class="bg-red-50 border border-red-200 rounded-lg p-4 hidden">
                            <h4 class="font-medium text-red-800 mb-2 flex items-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i>Schedule Conflict Detected
                            </h4>
                            <div id="conflictDetails" class="text-sm text-red-700"></div>
                        </div>

                        <div id="unitOverload" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 hidden">
                            <h4 class="font-medium text-yellow-800 mb-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>Unit Overload Warning
                            </h4>
                            <div id="overloadDetails" class="text-sm text-yellow-700"></div>
                        </div>
                    </div>

                    <!-- Enrollment Preview -->
                    <div id="enrollmentPreview" class="bg-green-50 border border-green-200 rounded-lg p-4 mt-6 hidden">
                        <h4 class="font-medium text-green-800 mb-2 flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>Ready to Enroll
                        </h4>
                        <div id="previewContent" class="text-sm text-green-700"></div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-2"></i>
                            All fields marked with * are required
                        </div>
                        <div class="flex space-x-3">
                            <a href="/admin/enrollments/student/<?php echo $student['id']; ?>/details"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 px-6 rounded-lg font-medium transition duration-200">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </a>

                            <button id="enrollStudentBtn" class="bg-green-600 hover:bg-green-700 text-white py-2 px-6 rounded-lg font-medium transition duration-200">
                                <i class="fas fa-user-check mr-2"></i>Enroll Student
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        <?php include $this->resolve("partials/_csrf_helpers.js"); ?>

        const studentId = <?php echo $student['id']; ?>;
        let selectedScheduleId = null;
        let currentUnits = 0;

        document.addEventListener('DOMContentLoaded', function() {
            const semesterSelect = document.getElementById('semester');
            const schoolYearInput = document.getElementById('schoolYear');
            const subjectSelect = document.getElementById('subject');
            const schedulesListDiv = document.getElementById('schedulesListDiv');
            const studentInfoDiv = document.getElementById('studentInfo');
            const enrollmentForm = document.getElementById('addEnrollmentForm');

            const saveBtn = document.getElementById('enrollStudentBtn');
            // Save button click
            saveBtn.addEventListener('click', function() {
                enrollStudent();
            });


            // Load student info when semester/year changes
            function loadStudentInfo() {
                const semester = semesterSelect.value;
                const schoolYear = schoolYearInput.value;

                if (!semester || !schoolYear) return;

                fetch(`/admin/api/student-info?student_id=${studentId}&semester=${semester}&school_year=${schoolYear}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            currentUnits = data.enrollment_data.total_units;
                            document.getElementById('currentEnrollments').textContent = data.enrollment_data.total_enrollments;
                            document.getElementById('totalUnits').textContent = currentUnits;
                            studentInfoDiv.classList.remove('hidden');
                            updateUnitCalculation();
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }

            // Update unit calculation
            function updateUnitCalculation() {
                const selectedOption = subjectSelect.options[subjectSelect.selectedIndex];
                const addingUnits = selectedOption ? parseInt(selectedOption.dataset.units || 0) : 0;
                const newTotal = currentUnits + addingUnits;
                const maxUnits = semesterSelect.value === 'summer' ? 12 : 24;

                document.getElementById('addingUnits').textContent = addingUnits;
                document.getElementById('newTotal').textContent = newTotal;

                const unitOverloadDiv = document.getElementById('unitOverload');
                const warningsSection = document.getElementById('warningsSection');

                if (newTotal > maxUnits) {
                    document.getElementById('overloadDetails').innerHTML = `
                <p>Current units: <strong>${currentUnits}</strong></p>
                <p>Adding: <strong>${addingUnits} units</strong></p>
                <p>New total: <strong>${newTotal} units</strong> (Maximum: ${maxUnits} units)</p>
                <p class="mt-2 font-medium text-yellow-900">⚠️ This enrollment requires overload approval.</p>
            `;
                    unitOverloadDiv.classList.remove('hidden');
                    warningsSection.classList.remove('hidden');
                } else {
                    unitOverloadDiv.classList.add('hidden');
                    checkIfWarningsEmpty();
                }
            }

            function checkIfWarningsEmpty() {
                const warnings = document.querySelectorAll('#warningsSection > div:not(.hidden)');
                if (warnings.length === 0) {
                    document.getElementById('warningsSection').classList.add('hidden');
                }
            }


            // Load schedules
            function loadSchedules() {
                const subjectId = subjectSelect.value;
                const semester = semesterSelect.value;
                const schoolYear = schoolYearInput.value;

                if (!subjectId || !semester || !schoolYear) {
                    schedulesListDiv.innerHTML = '<div class="text-gray-500 text-center py-8 bg-gray-50 rounded-lg"><i class="fas fa-info-circle text-3xl mb-2"></i><p>Please select all fields above</p></div>';
                    return;
                }

                schedulesListDiv.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-3xl text-primary"></i><p class="mt-2 text-gray-600">Loading schedules...</p></div>';

                fetch(`/admin/api/schedules?subject_id=${subjectId}&semester=${semester}&school_year=${schoolYear}&student_id=${studentId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            displaySchedules(data.schedules);
                        } else {
                            schedulesListDiv.innerHTML = `<div class="text-red-500 text-center py-8">${data.message}</div>`;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        schedulesListDiv.innerHTML = '<div class="text-red-500 text-center py-8">Error loading schedules</div>';
                    });
            }

            function displaySchedules(schedules) {
                if (schedules.length === 0) {
                    schedulesListDiv.innerHTML = '<div class="text-gray-500 text-center py-8 bg-gray-50 rounded-lg"><i class="fas fa-calendar-times text-3xl mb-2"></i><p>No schedules available for this subject</p></div>';
                    return;
                }

                schedulesListDiv.innerHTML = '';
                schedules.forEach(schedule => {
                    const isDisabled = schedule.has_conflict || schedule.is_full;
                    const scheduleCard = document.createElement('div');
                    scheduleCard.className = `border rounded-lg p-4 transition-all ${isDisabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer hover:shadow-md hover:border-primary'}`;

                    if (schedule.has_conflict) {
                        scheduleCard.classList.add('border-red-300', 'bg-red-50');
                    } else if (schedule.is_full) {
                        scheduleCard.classList.add('border-yellow-300', 'bg-yellow-50');
                    } else {
                        scheduleCard.classList.add('border-gray-300');
                    }

                    scheduleCard.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center flex-1">
                        <input type="radio" name="schedule" value="${schedule.id}" id="schedule_${schedule.id}"
                               ${isDisabled ? 'disabled' : ''} class="w-4 h-4 text-primary focus:ring-primary">
                        <div class="ml-4 flex-1">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                                <div><i class="fas fa-calendar text-gray-400 mr-1"></i>${schedule.day}</div>
                                <div><i class="fas fa-clock text-gray-400 mr-1"></i>${schedule.time}</div>
                                <div><i class="fas fa-door-open text-gray-400 mr-1"></i>${schedule.room}</div>
                                <div><i class="fas fa-user-tie text-gray-400 mr-1"></i>${schedule.instructor}</div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right ml-4">
                        <div class="text-sm font-medium ${schedule.is_full ? 'text-red-600' : 'text-green-600'}">
                            ${schedule.available_slots}/${schedule.capacity} slots
                        </div>
                        ${schedule.has_conflict ? '<div class="text-xs text-red-600 mt-1"><i class="fas fa-exclamation-triangle mr-1"></i>Conflict</div>' : ''}
                        ${schedule.is_full ? '<div class="text-xs text-yellow-600 mt-1"><i class="fas fa-ban mr-1"></i>Full</div>' : ''}
                    </div>
                </div>
            `;

                    if (!isDisabled) {
                        scheduleCard.addEventListener('click', function(e) {
                            if (e.target.type !== 'radio') {
                                document.getElementById(`schedule_${schedule.id}`).checked = true;
                                selectedScheduleId = schedule.id;
                                updateEnrollmentPreview(schedule);
                            }
                        });
                    }

                    schedulesListDiv.appendChild(scheduleCard);
                });

                // Add change listener to radio buttons
                document.querySelectorAll('input[name="schedule"]').forEach(radio => {
                    radio.addEventListener('change', function() {
                        selectedScheduleId = this.value;
                        const schedule = schedules.find(s => s.id == this.value);
                        updateEnrollmentPreview(schedule);
                    });
                });
            }

            function updateEnrollmentPreview(schedule) {
                if (!schedule) return;

                const subjectOption = subjectSelect.options[subjectSelect.selectedIndex];
                const previewContent = document.getElementById('previewContent');

                previewContent.innerHTML = `
            <div class="grid grid-cols-2 gap-3">
                <div><strong>Subject:</strong> ${schedule.code} - ${schedule.name}</div>
                <div><strong>Units:</strong> ${schedule.units}</div>
                <div><strong>Schedule:</strong> ${schedule.day}</div>
                <div><strong>Time:</strong> ${schedule.time}</div>
                <div><strong>Room:</strong> ${schedule.room}</div>
                <div><strong>Instructor:</strong> ${schedule.instructor}</div>
                <div><strong>Semester:</strong> ${semesterSelect.options[semesterSelect.selectedIndex].text}</div>
                <div><strong>School Year:</strong> ${schoolYearInput.value}</div>
            </div>
        `;

                document.getElementById('enrollmentPreview').classList.remove('hidden');
            }

            // Event listeners
            semesterSelect.addEventListener('change', function() {
                loadStudentInfo();
                loadSchedules();
            });

            schoolYearInput.addEventListener('input', function() {
                if (this.value.match(/^\d{4}-\d{4}$/)) {
                    loadStudentInfo();
                    loadSchedules();
                }
            });

            subjectSelect.addEventListener('change', function() {
                updateUnitCalculation();
                loadSchedules();
            });

            async function enrollStudent() {

                if (!selectedScheduleId) {
                    alert('Please select a schedule');
                    return;
                }

                const saveBtn = document.getElementById('enrollStudentBtn');
                const originalText = saveBtn.innerHTML;

                saveBtn.disabled = true;
                saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';

                try {

                    // const formData = new FormData();
                    // formData.append('student_id', studentId);
                    // formData.append('schedule_id', selectedScheduleId);


                    const data = {
                        student_id: studentId,
                        schedule_id: selectedScheduleId
                    };
                    console.log('=== SENDING DATA ===');
                    console.log(JSON.stringify(data, null, 2));


                    const response = await csrfManager.csrfFetch(`/admin/enrollments/create`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });

                    // To get the raw response text
                    // const rawText = await response.text();
                    // console.log('Raw response text:', rawText);

                    // You can then parse the text to a JSON object
                    // try {
                    //     const result = JSON.parse(rawText);
                    //     console.log('Response data (parsed):', result);
                    // } catch (e) {
                    //     console.error('Failed to parse JSON:', e);
                    // }
                    const result = await response.json();

                    if (result.new_token) {
                        csrfManager.updateToken(result.new_token);
                    }

                    if (result.success) {
                        showNotification(result.message || 'Student enrolled successfully!', 'success');


                        // Refresh the page to show updated modifications history
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    } else {
                        showNotification(result.message || 'Failed to enroll student.', 'error');
                    }

                } catch (error) {

                    //   alert('An error occurred while enrolling the student');
                    console.error('Error enrolling student:', error);
                    showNotification('An error occurred while enrolling the student.', 'error');

                } finally {
                    // Reset button state
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = originalText;
                }
            }
            // Form submission
            // enrollmentForm.addEventListener('submit', function(e) {
            //     e.preventDefault();

            //     if (!selectedScheduleId) {
            //         alert('Please select a schedule');
            //         return;
            //     }

            //     const submitBtn = e.submitter;
            //     const originalText = submitBtn.innerHTML;
            //     submitBtn.disabled = true;
            //     submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';

            //     const formData = new FormData();
            //     formData.append('student_id', studentId);
            //     formData.append('schedule_id', selectedScheduleId);

            //     csrfManager.csrfFetch('/admin/enrollments/create', {
            //             method: 'POST',
            //             body: formData
            //         })
            //         .then(response => {
            //             if (response.redirected) {
            //                 window.location.href = response.url;
            //             } else {
            //                 return response.text().then(text => {
            //                     throw new Error('Unexpected response');
            //                 });
            //             }
            //         })
            //         .catch(error => {
            //             console.error('Error:', error);
            //             alert('An error occurred while enrolling the student');
            //             submitBtn.disabled = false;
            //             submitBtn.innerHTML = originalText;
            //         });
            // });

            // Save and add another button

        });

        // Function to show notifications
        function showNotification(message, type = 'success') {
            const container = document.getElementById('notification-container');
            const notification = document.createElement('div');

            // Set background color based on type
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

            // Add to container
            container.appendChild(notification);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.classList.add('opacity-0', 'translate-x-full');
                    setTimeout(() => notification.remove(), 300);
                }
            }, 5000);
        }
    </script>

    <?php include $this->resolve("partials/_admin_footer.php"); ?>