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
                     <h2 class="text-lg font-semibold text-gray-800">Class Schedules</h2>
                     <p class="text-sm text-gray-500">View and manage class schedules</p>
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
     <!-- Schedule Management Content -->
     <main class="p-6">
         <!-- Action Bar -->
         <div class="flex justify-end mb-6">

             <a href="/admin/classes/create" class="bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded-lg flex items-center">
                 <i class="fas fa-calendar-plus mr-2"></i> Add New Schedule
             </a>
         </div>

         <!-- Filters -->
         <div class="bg-white rounded-lg shadow mb-6">
             <div class="p-6">
                 <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                     <!-- Search -->
                     <div>
                         <form method="GET">
                             <div>
                                 <label class="block text-sm font-medium text-gray-700 mb-2">
                                     <i class="fas fa-search mr-2"></i>Search
                                 </label>
                                 <input
                                     value="<?php echo e((string)$searchTerm); ?>"
                                     name="s"
                                     type="text"
                                     placeholder="Search schedule..."
                                     class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                             </div>
                         </form>
                     </div>

                     <!-- Semester Filter -->
                     <div>
                         <form method="GET">
                             <!-- Preserve existing search parameter -->
                             <?php if (isset($_GET['s'])): ?>
                                 <input type="hidden" name="s" value="<?= e($_GET['s']) ?>">
                             <?php endif; ?>

                             <!-- Preserve existing page parameter -->
                             <?php if (isset($_GET['p'])): ?>
                                 <input type="hidden" name="p" value="<?= e($_GET['p']) ?>">
                             <?php endif; ?>

                             <!-- Preserve existing school_year parameter -->
                             <?php if (isset($_GET['school_year'])): ?>
                                 <input type="hidden" name="school_year" value="<?= e($_GET['school_year']) ?>">
                             <?php endif; ?>

                             <!-- Preserve existing day parameter -->
                             <?php if (isset($_GET['day'])): ?>
                                 <input type="hidden" name="day" value="<?= e($_GET['day']) ?>">
                             <?php endif; ?>

                             <!-- Preserve existing subject_id parameter -->
                             <?php if (isset($_GET['subject_id'])): ?>
                                 <input type="hidden" name="subject_id" value="<?= e($_GET['subject_id']) ?>">
                             <?php endif; ?>

                             <div>
                                 <label class="block text-sm font-medium text-gray-700 mb-2">
                                     <i class="fas fa-calendar-alt mr-2"></i>Semester
                                 </label>
                                 <select name="semester"
                                     onchange="this.form.submit()"
                                     class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                     <option value="">All Semesters</option>
                                     <option value="1st" <?php echo (($selectedSemester ?? '') == '1st' ? 'selected' : '') ?>>1st Semester</option>
                                     <option value="2nd" <?php echo (($selectedSemester ?? '') == '2nd' ? 'selected' : '') ?>>2nd Semester</option>
                                     <option value="Summer" <?php echo (($selectedSemester ?? '') == 'Summer' ? 'selected' : '') ?>>Summer</option>
                                 </select>
                             </div>
                         </form>
                     </div>

                     <!-- School Year Filter -->
                     <div>
                         <form method="GET">
                             <!-- Preserve existing search parameter -->
                             <?php if (isset($_GET['s'])): ?>
                                 <input type="hidden" name="s" value="<?= e($_GET['s']) ?>">
                             <?php endif; ?>

                             <!-- Preserve existing page parameter -->
                             <?php if (isset($_GET['p'])): ?>
                                 <input type="hidden" name="p" value="<?= e($_GET['p']) ?>">
                             <?php endif; ?>

                             <!-- Preserve existing semester parameter -->
                             <?php if (isset($_GET['semester'])): ?>
                                 <input type="hidden" name="semester" value="<?= e($_GET['semester']) ?>">
                             <?php endif; ?>

                             <!-- Preserve existing day parameter -->
                             <?php if (isset($_GET['day'])): ?>
                                 <input type="hidden" name="day" value="<?= e($_GET['day']) ?>">
                             <?php endif; ?>

                             <!-- Preserve existing subject_id parameter -->
                             <?php if (isset($_GET['subject_id'])): ?>
                                 <input type="hidden" name="subject_id" value="<?= e($_GET['subject_id']) ?>">
                             <?php endif; ?>

                             <div>
                                 <label class="block text-sm font-medium text-gray-700 mb-2">
                                     <i class="fas fa-graduation-cap mr-2"></i>School Year
                                 </label>
                                 <select name="school_year"
                                     onchange="this.form.submit()"
                                     class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                     <option value="">All Years</option>
                                     <?php foreach ($schoolYears as $sy): ?>
                                         <option value="<?php echo e($sy['school_year']); ?>"
                                             <?php echo (($selectedSchoolYear ?? '') == $sy['school_year']) ? 'selected' : ''; ?>>
                                             <?php echo e($sy['school_year']); ?>
                                         </option>
                                     <?php endforeach; ?>


                                 </select>
                             </div>
                         </form>
                     </div>

                     <!-- Day Filter -->
                     <div>
                         <form method="GET">
                             <!-- Preserve existing search parameter -->
                             <?php if (isset($_GET['s'])): ?>
                                 <input type="hidden" name="s" value="<?= e($_GET['s']) ?>">
                             <?php endif; ?>

                             <!-- Preserve existing page parameter -->
                             <?php if (isset($_GET['p'])): ?>
                                 <input type="hidden" name="p" value="<?= e($_GET['p']) ?>">
                             <?php endif; ?>

                             <!-- Preserve existing semester parameter -->
                             <?php if (isset($_GET['semester'])): ?>
                                 <input type="hidden" name="semester" value="<?= e($_GET['semester']) ?>">
                             <?php endif; ?>

                             <!-- Preserve existing school_year parameter -->
                             <?php if (isset($_GET['school_year'])): ?>
                                 <input type="hidden" name="school_year" value="<?= e($_GET['school_year']) ?>">
                             <?php endif; ?>

                             <!-- Preserve existing subject_id parameter -->
                             <?php if (isset($_GET['subject_id'])): ?>
                                 <input type="hidden" name="subject_id" value="<?= e($_GET['subject_id']) ?>">
                             <?php endif; ?>

                             <div>
                                 <label class="block text-sm font-medium text-gray-700 mb-2">
                                     <i class="fas fa-calendar-day mr-2"></i>Day
                                 </label>
                                 <select name="day"
                                     onchange="this.form.submit()"
                                     class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                     <option value="">All Days</option>
                                     <option value="Monday" <?php echo (($selectedDay ?? '') == 'Monday' ? 'selected' : '') ?>>Monday</option>
                                     <option value="Tuesday" <?php echo (($selectedDay ?? '') == 'Tuesday' ? 'selected' : '') ?>>Tuesday</option>
                                     <option value="Wednesday" <?php echo (($selectedDay ?? '') == 'Wednesday' ? 'selected' : '') ?>>Wednesday</option>
                                     <option value="Thursday" <?php echo (($selectedDay ?? '') == 'Thursday' ? 'selected' : '') ?>>Thursday</option>
                                     <option value="Friday" <?php echo (($selectedDay ?? '') == 'Friday' ? 'selected' : '') ?>>Friday</option>
                                     <option value="Saturday" <?php echo (($selectedDay ?? '') == 'Saturday' ? 'selected' : '') ?>>Saturday</option>
                                 </select>
                             </div>
                         </form>
                     </div>

                     <!-- Subject Filter -->
                     <div>
                         <form method="GET">
                             <!-- Preserve existing search parameter -->
                             <?php if (isset($_GET['s'])): ?>
                                 <input type="hidden" name="s" value="<?= e($_GET['s']) ?>">
                             <?php endif; ?>

                             <!-- Preserve existing page parameter -->
                             <?php if (isset($_GET['p'])): ?>
                                 <input type="hidden" name="p" value="<?= e($_GET['p']) ?>">
                             <?php endif; ?>

                             <!-- Preserve existing semester parameter -->
                             <?php if (isset($_GET['semester'])): ?>
                                 <input type="hidden" name="semester" value="<?= e($_GET['semester']) ?>">
                             <?php endif; ?>

                             <!-- Preserve existing school_year parameter -->
                             <?php if (isset($_GET['school_year'])): ?>
                                 <input type="hidden" name="school_year" value="<?= e($_GET['school_year']) ?>">
                             <?php endif; ?>

                             <!-- Preserve existing day parameter -->
                             <?php if (isset($_GET['day'])): ?>
                                 <input type="hidden" name="day" value="<?= e($_GET['day']) ?>">
                             <?php endif; ?>

                             <div>
                                 <label class="block text-sm font-medium text-gray-700 mb-2">
                                     <i class="fas fa-book mr-2"></i>Subject
                                 </label>
                                 <select name="subject_id"
                                     onchange="this.form.submit()"
                                     class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                     <option value="">All Subjects</option>
                                     <?php foreach ($subjects as $subject) : ?>
                                         <option value="<?php echo e($subject['id']); ?>"
                                             <?= (($_GET['subject_id'] ?? '') == $subject['id']) ? 'selected' : '' ?>>
                                             <?php echo e($subject['code'] . ' - ' . $subject['name']); ?>
                                         </option>
                                     <?php endforeach; ?>
                                 </select>
                             </div>
                         </form>
                     </div>
                 </div>
             </div>
         </div>

         <!-- Schedules Table -->
         <div class="bg-white rounded-lg shadow overflow-hidden">
             <div class="overflow-x-auto">
                 <table class="min-w-full divide-y divide-gray-200">
                     <thead class="bg-gray-50">
                         <tr>
                             <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                 Subject
                             </th>
                             <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                 Semester & Year
                             </th>
                             <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                 Day & Time
                             </th>
                             <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                 Room
                             </th>
                             <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                 Enrolled
                             </th>
                             <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                 Actions
                             </th>
                         </tr>
                     </thead>
                     <tbody class="bg-white divide-y divide-gray-200">
                         <?php foreach ($schedules as $schedule): ?>
                             <tr>
                                 <td class="px-6 py-4 whitespace-nowrap">
                                     <div class="text-sm font-medium text-gray-900"><?php echo e($schedule['code']); ?> - <?php echo e($schedule['subject_name']); ?></div>
                                     <div class="text-sm text-gray-500"><?php echo e($schedule['units']); ?> Units</div>
                                 </td>
                                 <td class="px-6 py-4 whitespace-nowrap">
                                     <div class="text-sm text-gray-900"><?php echo e($schedule['semester']); ?> Semester</div>
                                     <div class="text-sm text-gray-500"><?php echo e($schedule['school_year']); ?></div>
                                 </td>
                                 <td class="px-6 py-4 whitespace-nowrap">
                                     <div class="text-sm text-gray-900"><?php echo e($schedule['day']); ?></div>
                                     <div class="text-sm text-gray-500"><?php echo e($schedule['time']); ?></div>
                                 </td>
                                 <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                     <?php echo e($schedule['room']); ?>
                                 </td>
                                 <td class="px-6 py-4 whitespace-nowrap">
                                     <div class="text-sm text-gray-900"><?php echo e($schedule['enrolled_count']); ?>/<?php echo e($schedule['capacity']); ?></div>
                                     <div class="w-full bg-gray-200 rounded-full h-1.5">
                                         <div class="bg-green-600 h-1.5 rounded-full" style="width: <?php
                                                                                                    echo $schedule['capacity'] > 0
                                                                                                        ? round(($schedule['enrolled_count'] / $schedule['capacity']) * 100)
                                                                                                        : 0;
                                                                                                    ?>%"></div>
                                     </div>
                                 </td>
                                 <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                     <button class="text-indigo-600 hover:text-indigo-900 mr-3"><i class="fas fa-edit"></i></button>
                                     <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                                 </td>
                             </tr>
                         <?php endforeach; ?>

                     </tbody>
                 </table>
             </div>
             <!-- Pagination -->
             <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                 <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                     <div>
                         <p class="text-sm text-gray-700">
                             <?php if ($count > 0): ?>
                                 Showing <span class="font-medium"><?php echo $offset + 1; ?></span> to
                                 <span class="font-medium"><?php echo min($offset + count($schedules), $count); ?></span> of
                                 <span class="font-medium"><?php echo number_format($count); ?></span> results
                             <?php else: ?>
                                 No results found
                             <?php endif; ?>
                         </p>
                     </div>
                     <div>
                         <nav
                             class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                             aria-label="Pagination">
                             <?php if ($currentPage > 1): ?>
                                 <a
                                     href="/admin/classes?<?php echo e($previousPageQuery); ?>"
                                     class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                     <span class="sr-only">Previous</span>
                                     <i class="fas fa-chevron-left"></i>
                                 </a>
                             <?php endif; ?>
                             <?php foreach ($pageLinks as $pageNum => $query): ?>

                                 <a
                                     href="/admin/classes?<?php echo e($query); ?>"
                                     class="<?php echo $pageNum + 1 === $currentPage ? 'bg-blue-50 text-blue-600  hover:bg-blue-100 ' : 'bg-white text-gray-500 hover:bg-gray-50'; ?>relative inline-flex items-center px-4 py-2 border border-gray-300  text-sm font-medium ">
                                     <?php echo $pageNum + 1; ?>
                                 </a>
                             <?php endforeach; ?>

                             <?php if ($currentPage < $lastPage): ?>
                                 <a
                                     href="/admin/classes?<?php echo e($nextPageQuery); ?>"
                                     class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                     <span class="sr-only">Next</span>
                                     <i class="fas fa-chevron-right"></i>
                                 </a>
                             <?php endif; ?>
                         </nav>
                     </div>
                 </div>
             </div>
         </div>
     </main>
 </div>
 </div>

 <!-- Add Schedule Modal -->
 <div id="addScheduleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
     <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
         <div class="border-b px-6 py-4">
             <h3 class="text-lg font-semibold">Add New Schedule</h3>
         </div>
         <div class="p-6">
             <form>
                 <div class="mb-4">
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="subject">
                         Subject
                     </label>
                     <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="subject">
                         <option value="">Select Subject</option>
                         <option value="cs101">CS101 - Introduction to Programming</option>
                         <option value="math202">MATH202 - Calculus II</option>
                         <option value="bus105">BUS105 - Principles of Management</option>
                         <option value="eng204">ENG204 - Thermodynamics</option>
                         <option value="nur101">NUR101 - Anatomy and Physiology</option>
                     </select>
                 </div>
                 <div class="mb-4">
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="semester">
                         Semester
                     </label>
                     <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="semester">
                         <option value="">Select Semester</option>
                         <option value="1st">1st Semester</option>
                         <option value="2nd">2nd Semester</option>
                         <option value="summer">Summer</option>
                     </select>
                 </div>
                 <div class="mb-4">
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="schoolYear">
                         School Year
                     </label>
                     <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="schoolYear" type="text" placeholder="e.g., 2023-2024">
                 </div>
                 <div class="mb-4">
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="day">
                         Day
                     </label>
                     <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="day">
                         <option value="">Select Day</option>
                         <option value="monday">Monday</option>
                         <option value="tuesday">Tuesday</option>
                         <option value="wednesday">Wednesday</option>
                         <option value="thursday">Thursday</option>
                         <option value="friday">Friday</option>
                         <option value="saturday">Saturday</option>
                     </select>
                 </div>
                 <div class="mb-4">
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="time">
                         Time
                     </label>
                     <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="time" type="text" placeholder="e.g., 9:00 AM - 10:30 AM">
                 </div>
                 <div class="mb-4">
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="room">
                         Room
                     </label>
                     <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="room" type="text" placeholder="e.g., Room 205">
                 </div>
             </form>
         </div>
         <div class="border-t px-6 py-4 flex justify-end">
             <button id="cancelAddSchedule" class="bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 px-4 rounded mr-2">
                 Cancel
             </button>
             <button class="bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded">
                 Add Schedule
             </button>
         </div>
     </div>
 </div>

 <script>
     // Simple JavaScript for interactivity
     document.addEventListener('DOMContentLoaded', function() {
         // Highlight active menu item
         const navItems = document.querySelectorAll('nav a');
         navItems.forEach(item => {
             if (item.textContent.includes('Schedules')) {
                 item.classList.add('bg-primary', 'text-white');
                 item.classList.remove('hover:bg-gray-700');
             }
         });

         // Modal functionality
         const addScheduleBtn = document.getElementById('addScheduleBtn');
         const addScheduleModal = document.getElementById('addScheduleModal');
         const cancelAddSchedule = document.getElementById('cancelAddSchedule');

         addScheduleBtn.addEventListener('click', function() {
             addScheduleModal.classList.remove('hidden');
         });

         cancelAddSchedule.addEventListener('click', function() {
             addScheduleModal.classList.add('hidden');
         });

         // Close modal when clicking outside
         window.addEventListener('click', function(event) {
             if (event.target === addScheduleModal) {
                 addScheduleModal.classList.add('hidden');
             }
         });

         // Add event listeners for edit and delete buttons
         const editButtons = document.querySelectorAll('button.text-indigo-600');
         editButtons.forEach(button => {
             button.addEventListener('click', function() {
                 alert('Edit Schedule functionality would open here.');
             });
         });

         const deleteButtons = document.querySelectorAll('button.text-red-600');
         deleteButtons.forEach(button => {
             button.addEventListener('click', function() {
                 if (confirm('Are you sure you want to delete this schedule?')) {
                     alert('Schedule would be deleted (backend integration needed).');
                 }
             });
         });
     });
 </script>
 </body>

 </html>