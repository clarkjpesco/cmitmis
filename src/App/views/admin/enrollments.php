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
                    <h2 class="text-lg font-semibold text-gray-800">Student Enrollments</h2>
                    <p class="text-gray-600 mt-1">View and manage student enrollments</p>
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
    <main class="p-6">
        <div class="max-w-7xl mx-auto">


            <!-- Search and Filter -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div class="md:col-span-2">
                            <form method="GET">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-search mr-2"></i>Search Student
                                    </label>
                                    <input
                                        value="<?php echo e((string)$searchTerm); ?>"
                                        name="s"
                                        type="text"
                                        placeholder="Search student..."
                                        class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                </div>
                            </form>
                        </div>

                        <!-- Course Filter -->
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

                                <!-- Preserve existing year_level parameter -->
                                <?php if (isset($_GET['year_level'])): ?>
                                    <input type="hidden" name="year_level" value="<?= e($_GET['year_level']) ?>">
                                <?php endif; ?>


                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-graduation-cap mr-2"></i>Course
                                    </label>
                                    <select name="course"
                                        onchange="this.form.submit()"
                                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                        <option value="">All Courses</option>
                                        <?php foreach ($courses as $course) : ?>
                                            <option value="<?php echo e($course['code']); ?>"
                                                <?= (($_GET['course'] ?? '') === $course['code']) ? 'selected' : '' ?>>
                                                <?php echo e(strtoupper($course['code'])); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </form>
                        </div>

                        <!-- Year Level Filter -->
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

                                <!-- Preserve existing course parameter -->
                                <?php if (isset($_GET['course'])): ?>
                                    <input type="hidden" name="course" value="<?= e($_GET['course']) ?>">
                                <?php endif; ?>



                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-layer-group mr-2"></i>Year Level
                                    </label>
                                    <select name="year_level"
                                        onchange="this.form.submit()"
                                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                        <option value="">All Levels</option>
                                        <option value="1" <?php echo (($selectedYearLevel ?? '') == 1 ? 'selected' : '') ?>>1st Year</option>
                                        <option value="2" <?php echo (($selectedYearLevel ?? '') == 2 ? 'selected' : '') ?>>2nd Year</option>
                                        <option value="3" <?php echo (($selectedYearLevel ?? '') == 3 ? 'selected' : '') ?>>3rd Year</option>
                                        <option value="4" <?php echo (($selectedYearLevel ?? '') == 4 ? 'selected' : '') ?>>4th Year</option>
                                    </select>
                                </div>
                            </form>
                        </div>


                    </div>
                </div>
            </div>
            <!-- Students List -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Students</h3>
                    <p class="text-sm text-gray-500 mt-1">Select a student to view their enrollments</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Info</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year Level</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Enrollments</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="studentsTableBody" class="bg-white divide-y divide-gray-200">
                            <?php foreach ($students as $student): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-primary rounded-full flex items-center justify-center text-white font-semibold">
                                                <?php echo strtoupper(substr($student['full_name'], 0, 1)); ?>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900"><?php echo e($student['full_name']); ?></div>
                                                <div class="text-sm text-gray-500"><?php echo e($student['student_number']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900"><?php echo e($student['course']); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-900">Year <?php echo e($student['year_level']); ?></td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-book-open mr-1"></i>
                                            <?php echo $student['enrollment_count'] ?? 0; ?> subjects
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <a href="/admin/enrollments/student/<?php echo $student['id']; ?>/details"
                                            class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-lg transition-colors">
                                            <i class="fas fa-eye mr-2"></i>View Enrollments
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div id="emptyState" class="hidden p-12 text-center">
                    <i class="fas fa-user-graduate text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No students found</h3>
                    <p class="text-gray-500">Try adjusting your search or filters</p>
                </div>
            </div>

            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            <?php if ($count > 0): ?>
                                Showing <span class="font-medium"><?php echo $offset + 1; ?></span> to
                                <span class="font-medium"><?php echo min($offset + count($students), $count); ?></span> of
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
                                    href="/admin/enrollments?<?php echo e($previousPageQuery); ?>"
                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Previous</span>
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            <?php endif; ?>
                            <?php foreach ($pageLinks as $pageNum => $query): ?>

                                <a
                                    href="/admin/enrollments?<?php echo e($query); ?>"
                                    class="<?php echo $pageNum + 1 === $currentPage ? 'bg-blue-50 text-blue-600  hover:bg-blue-100 ' : 'bg-white text-gray-500 hover:bg-gray-50'; ?>relative inline-flex items-center px-4 py-2 border border-gray-300  text-sm font-medium ">
                                    <?php echo $pageNum + 1; ?>
                                </a>
                            <?php endforeach; ?>

                            <?php if ($currentPage < $lastPage): ?>
                                <a
                                    href="/admin/enrollments?<?php echo e($nextPageQuery); ?>"
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



    <?php include $this->resolve("partials/_admin_footer.php"); ?>