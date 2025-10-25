<?php include $this->resolve("partials/_admin_header.php"); ?>
<!-- Main Content -->
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
                    <h2 class="text-lg font-semibold text-gray-800">All Students</h2>
                    <p class="text-sm text-gray-500">View and manage student records</p>
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

    <!-- Student Management Content -->
    <main class="p-6">
        <!-- Action Bar -->
        <div class="flex gap-3 justify-end mb-6">
            <a href="/admin/students/import"
                class="bg-emerald-600 hover:bg-emerald-700 text-white py-2.5 px-5 rounded-lg flex items-center transition-colors duration-200 shadow-sm hover:shadow-md">
                <i class="fas fa-file-import mr-2"></i>
                Import Excel
            </a>
            <a href="/admin/students/create"
                class="bg-primary hover:bg-primary-dark text-white py-2.5 px-5 rounded-lg flex items-center transition-colors duration-200 shadow-sm hover:shadow-md">
                <i class="fas fa-user-plus mr-2"></i>
                Add New Student
            </a>
        </div>

        <!-- Filters -->
        <!-- <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Course</label>
                <select class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                    <option>All Courses</option>
                    <option value="">Computer Science</option>
                    <option>Business Administration</option>
                    <option>Engineering</option>
                    <option>Nursing</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Year Level</label>
                <select class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                    <option>All Levels</option>
                    <option>1st Year</option>
                    <option>2nd Year</option>
                    <option>3rd Year</option>
                    <option>4th Year</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                    <option>All Status</option>
                    <option>Active</option>
                    <option>Inactive</option>
                    <option>Graduated</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Enrollment</label>
                <select class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                    <option>All Enrollments</option>
                    <option>Enrolled</option>
                    <option>Not Enrolled</option>
                </select>
            </div>
        </div>
    </div> -->

        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-1">
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

                            <!-- Preserve existing status parameter -->
                            <?php if (isset($_GET['status'])): ?>
                                <input type="hidden" name="status" value="<?= e($_GET['status']) ?>">
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

                            <!-- Preserve existing status parameter -->
                            <?php if (isset($_GET['status'])): ?>
                                <input type="hidden" name="status" value="<?= e($_GET['status']) ?>">
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

                    <!-- Status Filter -->
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

                            <!-- Preserve existing year_level parameter -->
                            <?php if (isset($_GET['year_level'])): ?>
                                <input type="hidden" name="year_level" value="<?= e($_GET['year_level']) ?>">
                            <?php endif; ?>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user-check mr-2"></i>Status
                                </label>
                                <select name="status"
                                    onchange="this.form.submit()"
                                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="">All Status</option>
                                    <option value='active' <?php echo (($selectedStatus ?? '') == 'active' ? 'selected' : '') ?>>Active</option>
                                    <option value='inactive' <?php echo (($selectedStatus ?? '') == 'inactive' ? 'selected' : '') ?>>Inactive</option>
                                    <option value='graduated' <?php echo (($selectedStatus ?? '') == 'graduated' ? 'selected' : '') ?>>Graduated</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Students Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Student
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Student Number
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Course
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Year Level
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold"><?php echo strtoupper($student['full_name'][0]); ?></div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?php echo e($student['full_name']); ?></div>
                                            <!-- <div class="text-sm text-gray-500">john.smith@example.com</div> -->
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e($student['student_number']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo e($student['course']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo e($student['year_level']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="text-indigo-600 hover:text-indigo-900 mr-3"><i class="fas fa-edit"></i></button>
                                    <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <!-- <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-bold">M</div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">Maria Garcia</div>
                                    <div class="text-sm text-gray-500">maria.garcia@example.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            2023-002
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Business Administration
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            3
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Active
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-indigo-600 hover:text-indigo-900 mr-3"><i class="fas fa-edit"></i></button>
                            <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center font-bold">R</div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">Robert Johnson</div>
                                    <div class="text-sm text-gray-500">robert.johnson@example.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            2023-003
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Engineering
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            1
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Active
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-indigo-600 hover:text-indigo-900 mr-3"><i class="fas fa-edit"></i></button>
                            <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center font-bold">S</div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">Sarah Williams</div>
                                    <div class="text-sm text-gray-500">sarah.williams@example.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            2023-004
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Nursing
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            4
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Graduating
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-indigo-600 hover:text-indigo-900 mr-3"><i class="fas fa-edit"></i></button>
                            <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center font-bold">D</div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">David Brown</div>
                                    <div class="text-sm text-gray-500">david.brown@example.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            2023-005
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Computer Science
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            2
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Inactive
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-indigo-600 hover:text-indigo-900 mr-3"><i class="fas fa-edit"></i></button>
                            <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr> -->
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
                                    href="/admin/students?<?php echo e($previousPageQuery); ?>"
                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Previous</span>
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            <?php endif; ?>
                            <?php foreach ($pageLinks as $pageNum => $query): ?>

                                <a
                                    href="/admin/students?<?php echo e($query); ?>"
                                    class="<?php echo $pageNum + 1 === $currentPage ? 'bg-blue-50 text-blue-600  hover:bg-blue-100 ' : 'bg-white text-gray-500 hover:bg-gray-50'; ?>relative inline-flex items-center px-4 py-2 border border-gray-300  text-sm font-medium ">
                                    <?php echo $pageNum + 1; ?>
                                </a>
                            <?php endforeach; ?>

                            <?php if ($currentPage < $lastPage): ?>
                                <a
                                    href="/admin/students?<?php echo e($nextPageQuery); ?>"
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


</body>

</html>