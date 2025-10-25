<?php include $this->resolve("partials/_admin_header.php"); ?>
<div class="flex-1 overflow-auto">
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Import Students</h1>
                    <p class="text-gray-600">Upload an Excel file to bulk import student records</p>
                </div>
                <a href="/admin/students" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 hover:shadow-md transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Students
                </a>
            </div>

            <!-- Success/Error Messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 rounded-lg p-4 shadow-sm animate-fade-in">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-green-800"><?= $_SESSION['success'] ?></p>
                        </div>
                        <button type="button" class="ml-3 text-green-500 hover:text-green-700" onclick="this.parentElement.parentElement.remove()">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-lg p-4 shadow-sm animate-fade-in">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-red-800"><?= $_SESSION['error'] ?></p>
                        </div>
                        <button type="button" class="ml-3 text-red-500 hover:text-red-700" onclick="this.parentElement.parentElement.remove()">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['import_errors']) && !empty($_SESSION['import_errors'])): ?>
                <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-4 shadow-sm animate-fade-in">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <div class="flex-1">
                            <h5 class="text-sm font-semibold text-yellow-800 mb-2">Import completed with errors:</h5>
                            <ul class="space-y-1">
                                <?php foreach ($_SESSION['import_errors'] as $error): ?>
                                    <li class="text-sm text-yellow-700 flex items-start">
                                        <span class="inline-block w-1.5 h-1.5 bg-yellow-500 rounded-full mr-2 mt-1.5 flex-shrink-0"></span>
                                        <span><?= $error ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <button type="button" class="ml-3 text-yellow-500 hover:text-yellow-700" onclick="this.parentElement.parentElement.remove()">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
                <?php unset($_SESSION['import_errors']); ?>
            <?php endif; ?>

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Upload Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                            <h5 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Upload Excel File
                            </h5>
                        </div>
                        <div class="p-6">
                            <form action="/admin/students/import" method="post" enctype="multipart/form-data">
                                <?php include $this->resolve("partials/_csrf.php"); ?>
                                <div class="mb-6">
                                    <label for="excel_file" class="block text-sm font-medium text-gray-700 mb-2">
                                        Select Excel File
                                    </label>
                                    <div class="relative">
                                        <input
                                            type="file"
                                            class="block w-full text-sm text-gray-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 file:cursor-pointer border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                            id="excel_file"
                                            name="excel_file"
                                            accept=".xlsx, .xls"
                                            required>
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                        Supported formats: .xlsx, .xls (Max: 10MB)
                                    </p>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-3">
                                    <button type="submit" class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-md hover:shadow-lg transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                        Import Students
                                    </button>
                                    <a href="/admin/students" class="inline-flex items-center justify-center px-6 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- File Format Card -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                            <h5 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                File Format
                            </h5>
                        </div>
                        <div class="p-6">
                            <p class="text-sm text-gray-600 mb-4">Your Excel file must have these columns in the first row:</p>

                            <div class="overflow-hidden rounded-lg border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Column</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Description</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">ID</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">Student Number <span class="text-red-500">*</span></td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">LName</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">Last Name <span class="text-red-500">*</span></td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">FName</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">First Name <span class="text-red-500">*</span></td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">Suffix</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">Name Suffix</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">MI</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">Middle Initial</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">Gender</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">Male/Female</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">Course_ID</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">Course ID <span class="text-red-500">*</span></td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">Year_Level</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">Year Level <span class="text-red-500">*</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                <a href="/admin/students/import/template" class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white font-medium rounded-lg hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-sm hover:shadow-md transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Download Template
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Import Notes Card -->
                    <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl shadow-md overflow-hidden border border-amber-200">
                        <div class="p-6">
                            <h6 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                                Import Notes
                            </h6>
                            <ul class="space-y-2.5">
                                <li class="flex items-start text-sm text-gray-700">
                                    <svg class="w-5 h-5 mr-2 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Usernames are set to student ID by default</span>
                                </li>
                                <li class="flex items-start text-sm text-gray-700">
                                    <svg class="w-5 h-5 mr-2 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Passwords are automatically generated</span>
                                </li>
                                <li class="flex items-start text-sm text-gray-700">
                                    <svg class="w-5 h-5 mr-2 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Full names are generated from name components</span>
                                </li>
                                <li class="flex items-start text-sm text-gray-700">
                                    <svg class="w-5 h-5 mr-2 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Duplicate student numbers will be skipped</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>

    <?php include $this->resolve("partials/_admin_footer.php"); ?>