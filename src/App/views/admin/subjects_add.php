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
                    <h2 class="text-lg font-semibold text-gray-800">Add New Subject</h2>
                    <p class="text-sm text-gray-500">Create and save a new subject with its code, name, and related course details</p>
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
    <!-- Add Subject Form -->
    <main class="p-6">
        <div class="max-w-4xl mx-auto">
            <form method="POST" id="addSubjectForm" class="space-y-8">
                <?php include $this->resolve("partials/_csrf.php"); ?>
                <!-- Subject Basic Information -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <i class="fas fa-book mr-2 text-primary"></i>
                            Subject Information
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Basic subject details and identification</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="subject_code" class="block text-sm font-medium text-gray-700 mb-2">
                                Subject Code <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="subject_code" name="subject_code" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent uppercase"
                                placeholder="e.g., CS101"
                                maxlength="20">
                            <p class="text-xs text-gray-500 mt-1">Must be unique, max 20 characters</p>
                        </div>

                        <div>
                            <label for="units" class="block text-sm font-medium text-gray-700 mb-2">
                                Units <span class="text-red-500">*</span>
                            </label>
                            <select id="units" name="units" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Select Units</option>
                                <option value="1">1 Unit</option>
                                <option value="2">2 Units</option>
                                <option value="3">3 Units</option>
                                <option value="4">4 Units</option>
                                <option value="5">5 Units</option>
                                <option value="6">6 Units</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label for="subject_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Subject Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="subject_name" name="subject_name" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="e.g., Introduction to Programming"
                                maxlength="100">
                        </div>
                    </div>
                </div>

                <!-- Subject Details -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <i class="fas fa-info-circle mr-2 text-primary"></i>
                            Additional Details
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Subject categorization and description</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700 mb-2">
                                Department
                            </label>
                            <select id="department" name="department"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Select Department</option>
                                <option value="Computer Science">Computer Science</option>
                                <option value="Business Administration">Business Administration</option>
                                <option value="Engineering">Engineering</option>
                                <option value="Nursing">Nursing</option>
                                <option value="Education">Education</option>
                                <option value="Mathematics">Mathematics</option>
                                <option value="English">English</option>
                                <option value="Physical Education">Physical Education</option>
                                <option value="Arts and Sciences">Arts and Sciences</option>
                                <option value="Psychology">Psychology</option>
                            </select>
                        </div>

                        <div>
                            <label for="subject_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Subject Type
                            </label>
                            <select id="subject_type" name="subject_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Select Type</option>
                                <option value="Core">Core Subject</option>
                                <option value="Major">Major Subject</option>
                                <option value="Minor">Minor Subject</option>
                                <option value="Elective">Elective</option>
                                <option value="Practicum">Practicum</option>
                                <option value="Thesis">Thesis</option>
                                <option value="Laboratory">Laboratory</option>
                            </select>
                        </div>

                        <div>
                            <label for="year_level" class="block text-sm font-medium text-gray-700 mb-2">
                                Recommended Year Level
                            </label>
                            <select id="year_level" name="year_level"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Any Year Level</option>
                                <option value="1">1st Year</option>
                                <option value="2">2nd Year</option>
                                <option value="3">3rd Year</option>
                                <option value="4">4th Year</option>
                            </select>
                        </div>

                        <div>
                            <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">
                                Typical Semester
                            </label>
                            <select id="semester" name="semester"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Any Semester</option>
                                <option value="1st">1st Semester</option>
                                <option value="2nd">2nd Semester</option>
                                <option value="summer">Summer</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Subject Description
                            </label>
                            <textarea id="description" name="description" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="Provide a brief description of the subject content and objectives..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Prerequisites -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <i class="fas fa-link mr-2 text-primary"></i>
                            Prerequisites & Requirements
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Subject requirements and prerequisites</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="prerequisites" class="block text-sm font-medium text-gray-700 mb-2">
                                Prerequisites
                            </label>
                            <textarea id="prerequisites" name="prerequisites" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="List prerequisite subjects or requirements..."></textarea>
                            <p class="text-xs text-gray-500 mt-1">One prerequisite per line or separated by commas</p>
                        </div>

                        <div>
                            <label for="corequisites" class="block text-sm font-medium text-gray-700 mb-2">
                                Co-requisites
                            </label>
                            <textarea id="corequisites" name="corequisites" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="List subjects that must be taken simultaneously..."></textarea>
                        </div>

                        <div>
                            <label for="lab_hours" class="block text-sm font-medium text-gray-700 mb-2">
                                Laboratory Hours
                            </label>
                            <input type="number" id="lab_hours" name="lab_hours" min="0" max="10"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="0">
                        </div>

                        <div>
                            <label for="lecture_hours" class="block text-sm font-medium text-gray-700 mb-2">
                                Lecture Hours
                            </label>
                            <input type="number" id="lecture_hours" name="lecture_hours" min="0" max="10"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="3">
                        </div>
                    </div>
                </div>

                <!-- Status and Settings -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <i class="fas fa-cog mr-2 text-primary"></i>
                            Status & Settings
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Subject availability and status settings</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status
                            </label>
                            <select id="status" name="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="review">Under Review</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>

                        <div>
                            <label for="max_students" class="block text-sm font-medium text-gray-700 mb-2">
                                Maximum Students per Class
                            </label>
                            <input type="number" id="max_students" name="max_students" min="1" max="100"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="40">
                        </div>

                        <div class="md:col-span-2">
                            <div class="flex items-center space-x-6">
                                <label class="flex items-center">
                                    <input type="checkbox" id="has_laboratory" name="has_laboratory" class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary">
                                    <span class="ml-2 text-sm text-gray-700">Has Laboratory Component</span>
                                </label>

                                <label class="flex items-center">
                                    <input type="checkbox" id="requires_approval" name="requires_approval" class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary">
                                    <span class="ml-2 text-sm text-gray-700">Requires Enrollment Approval</span>
                                </label>

                                <label class="flex items-center">
                                    <input type="checkbox" id="is_repeatable" name="is_repeatable" class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary">
                                    <span class="ml-2 text-sm text-gray-700">Repeatable Subject</span>
                                </label>
                            </div>
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
                                <i class="fas fa-book-medical mr-2"></i>Add Subject
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <?php include $this->resolve("partials/_admin_footer.php"); ?>