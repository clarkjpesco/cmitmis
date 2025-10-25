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
                    <h2 class="text-lg font-semibold text-gray-800">Add New Student</h2>
                    <p class="text-sm text-gray-500">Register a new student by entering their basic information and course details</p>
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
    <!-- Add Student Form -->
    <main class="p-6">
        <div class="max-w-4xl mx-auto">
            <form method="POST" id="addStudentForm" class="space-y-8">
                <?php include $this->resolve("partials/_csrf.php"); ?>
                <!-- Account Information -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <i class="fas fa-user-circle mr-2 text-primary"></i>
                            Account Information
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Login credentials and basic account details</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                Username <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="username" name="username" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="Enter username">
                            <p class="text-xs text-gray-500 mt-1">Must be unique, 3-50 characters</p>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="password" name="password" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent pr-10"
                                    placeholder="Enter password">
                                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-eye text-gray-400" id="passwordToggle"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Minimum 6 characters</p>
                        </div>

                        <div class="md:col-span-2">
                            <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="full_name" name="full_name" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="Enter student's full name">
                        </div>
                    </div>
                </div>

                <!-- Student Details -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <i class="fas fa-id-card mr-2 text-primary"></i>
                            Student Details
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Academic and personal information</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="student_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Student Number <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="student_number" name="student_number" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="e.g., 2025-001">
                            <p class="text-xs text-gray-500 mt-1">Must be unique identifier</p>
                        </div>

                        <div>
                            <label for="course" class="block text-sm font-medium text-gray-700 mb-2">
                                Course <span class="text-red-500">*</span>
                            </label>
                            <select id="course" name="course" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Select Course</option>
                                <?php foreach ($courses as $course) : ?>
                                    <option value="<?php echo e($course['id']); ?>"
                                        <?= (($oldFormData['course'] ?? '') === $course['id']) ? 'selected' : '' ?>>
                                        <?php echo e($course['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (array_key_exists('course', $errors)) : ?>
                                <div class="error-message">
                                    <?php echo e($errors['course'][0]); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div>
                            <label for="year_level" class="block text-sm font-medium text-gray-700 mb-2">
                                Year Level <span class="text-red-500">*</span>
                            </label>
                            <select id="year_level" name="year_level" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Select Year Level</option>
                                <option value="1">1st Year</option>
                                <option value="2">2nd Year</option>
                                <option value="3">3rd Year</option>
                                <option value="4">4th Year</option>

                            </select>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address
                            </label>
                            <input type="email" id="email" name="email"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="student@example.com">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Phone Number
                            </label>
                            <input type="tel" id="phone" name="phone"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="+63 9XX XXX XXXX">
                        </div>

                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                                Gender
                            </label>
                            <select id="gender" name="gender"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                                <option value="Prefer not to say">Prefer not to say</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                Address
                            </label>
                            <textarea id="address" name="address" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="Enter complete address"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <i class="fas fa-phone mr-2 text-primary"></i>
                            Emergency Contact
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Contact person in case of emergency</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="emergency_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Contact Name
                            </label>
                            <input type="text" id="emergency_name" name="emergency_name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="Full name of emergency contact">
                        </div>

                        <div>
                            <label for="emergency_relationship" class="block text-sm font-medium text-gray-700 mb-2">
                                Relationship
                            </label>
                            <select id="emergency_relationship" name="emergency_relationship"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Select Relationship</option>
                                <option value="Parent">Parent</option>
                                <option value="Guardian">Guardian</option>
                                <option value="Sibling">Sibling</option>
                                <option value="Spouse">Spouse</option>
                                <option value="Relative">Relative</option>
                                <option value="Friend">Friend</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div>
                            <label for="emergency_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Contact Number
                            </label>
                            <input type="tel" id="emergency_phone" name="emergency_phone"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="+63 9XX XXX XXXX">
                        </div>

                        <div>
                            <label for="emergency_email" class="block text-sm font-medium text-gray-700 mb-2">
                                Contact Email
                            </label>
                            <input type="email" id="emergency_email" name="emergency_email"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="contact@example.com">
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Fields marked with <span class="text-red-500">*</span> are required
                        </div>

                        <div class="flex space-x-4">
                            <button type="button" onclick="goBack()"
                                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </button>

                            <button type="button" onclick="saveAsDraft()"
                                class="px-6 py-2 border border-primary text-primary rounded-lg hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-primary">
                                <i class="fas fa-save mr-2"></i>Save as Draft
                            </button>

                            <button type="submit"
                                class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-primary">
                                <i class="fas fa-user-plus mr-2"></i>Add Student
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <?php include $this->resolve("partials/_admin_footer.php"); ?>