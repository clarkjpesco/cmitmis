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
                    <h2 class="text-lg font-semibold text-gray-800">All Courses</h2>
                    <p class="text-sm text-gray-500">View and manage course records</p>
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


        <!-- Action Bar -->
        <div
            class="bg-white rounded-lg shadow p-4 mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
            <!-- <div class="flex-1 max-w-md"> -->
            <div class="w-full lg:flex-1 lg:max-w-md">
                <form method="GET" class="flex flex-col sm:flex-row gap-3">

                    <div class="relative flex-1">
                        <input
                            name="s"
                            type="text"
                            placeholder="Search course..."
                            class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" />
                        <i
                            class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <button type="submit"
                        class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-600 flex items-center justify-center whitespace-nowrap">
                        Search
                    </button>
                </form>
            </div>
            <a href="/admin/courses/create"
                id="addCoursesBtn"
                class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 flex items-center">
                <i class="fas fa-plus mr-2"></i> Add New Course
            </a>
        </div>

        <!-- Categories Grid -->
        <div
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Category Card 1 -->
            <?php foreach ($courses as $course): ?>
                <div class="bg-white rounded-lg shadow category-card p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div
                            class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-blue-600 text-xl"></i>
                        </div>
                        <div class="flex space-x-2">
                            <a href="/admin/courses/<?php echo e($course['id']); ?>/edit"
                                class="text-primary hover:text-blue-700 edit-category"
                                data-id="1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button
                                class="text-accent hover:text-red-700 delete-category"
                                data-id="1">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <h3 class="font-semibold text-lg text-secondary mb-2">
                        <?php echo e($course['name']); ?>
                    </h3>
                    <p class="text-gray-600 text-sm mb-4">
                        <?php echo e($course['description']); ?>
                    </p>
                    <div class="flex justify-between items-center">

                        <span
                            class="text-xs px-2 py-1 <?php echo ($course['is_active'] === 1 ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800'); ?> rounded-full"><?php echo ($course['is_active'] === 1 ? 'Active' : 'Inactive'); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Add New Category Card -->
            <a href="/admin/courses/create">
                <div
                    class="border-2 border-dashed border-gray-300 rounded-lg hover:border-primary hover:bg-blue-50 transition-all duration-300 flex items-center justify-center min-h-[200px] cursor-pointer"
                    id="addCategoryCard">
                    <div class="text-center">
                        <i class="fas fa-plus-circle text-3xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">Add Course</p>
                    </div>
                </div>
            </a>
        </div>


    </main>

    <?php include $this->resolve("partials/_admin_footer.php"); ?>