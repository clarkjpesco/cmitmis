<?php include $this->resolve("partials/_admin_header.php"); ?>

<div class="flex-1 overflow-auto">
    <?php include $this->resolve("partials/_csrf.php"); ?>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">Import Students</h1>
                    <p class="mt-1 text-sm text-slate-600">Processing your student data import</p>
                </div>
                <a href="/admin/students" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-400 transition-all duration-200 shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Students
                </a>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2 animate-spin" id="header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Import Progress
                    </h2>
                </div>

                <div class="p-6 sm:p-8">
                    <!-- Progress Bar Section -->
                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-3">
                            <span id="progress-status" class="text-sm font-medium text-slate-700">Initializing...</span>
                            <span id="progress-percentage" class="text-sm font-semibold text-blue-600">0%</span>
                        </div>
                        <div class="relative h-6 bg-slate-200 rounded-full overflow-hidden shadow-inner">
                            <div id="progress-bar"
                                class="absolute top-0 left-0 h-full bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full transition-all duration-500 ease-out flex items-center justify-end"
                                style="width: 0%">
                                <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                        <!-- Processed -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-5 border border-blue-200 transform hover:scale-105 transition-transform duration-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-2xl font-bold text-blue-700" id="processed-count">0</p>
                                    <p class="text-xs font-medium text-blue-600 mt-1">Processed</p>
                                </div>
                                <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl p-5 border border-slate-200 transform hover:scale-105 transition-transform duration-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-2xl font-bold text-slate-700" id="total-count">0</p>
                                    <p class="text-xs font-medium text-slate-600 mt-1">Total Rows</p>
                                </div>
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Imported -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-5 border border-green-200 transform hover:scale-105 transition-transform duration-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-2xl font-bold text-green-700" id="imported-count">0</p>
                                    <p class="text-xs font-medium text-green-600 mt-1">Imported</p>
                                </div>
                                <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Errors -->
                        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-5 border border-red-200 transform hover:scale-105 transition-transform duration-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-2xl font-bold text-red-700" id="errors-count">0</p>
                                    <p class="text-xs font-medium text-red-600 mt-1">Errors</p>
                                </div>
                                <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Current Activity -->
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-slate-700 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Current Activity
                        </h3>
                        <div id="current-activity" class="bg-blue-50 border-l-4 border-blue-500 rounded-r-lg p-4 text-sm text-blue-800">
                            Starting import process...
                        </div>
                    </div>

                    <!-- Errors Display -->
                    <div id="errors-container" class="mb-6 hidden">
                        <h3 class="text-sm font-semibold text-slate-700 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Errors Encountered
                        </h3>
                        <div id="errors-list" class="bg-red-50 border-l-4 border-red-500 rounded-r-lg p-4 max-h-48 overflow-y-auto space-y-2 text-sm text-red-800">
                        </div>
                    </div>

                    <!-- Results Section -->
                    <div id="results-section" class="hidden">
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6 mb-6">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-green-900">Import Completed Successfully!</h3>
                                    <p id="completion-message" class="mt-2 text-sm text-green-800"></p>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <a href="/admin/students" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                View Students
                            </a>
                            <a href="/admin/students/import" class="inline-flex items-center justify-center px-6 py-3 bg-white border-2 border-blue-600 text-blue-600 font-medium rounded-lg hover:bg-blue-50 transition-all duration-200 shadow-md hover:shadow-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Import Another File
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        <?php include $this->resolve("partials/_csrf_helpers.js"); ?>

        const importId = '<?php echo $import_id ?? ''; ?>';

        console.log(importId);
        let progressInterval;
        let importStarted = false;



        async function startImport() {
            if (importStarted) return;
            importStarted = true;

            try {
                const response = await csrfManager.csrfFetch(`/admin/students/import/start`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        import_id: importId
                    })
                });

                const data = await response.json();
                console.log('Import started:', data);

            } catch (error) {
                console.error('Error starting import:', error);
                const activityElement = document.getElementById('current-activity');
                activityElement.textContent = 'Failed to start import';
                activityElement.classList.remove('bg-blue-50', 'border-blue-500', 'text-blue-800');
                activityElement.classList.add('bg-red-50', 'border-red-500', 'text-red-800');
            }
        }

        async function updateProgress() {
            try {
                const response = await csrfManager.csrfFetch(`/admin/students/import/progress?import_id=${importId}`);
                const data = await response.json();

                const progressBar = document.getElementById('progress-bar');
                const progressPercentage = document.getElementById('progress-percentage');

                progressBar.style.width = data.percentage + '%';
                progressPercentage.textContent = data.percentage + '%';

                document.getElementById('processed-count').textContent = data.processed || 0;
                document.getElementById('total-count').textContent = data.total || 0;
                document.getElementById('imported-count').textContent = data.imported || 0;
                document.getElementById('errors-count').textContent = data.errors ? data.errors.length : 0;

                if (data.total > 0) {
                    document.getElementById('progress-status').textContent =
                        `Processing... (Row ${data.current_row || data.processed} of ${data.total})`;
                }

                const currentActivity = document.getElementById('current-activity');
                if (data.total > 0) {
                    currentActivity.textContent = `Processing row ${data.current_row || data.processed} of ${data.total}`;
                }

                const errorsContainer = document.getElementById('errors-container');
                const errorsList = document.getElementById('errors-list');

                if (data.errors && data.errors.length > 0) {
                    errorsContainer.classList.remove('hidden');
                    errorsList.innerHTML = data.errors.map(error =>
                        `<div class="flex items-start">
                    <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span>${error}</span>
                </div>`
                    ).join('');
                }

                if (data.status === 'completed') {
                    clearInterval(progressInterval);

                    const pulseElement = progressBar.querySelector('.animate-pulse');
                    if (pulseElement) {
                        pulseElement.remove();
                    }

                    progressBar.classList.add('bg-gradient-to-r', 'from-green-500', 'to-emerald-600');
                    progressBar.classList.remove('from-blue-500', 'to-indigo-600');

                    document.getElementById('progress-status').textContent = 'Import Completed!';

                    const headerIcon = document.getElementById('header-icon');
                    headerIcon.classList.remove('animate-spin');
                    headerIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';

                    const resultsSection = document.getElementById('results-section');
                    const completionMessage = document.getElementById('completion-message');

                    resultsSection.classList.remove('hidden');
                    completionMessage.textContent =
                        `Successfully imported ${data.imported} out of ${data.total} students.`;

                    if (data.errors && data.errors.length > 0) {
                        completionMessage.innerHTML +=
                            `<br><strong class="text-red-700">${data.errors.length} errors occurred during import.</strong>`;
                    }

                } else if (data.status === 'error') {
                    clearInterval(progressInterval);

                    const pulseElement = progressBar.querySelector('.animate-pulse');
                    if (pulseElement) {
                        pulseElement.remove();
                    }

                    progressBar.classList.add('bg-gradient-to-r', 'from-red-500', 'to-red-600');
                    progressBar.classList.remove('from-blue-500', 'to-indigo-600');

                    document.getElementById('progress-status').textContent = 'Import Failed!';

                    const headerIcon = document.getElementById('header-icon');
                    headerIcon.classList.remove('animate-spin');

                    currentActivity.textContent = 'An error occurred during import';
                    currentActivity.classList.remove('bg-blue-50', 'border-blue-500', 'text-blue-800');
                    currentActivity.classList.add('bg-red-50', 'border-red-500', 'text-red-800');
                }

            } catch (error) {
                console.error('Error fetching progress:', error);
            }
        }

        // Check if import ID exists
        if (importId) {
            // Start polling immediately
            progressInterval = setInterval(updateProgress, 500); // Poll every 0.5 seconds

            // Start the import process
            startImport();

            // Initial progress check
            updateProgress();
        } else {
            document.getElementById('current-activity').textContent = 'Error: No import ID found';
            document.getElementById('current-activity').classList.remove('bg-blue-50', 'border-blue-500', 'text-blue-800');
            document.getElementById('current-activity').classList.add('bg-red-50', 'border-red-500', 'text-red-800');
        }
    </script>

    <?php include $this->resolve("partials/_admin_footer.php"); ?>