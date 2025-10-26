<?php include $this->resolve("partials/_student_header.php"); ?>
<main class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">My Schedule</h2>
            <p class="text-gray-600 mt-1">View your class schedule for the current semester</p>
        </div>

        <?php if (empty($schedule)): ?>
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <i class="fas fa-calendar-times text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Schedule Available</h3>
                <p class="text-gray-500">You don't have any enrolled subjects for this semester</p>
            </div>
        <?php else: ?>
            <!-- Schedule Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Day</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instructor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Units</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($schedule as $class): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900"><?php echo e($class['code']); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo e($class['subject_name']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <i class="fas fa-calendar text-gray-400 mr-2"></i>
                                        <?php echo e($class['day']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <i class="fas fa-clock text-gray-400 mr-2"></i>
                                        <?php echo e($class['time']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <i class="fas fa-door-open text-gray-400 mr-2"></i>
                                        <?php echo e($class['room']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <i class="fas fa-user-tie text-gray-400 mr-2"></i>
                                        <?php echo e($class['instructor']); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <?php echo e($class['units']); ?> units
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden divide-y divide-gray-200">
                    <?php foreach ($schedule as $class): ?>
                        <div class="p-4 hover:bg-gray-50 transition-colors schedule-card" data-day="<?php echo e($class['day']); ?>">
                            <div class="mb-3">
                                <div class="text-sm font-bold text-gray-900"><?php echo e($class['code']); ?></div>
                                <div class="text-sm text-gray-600"><?php echo e($class['subject_name']); ?></div>
                            </div>

                            <div class="space-y-2">
                                <div class="flex items-center text-sm text-gray-700">
                                    <i class="fas fa-calendar text-gray-400 mr-2 w-4"></i>
                                    <span class="font-medium text-gray-500 w-20">Day:</span>
                                    <span><?php echo e($class['day']); ?></span>
                                </div>

                                <div class="flex items-center text-sm text-gray-700">
                                    <i class="fas fa-clock text-gray-400 mr-2 w-4"></i>
                                    <span class="font-medium text-gray-500 w-20">Time:</span>
                                    <span><?php echo e($class['time']); ?></span>
                                </div>

                                <div class="flex items-center text-sm text-gray-700">
                                    <i class="fas fa-door-open text-gray-400 mr-2 w-4"></i>
                                    <span class="font-medium text-gray-500 w-20">Room:</span>
                                    <span><?php echo e($class['room']); ?></span>
                                </div>

                                <div class="flex items-center text-sm text-gray-700">
                                    <i class="fas fa-user-tie text-gray-400 mr-2 w-4"></i>
                                    <span class="font-medium text-gray-500 w-20">Instructor:</span>
                                    <span><?php echo e($class['instructor']); ?></span>
                                </div>

                                <div class="flex items-center text-sm text-gray-700">
                                    <span class="font-medium text-gray-500 ml-6 w-20">Units:</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <?php echo e($class['units']); ?> units
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Schedule Summary -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="font-medium text-blue-900 mb-4">
                    <i class="fas fa-info-circle mr-2"></i>Schedule Summary
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white rounded-lg p-4">
                        <div class="text-sm text-gray-600">Total Subjects</div>
                        <div class="text-2xl font-bold text-gray-900"><?php echo count($schedule); ?></div>
                    </div>
                    <div class="bg-white rounded-lg p-4">
                        <div class="text-sm text-gray-600">Total Units</div>
                        <div class="text-2xl font-bold text-gray-900">
                            <?php
                            $totalUnits = 0;
                            foreach ($schedule as $class) {
                                $totalUnits += $class['units'];
                            }
                            echo $totalUnits;
                            ?>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-4">
                        <div class="text-sm text-gray-600">Semester</div>
                        <div class="text-lg font-bold text-gray-900">
                            <?php echo ucfirst($schedule[0]['semester'] ?? 'N/A'); ?> - <?php echo $schedule[0]['school_year'] ?? ''; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Day Filter Buttons -->
            <div class="mt-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Filter by Day:</h4>
                    <div class="flex flex-wrap gap-2">
                        <button onclick="filterByDay('')" class="day-filter-btn active px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-primary text-white">
                            All Days
                        </button>
                        <button onclick="filterByDay('Monday')" class="day-filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-100 text-gray-700 hover:bg-gray-200">
                            Monday
                        </button>
                        <button onclick="filterByDay('Tuesday')" class="day-filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-100 text-gray-700 hover:bg-gray-200">
                            Tuesday
                        </button>
                        <button onclick="filterByDay('Wednesday')" class="day-filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-100 text-gray-700 hover:bg-gray-200">
                            Wednesday
                        </button>
                        <button onclick="filterByDay('Thursday')" class="day-filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-100 text-gray-700 hover:bg-gray-200">
                            Thursday
                        </button>
                        <button onclick="filterByDay('Friday')" class="day-filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-100 text-gray-700 hover:bg-gray-200">
                            Friday
                        </button>
                        <button onclick="filterByDay('Saturday')" class="day-filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-100 text-gray-700 hover:bg-gray-200">
                            Saturday
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
    function filterByDay(day) {
        // Get both desktop rows and mobile cards
        const desktopRows = document.querySelectorAll('tbody tr');
        const mobileCards = document.querySelectorAll('.schedule-card');
        const buttons = document.querySelectorAll('.day-filter-btn');

        // Update button styles
        buttons.forEach(btn => {
            btn.classList.remove('active', 'bg-primary', 'text-white');
            btn.classList.add('bg-gray-100', 'text-gray-700');
        });
        event.target.classList.remove('bg-gray-100', 'text-gray-700');
        event.target.classList.add('active', 'bg-primary', 'text-white');

        // Filter desktop table rows
        desktopRows.forEach(row => {
            const dayCell = row.querySelector('td:nth-child(2)').textContent;
            if (day === '' || dayCell.includes(day)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        // Filter mobile cards using data-day attribute
        mobileCards.forEach(card => {
            const cardDay = card.getAttribute('data-day');
            if (day === '' || cardDay.includes(day)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>

<?php include $this->resolve("partials/_student_footer.php"); ?>