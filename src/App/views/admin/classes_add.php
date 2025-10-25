       <?php include $this->resolve("partials/_admin_header.php"); ?>

       <div class="flex-1 overflow-auto">
           <!-- Header -->
           <header class="bg-white shadow-sm">
               <div class="flex items-center justify-between p-4">
                   <div class="flex items-center">
                       <button
                           id="mobile-menu-toggle"
                           class="p-1 mr-4 text-gray-600 lg:hidden hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded"
                           aria-label="Toggle navigation menu">
                           <i class="fas fa-bars text-xl"></i>
                       </button>
                       <div>
                           <h2 class="text-lg font-semibold text-gray-800">Add New Schedule</h2>
                           <p class="text-sm text-gray-500">Create and assign class timetables by selecting subjects, instructors, rooms, and time slots.</p>
                       </div>
                   </div>

                   <div class="flex items-center gap-2">


                       <div class="relative">
                           <a
                               href="/admin/dashboard"
                               class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                               <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center font-medium text-white shadow-sm">
                                   <?php echo strtoupper(substr($_SESSION['user']['full_name'], 0, 1)); ?>
                               </div>
                               <span class="hidden md:block text-gray-700 font-medium">
                                   <?php echo e($_SESSION['user']['full_name']); ?>
                               </span>
                           </a>
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
           <!-- Form Content -->
           <main class="p-6">
               <?php include $this->resolve("partials/_csrf.php"); ?>
               <div class="max-w-4xl mx-auto">
                   <!-- Breadcrumb -->
                   <nav class="mb-6">
                       <ol class="flex items-center space-x-2 text-sm text-gray-500">
                           <li><a href="/admin/dashboard" class="hover:text-primary">Dashboard</a></li>
                           <li><i class="fas fa-chevron-right text-xs"></i></li>
                           <li><a href="/admin/classes" class="hover:text-primary">Class Schedules</a></li>
                           <li><i class="fas fa-chevron-right text-xs"></i></li>
                           <li class="text-gray-900">Add Schedule</li>
                       </ol>
                   </nav>

                   <!-- Main Form Card -->
                   <div class="bg-white rounded-lg shadow-lg">
                       <!-- Card Header -->
                       <div class="border-b border-gray-200 px-6 py-4">
                           <h2 class="text-lg font-semibold text-gray-800">Schedule Information</h2>
                           <p class="text-sm text-gray-500 mt-1">Fill in the details to create a new class schedule</p>
                       </div>

                       <!-- Form -->
                       <form id="addScheduleForm" class="p-6">
                           <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                               <!-- Subject Selection -->
                               <div class="lg:col-span-2">
                                   <label class="block text-sm font-medium text-gray-700 mb-2">
                                       Subject <span class="text-red-500">*</span>
                                   </label>
                                   <select id="subject" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" required>
                                       <option value="">Select a subject...</option>
                                       <?php foreach ($subjects as $subject): ?>

                                           <option value="<?php echo e($subject['id']); ?>" data-units="<?php echo e($subject['units']); ?>"><?php echo e($subject['code']); ?> - <?php echo e($subject['name']); ?> (<?php echo e($subject['units']); ?> Units)</option>

                                       <?php endforeach; ?>
                                   </select>
                               </div>

                               <!-- Academic Term -->
                               <div>
                                   <label class="block text-sm font-medium text-gray-700 mb-2">
                                       Semester <span class="text-red-500">*</span>
                                   </label>
                                   <select id="semester" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" required>
                                       <option value="">Select semester...</option>
                                       <option value="1st">1st Semester</option>
                                       <option value="2nd">2nd Semester</option>
                                       <option value="summer">Summer</option>
                                   </select>
                               </div>

                               <div>
                                   <label class="block text-sm font-medium text-gray-700 mb-2">
                                       School Year <span class="text-red-500">*</span>
                                   </label>
                                   <input id="schoolYear" type="text" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="e.g., 2024-2025" pattern="[0-9]{4}-[0-9]{4}" required>
                               </div>

                               <!-- Schedule Days -->
                               <div class="lg:col-span-2">
                                   <label class="block text-sm font-medium text-gray-700 mb-2">
                                       Class Days <span class="text-red-500">*</span>
                                   </label>
                                   <div class="grid grid-cols-2 md:grid-cols-7 gap-2">
                                       <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                           <input type="checkbox" name="days" value="monday" class="mr-2 text-primary">
                                           <span class="text-sm font-medium">Mon</span>
                                       </label>
                                       <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                           <input type="checkbox" name="days" value="tuesday" class="mr-2 text-primary">
                                           <span class="text-sm font-medium">Tue</span>
                                       </label>
                                       <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                           <input type="checkbox" name="days" value="wednesday" class="mr-2 text-primary">
                                           <span class="text-sm font-medium">Wed</span>
                                       </label>
                                       <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                           <input type="checkbox" name="days" value="thursday" class="mr-2 text-primary">
                                           <span class="text-sm font-medium">Thu</span>
                                       </label>
                                       <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                           <input type="checkbox" name="days" value="friday" class="mr-2 text-primary">
                                           <span class="text-sm font-medium">Fri</span>
                                       </label>
                                       <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                           <input type="checkbox" name="days" value="saturday" class="mr-2 text-primary">
                                           <span class="text-sm font-medium">Sat</span>
                                       </label>
                                       <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                           <input type="checkbox" name="days" value="sunday" class="mr-2 text-primary">
                                           <span class="text-sm font-medium">Sun</span>
                                       </label>
                                   </div>
                               </div>

                               <!-- Time -->
                               <div>
                                   <label class="block text-sm font-medium text-gray-700 mb-2">
                                       Start Time <span class="text-red-500">*</span>
                                   </label>
                                   <input id="startTime" type="time" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" required>
                               </div>

                               <div>
                                   <label class="block text-sm font-medium text-gray-700 mb-2">
                                       End Time <span class="text-red-500">*</span>
                                   </label>
                                   <input id="endTime" type="time" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" required>
                               </div>

                               <!-- Room and Capacity -->
                               <div>
                                   <label class="block text-sm font-medium text-gray-700 mb-2">
                                       Room <span class="text-red-500">*</span>
                                   </label>
                                   <input id="room" type="text" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="e.g., Room 205, Computer Lab 3" required>
                               </div>

                               <div>
                                   <label class="block text-sm font-medium text-gray-700 mb-2">
                                       Capacity <span class="text-red-500">*</span>
                                   </label>
                                   <input id="capacity" type="number" min="1" max="100" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Maximum students" required>
                               </div>

                               <!-- Instructor -->
                               <div class="lg:col-span-2">
                                   <label class="block text-sm font-medium text-gray-700 mb-2">
                                       Instructor
                                   </label>
                                   <input id="instructor" type="text" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Instructor name (optional)">
                               </div>

                               <!-- Additional Notes -->
                               <div class="lg:col-span-2">
                                   <label class="block text-sm font-medium text-gray-700 mb-2">
                                       Notes
                                   </label>
                                   <textarea id="notes" rows="3" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Any additional information or requirements..."></textarea>
                               </div>
                           </div>

                           <!-- Form Actions -->
                           <div class="border-t border-gray-200 pt-6 mt-8 flex justify-end space-x-3">
                               <a href="/admin/classes" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-3 px-6 rounded-lg transition duration-200">
                                   Cancel
                               </a>

                               <button type="submit" class="bg-primary hover:bg-indigo-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200">
                                   <i class="fas fa-plus mr-2"></i>Create Schedule
                               </button>
                           </div>
                       </form>
                   </div>

                   <!-- Schedule Preview Card -->
                   <div id="schedulePreview" class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-6 hidden">
                       <h3 class="text-lg font-semibold text-blue-900 mb-4">
                           <i class="fas fa-eye mr-2"></i>Schedule Preview
                       </h3>
                       <div id="previewContent" class="text-sm text-blue-800">
                           <!-- Preview will be populated by JavaScript -->
                       </div>
                   </div>
               </div>
           </main>
       </div>
       </div>

       <!-- Success Modal -->
       <div id="successModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
           <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
               <div class="p-6 text-center">
                   <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                       <i class="fas fa-check text-2xl text-green-600"></i>
                   </div>
                   <h3 class="text-lg font-semibold text-gray-900 mb-2">Schedule Created Successfully!</h3>
                   <p class="text-gray-600 mb-6">The new class schedule has been added to the system.</p>
                   <div class="flex justify-center space-x-3">
                       <button id="addAnother" class="bg-primary hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
                           Add Another
                       </button>
                       <a href="/admin/classes" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                           View All Schedules
                       </a>
                   </div>
               </div>
           </div>
       </div>

       <script>
           <?php include $this->resolve("partials/_csrf_helpers.js"); ?>
           document.addEventListener('DOMContentLoaded', function() {
               const form = document.getElementById('addScheduleForm');
               const successModal = document.getElementById('successModal');
               const addAnotherBtn = document.getElementById('addAnother');
               const schedulePreview = document.getElementById('schedulePreview');
               const previewContent = document.getElementById('previewContent');

               // Auto-generate school year based on current date
               const currentYear = new Date().getFullYear();
               const nextYear = currentYear + 1;
               document.getElementById('schoolYear').value = `${currentYear}-${nextYear}`;

               // Real-time form validation and preview
               const formFields = ['subject', 'semester', 'schoolYear', 'startTime', 'endTime', 'room', 'capacity'];
               formFields.forEach(fieldId => {
                   const field = document.getElementById(fieldId);
                   if (field) {
                       field.addEventListener('input', updatePreview);
                   }
               });

               // Days checkboxes
               const dayCheckboxes = document.querySelectorAll('input[name="days"]');
               dayCheckboxes.forEach(checkbox => {
                   checkbox.addEventListener('change', updatePreview);
               });

               function updatePreview() {
                   const subject = document.getElementById('subject');
                   const selectedSubject = subject.options[subject.selectedIndex];
                   const semester = document.getElementById('semester').value;
                   const schoolYear = document.getElementById('schoolYear').value;
                   const startTime = document.getElementById('startTime').value;
                   const endTime = document.getElementById('endTime').value;
                   const room = document.getElementById('room').value;
                   const capacity = document.getElementById('capacity').value;

                   const selectedDays = Array.from(dayCheckboxes)
                       .filter(cb => cb.checked)
                       .map(cb => cb.value.charAt(0).toUpperCase() + cb.value.slice(1));

                   if (selectedSubject.value && semester && selectedDays.length > 0 && startTime && endTime) {
                       const subjectText = selectedSubject.textContent;
                       const daysText = selectedDays.join(', ');
                       const timeText = startTime && endTime ? `${formatTime(startTime)} - ${formatTime(endTime)}` : '';

                       previewContent.innerHTML = `
                        <div class="space-y-2">
                            <div><strong>Subject:</strong> ${subjectText}</div>
                            <div><strong>Term:</strong> ${semester} Semester, ${schoolYear}</div>
                            <div><strong>Schedule:</strong> ${daysText} ${timeText}</div>
                            ${room ? `<div><strong>Room:</strong> ${room}</div>` : ''}
                            ${capacity ? `<div><strong>Capacity:</strong> ${capacity} students</div>` : ''}
                        </div>
                    `;
                       schedulePreview.classList.remove('hidden');
                   } else {
                       schedulePreview.classList.add('hidden');
                   }
               }

               function formatTime(timeStr) {
                   if (!timeStr) return '';
                   const [hours, minutes] = timeStr.split(':');
                   const hour12 = hours % 12 || 12;
                   const ampm = hours >= 12 ? 'PM' : 'AM';
                   return `${hour12}:${minutes} ${ampm}`;
               }

               // Form submission
               form.addEventListener('submit', function(e) {
                   e.preventDefault();

                   // Validate required fields
                   if (!validateForm()) {
                       return;
                   }

                   // Prepare form data
                   const formData = new FormData();
                   formData.append('subject_id', document.getElementById('subject').value);
                   formData.append('semester', document.getElementById('semester').value);
                   formData.append('school_year', document.getElementById('schoolYear').value);
                   formData.append('start_time', document.getElementById('startTime').value);
                   formData.append('end_time', document.getElementById('endTime').value);
                   formData.append('room', document.getElementById('room').value);
                   formData.append('capacity', document.getElementById('capacity').value);
                   formData.append('instructor', document.getElementById('instructor').value || '');
                   formData.append('notes', document.getElementById('notes').value || '');

                   // Add selected days
                   const selectedDays = Array.from(dayCheckboxes)
                       .filter(cb => cb.checked)
                       .map(cb => cb.value);

                   selectedDays.forEach(day => {
                       formData.append('days[]', day);
                   });

                   // Submit form
                   const submitBtn = form.querySelector('button[type="submit"]');
                   const originalText = submitBtn.innerHTML;
                   submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating...';
                   submitBtn.disabled = true;

                   // fetch('/admin/classes/create', {
                   //         method: 'POST',
                   //         body: formData
                   //     })
                   //     .then(response => response.json())
                   //     .then(data => {
                   //         if (data.success) {
                   //             successModal.classList.remove('hidden');
                   //         } else {
                   //             alert('Error: ' + (data.errors ? data.errors.join('\n') : 'Unknown error occurred'));
                   //         }
                   //     })
                   //     .catch(error => {
                   //         console.error('Error:', error);
                   //         alert('Network error occurred. Please try again.');
                   //     })
                   //     .finally(() => {
                   //         submitBtn.innerHTML = originalText;
                   //         submitBtn.disabled = false;
                   //     });

                   // Use csrfFetch instead of regular fetch
                   csrfManager.csrfFetch('/admin/classes/create', {
                           method: 'POST',
                           body: formData
                       })
                       .then(response => response.json())
                       .then(data => {
                           if (data.success) {
                               successModal.classList.remove('hidden');

                               // Check if server returned a new token and update it
                               if (data.new_token) {
                                   csrfManager.updateToken(data.new_token);
                                   console.log('Updated CSRF token from server response');
                               }
                           } else {
                               alert('Error: ' + (data.errors ? data.errors.join('\n') : 'Unknown error occurred'));
                           }
                       })
                       .catch(error => {
                           console.error('Error:', error);
                           alert('Network error occurred. Please try again.');
                       })
                       .finally(() => {
                           submitBtn.innerHTML = originalText;
                           submitBtn.disabled = false;
                       });
               });

               function validateForm() {
                   const requiredFields = ['subject', 'semester', 'schoolYear', 'startTime', 'endTime', 'room', 'capacity'];
                   let isValid = true;

                   // Check required fields
                   requiredFields.forEach(fieldId => {
                       const field = document.getElementById(fieldId);
                       if (!field.value.trim()) {
                           field.classList.add('border-red-500');
                           isValid = false;
                       } else {
                           field.classList.remove('border-red-500');
                       }
                   });

                   // Check if at least one day is selected
                   const selectedDays = Array.from(dayCheckboxes).filter(cb => cb.checked);
                   if (selectedDays.length === 0) {
                       alert('Please select at least one class day.');
                       isValid = false;
                   }

                   // Validate time range
                   const startTime = document.getElementById('startTime').value;
                   const endTime = document.getElementById('endTime').value;
                   if (startTime && endTime && startTime >= endTime) {
                       alert('End time must be after start time.');
                       isValid = false;
                   }

                   return isValid;
               }



               // Success modal actions
               addAnotherBtn.addEventListener('click', function() {
                   successModal.classList.add('hidden');
                   form.reset();
                   schedulePreview.classList.add('hidden');
                   // Auto-fill school year again
                   document.getElementById('schoolYear').value = `${currentYear}-${nextYear}`;
               });

               // Time conflict checking (basic example)
               function checkTimeConflicts() {
                   // This would normally check against existing schedules in the database
                   // For now, just a placeholder
                   console.log('Checking for time conflicts...');
               }

               document.getElementById('startTime').addEventListener('change', checkTimeConflicts);
               document.getElementById('endTime').addEventListener('change', checkTimeConflicts);
           });
       </script>
       </body>

       </html>