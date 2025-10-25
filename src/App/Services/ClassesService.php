<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use InvalidArgumentException;
use Exception;

class ClassesService
{
    public function __construct(private Database $db) {}

    /**
     * Create a new class schedule
     */
    public function createSchedule(array $data): array
    {
        // Validate required fields
        $this->validateScheduleData($data);

        // Check if subject exists
        $subject = $this->getSubjectById((int)$data['subject_id']);
        if (!$subject) {
            throw new InvalidArgumentException('Selected subject does not exist');
        }

        // Check for schedule conflicts
        $conflicts = $this->checkScheduleConflicts(
            $data['days'],
            $data['start_time'],
            $data['end_time'],
            $data['room'],
            $data['semester'],
            $data['school_year']
        );

        if (!empty($conflicts)) {
            throw new InvalidArgumentException('Schedule conflict detected: ' . implode(', ', $conflicts));
        }

        // Begin transaction
        $this->db->beginTransaction();

        try {
            // Format data for insertion
            $daysString = implode(', ', array_map('ucfirst', $data['days']));
            $timeString = $this->formatTimeRange($data['start_time'], $data['end_time']);

            // Insert the schedule
            $this->db->query(
                "INSERT INTO schedules (subject_id, semester, school_year, day, time, room, capacity, instructor, notes, created_at) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())",
                [
                    $data['subject_id'],
                    $data['semester'],
                    $data['school_year'],
                    $daysString,
                    $timeString,
                    $data['room'],
                    $data['capacity'] ?? null,
                    $data['instructor'] ?? null,
                    $data['notes'] ?? null
                ]
            );

            $scheduleId = $this->db->id();

            // Commit transaction
            $this->db->commit();

            // Return the created schedule with subject info
            return $this->getScheduleById((int)$scheduleId);
        } catch (Exception $e) {
            // Rollback transaction on error
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    /**
     * Get all schedules with subject information
     */
    public function getAllSchedules(array $filters = []): array
    {
        $where = [];
        $params = [];

        // Build WHERE clause based on filters
        if (!empty($filters['semester'])) {
            $where[] = 's.semester = ?';
            $params[] = $filters['semester'];
        }

        if (!empty($filters['school_year'])) {
            $where[] = 's.school_year = ?';
            $params[] = $filters['school_year'];
        }

        if (!empty($filters['day'])) {
            $where[] = 's.day LIKE ?';
            $params[] = '%' . $filters['day'] . '%';
        }

        if (!empty($filters['subject_id'])) {
            $where[] = 's.subject_id = ?';
            $params[] = $filters['subject_id'];
        }

        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        $query = "
            SELECT s.*, sub.code, sub.name as subject_name, sub.units,
                   COALESCE(enrollment_count.count, 0) as enrolled_count
            FROM schedules s 
            JOIN subjects sub ON s.subject_id = sub.id 
            LEFT JOIN (
                SELECT schedule_id, COUNT(*) as count 
                FROM enrollments 
                GROUP BY schedule_id
            ) enrollment_count ON s.id = enrollment_count.schedule_id
            {$whereClause}
            ORDER BY s.school_year DESC, s.semester, sub.code
        ";

        return $this->db->query($query, $params)->findAll();
    }

    /**
     * Get schedules with pagination (matching your style)
     */
    public function getSchedulesPaginated(int $length, int $offset, array $filters = []): array
    {
        $where = [];
        $params = [];

        // Build WHERE clause based on filters
        if (!empty($filters['semester'])) {
            $where[] = 's.semester = ?';
            $params[] = $filters['semester'];
        }

        if (!empty($filters['school_year'])) {
            $where[] = 's.school_year = ?';
            $params[] = $filters['school_year'];
        }

        if (!empty($filters['day'])) {
            $where[] = 's.day LIKE ?';
            $params[] = '%' . $filters['day'] . '%';
        }

        if (!empty($filters['subject_id'])) {
            $where[] = 's.subject_id = ?';
            $params[] = $filters['subject_id'];
        }

        if (!empty($filters['search'])) {
            $where[] = '(sub.code LIKE ? OR sub.name LIKE ? OR s.room LIKE ? OR s.instructor LIKE ?)';
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';



        // Get paginated results
        $query = "
            SELECT s.*, sub.code, sub.name as subject_name, sub.units,
                   COALESCE(enrollment_count.count, 0) as enrolled_count
            FROM schedules s 
            JOIN subjects sub ON s.subject_id = sub.id 
            LEFT JOIN (
                SELECT schedule_id, COUNT(*) as count 
                FROM enrollments 
                GROUP BY schedule_id
            ) enrollment_count ON s.id = enrollment_count.schedule_id
            {$whereClause}
            ORDER BY s.school_year DESC, s.semester, sub.code
            LIMIT {$length} OFFSET {$offset}
        ";

        // $params[] = $length;
        // $params[] = $offset;

        $schedules = $this->db->query($query, $params)->findAll();

        // Get total count
        $countQuery = "
            SELECT COUNT(*)
            FROM schedules s 
            JOIN subjects sub ON s.subject_id = sub.id 
            {$whereClause}
        ";
        $count = $this->db->query($countQuery, $params)->count();

        return [$schedules, $count];
    }


    /**
     * Get schedule by ID
     */
    public function getScheduleById(int $id): array
    {
        $schedule = $this->db->query(
            "SELECT s.*, sub.code, sub.name as subject_name, sub.units 
             FROM schedules s 
             JOIN subjects sub ON s.subject_id = sub.id 
             WHERE s.id = ?",
            [$id]
        )->find();

        if (!$schedule) {
            throw new InvalidArgumentException('Schedule not found');
        }

        return $schedule;
    }


    /**
     * Update a schedule
     */
    public function updateSchedule(int $id, array $data): array
    {
        // Validate the schedule exists
        $existingSchedule = $this->getScheduleById($id);

        // Validate the update data
        $this->validateScheduleData($data);

        // Check for conflicts (excluding current schedule)
        $conflicts = $this->checkScheduleConflicts(
            $data['days'],
            $data['start_time'],
            $data['end_time'],
            $data['room'],
            $data['semester'],
            $data['school_year'],
            $id // Exclude current schedule from conflict check
        );

        if (!empty($conflicts)) {
            throw new InvalidArgumentException('Schedule conflict detected: ' . implode(', ', $conflicts));
        }

        // Format data for update
        $daysString = implode(', ', array_map('ucfirst', $data['days']));
        $timeString = $this->formatTimeRange($data['start_time'], $data['end_time']);

        $this->db->query(
            "UPDATE schedules 
             SET subject_id = ?, semester = ?, school_year = ?, day = ?, time = ?, 
                 room = ?, capacity = ?, instructor = ?, notes = ?
             WHERE id = ?",
            [
                $data['subject_id'],
                $data['semester'],
                $data['school_year'],
                $daysString,
                $timeString,
                $data['room'],
                $data['capacity'] ?? null,
                $data['instructor'] ?? null,
                $data['notes'] ?? null,
                $id
            ]
        );

        return $this->getScheduleById($id);
    }

    /**
     * Delete a schedule
     */
    public function deleteSchedule(int $id): bool
    {
        // Check if there are any enrollments
        $enrollmentCount = $this->db->query(
            "SELECT COUNT(*) FROM enrollments WHERE schedule_id = ?",
            [$id]
        )->count();

        if ($enrollmentCount > 0) {
            throw new InvalidArgumentException('Cannot delete schedule with existing enrollments');
        }

        $this->db->query("DELETE FROM schedules WHERE id = ?", [$id]);

        return true;
    }

    /**
     * Get all subjects
     */
    public function getAllSubjects(): array
    {
        return $this->db->query("SELECT id, code, name, units FROM subjects ORDER BY code")->findAll();
    }

    /**
     * Get subject by ID
     */
    public function getSubjectById(int $id): ?array
    {
        return $this->db->query("SELECT * FROM subjects WHERE id = ?", [$id])->find() ?: null;
    }

    /**
     * Check for schedule conflicts
     */
    private function checkScheduleConflicts(array $days, string $startTime, string $endTime, string $room, string $semester, string $schoolYear, ?int $excludeId = null): array
    {
        $conflicts = [];
        $daysToCheck = array_map('ucfirst', $days);

        // Build query to check existing schedules
        $query = "
            SELECT s.*, sub.code, sub.name 
            FROM schedules s 
            JOIN subjects sub ON s.subject_id = sub.id 
            WHERE s.room = ? AND s.semester = ? AND s.school_year = ?
        ";
        $params = [$room, $semester, $schoolYear];

        // Exclude current schedule if updating
        if ($excludeId !== null) {
            $query .= " AND s.id != ?";
            $params[] = $excludeId;
        }

        $existingSchedules = $this->db->query($query, $params)->findAll();

        foreach ($existingSchedules as $schedule) {
            // Check if any day overlaps
            $existingDays = array_map('trim', explode(',', $schedule['day']));
            $dayOverlap = array_intersect($daysToCheck, $existingDays);

            if (!empty($dayOverlap)) {
                // Parse existing time range
                $existingTimeRange = $this->parseTimeRange($schedule['time']);
                if ($existingTimeRange) {
                    // Check time overlap
                    if ($this->timesOverlap($startTime, $endTime, $existingTimeRange['start'], $existingTimeRange['end'])) {
                        $conflicts[] = "Room {$room} is already booked for {$schedule['code']} - {$schedule['name']} on " . implode(', ', $dayOverlap);
                    }
                }
            }
        }

        return $conflicts;
    }

    /**
     * Validate schedule data
     */
    private function validateScheduleData(array $data): void
    {
        $requiredFields = ['subject_id', 'semester', 'school_year', 'days', 'start_time', 'end_time', 'room'];
        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
            }
        }

        // Validate days array
        if (isset($data['days']) && !is_array($data['days'])) {
            $errors[] = 'At least one day must be selected';
        }

        // Validate time format and logic
        if (!empty($data['start_time']) && !empty($data['end_time'])) {
            $startTime = strtotime($data['start_time']);
            $endTime = strtotime($data['end_time']);

            if ($startTime >= $endTime) {
                $errors[] = 'End time must be after start time';
            }
        }

        // Validate capacity
        if (isset($data['capacity']) && $data['capacity'] !== null && $data['capacity'] !== '') {
            if (!is_numeric($data['capacity']) || $data['capacity'] < 1) {
                $errors[] = 'Capacity must be a positive number';
            }
        }

        // Validate semester
        $validSemesters = ['1st', '2nd', 'summer'];
        if (!empty($data['semester']) && !in_array($data['semester'], $validSemesters)) {
            $errors[] = 'Invalid semester selected';
        }

        if (!empty($errors)) {
            throw new InvalidArgumentException(implode(', ', $errors));
        }
    }

    /**
     * Check if two time ranges overlap
     */
    private function timesOverlap(string $start1, string $end1, string $start2, string $end2): bool
    {
        $start1 = strtotime($start1);
        $end1 = strtotime($end1);
        $start2 = strtotime($start2);
        $end2 = strtotime($end2);

        return ($start1 < $end2) && ($end1 > $start2);
    }

    /**
     * Format time range for display/storage
     */
    private function formatTimeRange(string $startTime, string $endTime): string
    {
        $start = date('g:i A', strtotime($startTime));
        $end = date('g:i A', strtotime($endTime));
        return "{$start} - {$end}";
    }

    /**
     * Parse time range string back to start and end times
     */
    private function parseTimeRange(string $timeString): ?array
    {
        if (!preg_match('/(\d{1,2}:\d{2}\s*(AM|PM))\s*-\s*(\d{1,2}:\d{2}\s*(AM|PM))/i', $timeString, $matches)) {
            return null;
        }

        return [
            'start' => $matches[1],
            'end' => $matches[3]
        ];
    }

    /**
     * Get schedule statistics
     */
    public function getScheduleStatistics(): array
    {
        $stats = [];

        // Total schedules
        $stats['total_schedules'] = $this->db->query("SELECT COUNT(*) FROM schedules")->count();

        // Schedules by semester
        $stats['by_semester'] = $this->db->query(
            "SELECT semester, COUNT(*) as count FROM schedules GROUP BY semester ORDER BY semester"
        )->findAll();

        // Room utilization
        $stats['room_utilization'] = $this->db->query(
            "SELECT room, COUNT(*) as schedule_count FROM schedules GROUP BY room ORDER BY schedule_count DESC LIMIT 10"
        )->findAll();

        // Popular subjects
        $stats['popular_subjects'] = $this->db->query(
            "SELECT sub.code, sub.name, COUNT(s.id) as schedule_count
             FROM subjects sub
             LEFT JOIN schedules s ON sub.id = s.subject_id
             GROUP BY sub.id, sub.code, sub.name
             ORDER BY schedule_count DESC
             LIMIT 10"
        )->findAll();

        return $stats;
    }


    // enrollments

    public function scheduleInfo(int $scheduleId)
    {
        $scheduleInfo = $this->db->query(
            "SELECT s.capacity, 
                COUNT(e.id) as enrolled_count,
                sub.name as subject_name,
                sub.code as subject_code
         FROM schedules s
         LEFT JOIN subjects sub ON s.subject_id = sub.id
         LEFT JOIN enrollments e ON s.id = e.schedule_id
         WHERE s.id = :schedule_id
         GROUP BY s.id",
            ['schedule_id' => $scheduleId]
        )->find();

        return $scheduleInfo;
    }

    public function getAvailableSchedules($subjectId, $semester, $schoolYear)
    {

        $schedules = $this->db->query(
            "SELECT s.id, s.day, s.time, s.room, s.capacity, s.instructor,
                COUNT(e.id) as enrolled_count,
                sub.code, sub.name, sub.units
         FROM schedules s
         JOIN subjects sub ON s.subject_id = sub.id
         LEFT JOIN enrollments e ON s.id = e.schedule_id
         WHERE s.subject_id = :subject_id
         AND s.semester = :semester
         AND s.school_year = :school_year
         GROUP BY s.id
         ORDER BY s.day, s.time",
            [
                'subject_id' => $subjectId,
                'semester' => $semester,
                'school_year' => $schoolYear
            ]
        )->findAll();

        return $schedules;
    }

    public function getCurrentSemester()
    {
        $currentSemester = $this->db->query(
            "SELECT semester, school_year
         FROM schedules
         ORDER BY created_at DESC
         LIMIT 1"
        )->find();

        return $currentSemester;
    }

    public function getAllSchoolYear()
    {
        $schoolYears = $this->db->query(
            "SELECT DISTINCT school_year
         FROM schedules
         ORDER BY created_at DESC"
        )->findAll();

        return $schoolYears;
    }

    public function getAvailableSemesters($studentId)
    {
        $semesters = $this->db->query(
            "SELECT DISTINCT semester, school_year
         FROM schedules sc
         JOIN enrollments e ON sc.id = e.schedule_id
         WHERE e.student_id = :student_id
         ORDER BY school_year DESC, semester DESC",
            ['student_id' => $studentId]
        )->findAll();

        return $semesters;
    }
}
