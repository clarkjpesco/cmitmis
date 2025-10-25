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
                    <h2 class="text-lg font-semibold text-gray-800">All Subjects</h2>
                    <p class="text-sm text-gray-500">View and manage subject offerings</p>
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
    <!-- Subject Management Content -->
    <main class="p-6">
        <!-- Action Bar -->
        <div class="flex justify-end mb-6">

            <a href="/admin/subjects/create" class="bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded-lg flex items-center">
                <i class="fas fa-book-medical mr-2"></i> Add New Subject
            </a>
        </div>




        <!-- Search and Filter -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <form method="GET">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-search mr-2"></i>Search Subject
                                </label>
                                <input
                                    value="<?php echo e((string)$searchTerm); ?>"
                                    name="s"
                                    type="text"
                                    placeholder="Search subject by name or code..."
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </form>
                    </div>

                    <!-- Units Filter -->
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

                            <!-- Preserve existing status parameter -->
                            <?php if (isset($_GET['status'])): ?>
                                <input type="hidden" name="status" value="<?= e($_GET['status']) ?>">
                            <?php endif; ?>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-graduation-cap mr-2"></i>Units
                                </label>
                                <select name="units"
                                    onchange="this.form.submit()"
                                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="">All Units</option>
                                    <option value='1' <?php echo (($selectedUnits ?? '') == '1' ? 'selected' : '') ?>>1 Unit</option>
                                    <option value='2' <?php echo (($selectedUnits ?? '') == '2' ? 'selected' : '') ?>>2 Units</option>
                                    <option value='3' <?php echo (($selectedUnits ?? '') == '3' ? 'selected' : '') ?>>3 Units</option>
                                    <option value='4' <?php echo (($selectedUnits ?? '') == '4' ? 'selected' : '') ?>>4 Units</option>
                                    <option value='5' <?php echo (($selectedUnits ?? '') == '5' ? 'selected' : '') ?>>5 Units</option>
                                    <option value='6' <?php echo (($selectedUnits ?? '') == '6' ? 'selected' : '') ?>>6 Units</option>
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

                            <!-- Preserve existing units parameter -->
                            <?php if (isset($_GET['units'])): ?>
                                <input type="hidden" name="units" value="<?= e($_GET['units']) ?>">
                            <?php endif; ?>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-layer-group mr-2"></i>Status
                                </label>
                                <select name="status"
                                    onchange="this.form.submit()"
                                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="">All Status</option>
                                    <option value='active' <?php echo (($selectedStatus ?? '') == 'active' ? 'selected' : '') ?>>Active</option>
                                    <option value='inactive' <?php echo (($selectedStatus ?? '') == 'inactive' ? 'selected' : '') ?>>Inactive</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Subjects Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Subject Code
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Subject Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Units
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
                        <?php foreach ($subjects as $subject): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($subject['code']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($subject['name']); ?></div>
                                    <div class="text-sm text-gray-500"><?php
                                                                        $shortDesc = substr($subject['description'], 0, 100);
                                                                        $shortDesc = substr($shortDesc, 0, strrpos($shortDesc, ' ')) . '...';
                                                                        echo $shortDesc;

                                                                        ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo e($subject['units']); ?>
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
                                <span class="font-medium"><?php echo min($offset + count($subjects), $count); ?></span> of
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
                                    href="/admin/subjects?<?php echo e($previousPageQuery); ?>"
                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Previous</span>
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            <?php endif; ?>
                            <?php foreach ($pageLinks as $pageNum => $query): ?>

                                <a
                                    href="/admin/subjects?<?php echo e($query); ?>"
                                    class="<?php echo $pageNum + 1 === $currentPage ? 'bg-blue-50 text-blue-600  hover:bg-blue-100 ' : 'bg-white text-gray-500 hover:bg-gray-50'; ?>relative inline-flex items-center px-4 py-2 border border-gray-300  text-sm font-medium ">
                                    <?php echo $pageNum + 1; ?>
                                </a>
                            <?php endforeach; ?>

                            <?php if ($currentPage < $lastPage): ?>
                                <a
                                    href="/admin/subjects?<?php echo e($nextPageQuery); ?>"
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