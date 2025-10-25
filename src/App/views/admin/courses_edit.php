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
                    <h2 class="text-lg font-semibold text-gray-800">Update Course</h2>
                    <p class="text-sm text-gray-500">Modify an existing course’s details, such as its code, name, description or status.</p>
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



        <div class="bg-white rounded-lg shadow form-card p-6 mb-6">
            <h3 class="font-semibold text-lg mb-6 text-secondary border-b pb-3">
                Update Course Information
            </h3>

            <form method="POST" class="grid grid-cols-1">
                <?php include $this->resolve("partials/_csrf.php"); ?>

                <div class="space-y-8">
                    <div>
                        <label
                            for="courseName"
                            class="block text-sm font-medium text-gray-700 mb-1">Course Name *</label>
                        <input
                            type="text"
                            id="courseName"
                            name="courseName"
                            value="<?php echo e($course['name'] ?? ''); ?>"
                            required
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="Enter service name" />
                        <?php if (array_key_exists('courseName', $errors)) : ?>
                            <div class="error-message">
                                <?php echo e($errors['courseName'][0]); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label
                            for="courseCode"
                            class="block text-sm font-medium text-gray-700 mb-1">Course Code *</label>
                        <input
                            type="text"
                            id="courseCode"
                            name="courseCode"
                            value="<?php echo e($course['code'] ?? ''); ?>"
                            required
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="Enter service name" />
                        <?php if (array_key_exists('courseCode', $errors)) : ?>
                            <div class="error-message">
                                <?php echo e($errors['courseCode'][0]); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label
                            for="courseDescription"
                            class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea
                            id="courseDescription"
                            name="courseDescription"
                            rows="3"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="Enter course description"><?php echo e($course['description'] ?? ''); ?></textarea>
                        <?php if (array_key_exists('courseDescription', $errors)) : ?>
                            <div class="error-message">
                                <?php echo e($errors['courseDescription'][0]); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="flex items-center">
                        <input type="hidden" name="courseActive" value="0">
                        <input
                            type="checkbox"
                            id="courseActive"
                            name="courseActive"
                            value="1"
                            <?php echo ($course['is_active'] ?? false) == 1 ? 'checked' : ''; ?>
                            class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded" />
                        <label
                            for="courseActive"
                            class="ml-2 block text-sm text-gray-700">Active</label>
                    </div>
                </div>

                <!-- Form Actions -->
                <div
                    class="md:col-span-2 flex justify-end space-x-4 pt-6 border-t mt-6">
                    <a href="/admin/courses"
                        type="button"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                        Cancel
                    </a>
                    <button
                        type="submit"
                        class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 flex items-center">
                        <i class="fas fa-graduation-cap mr-2"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </main>
    <?php include $this->resolve("partials/_admin_footer.php"); ?>