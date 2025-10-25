<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use InvalidArgumentException;
use Exception;

class EnrollmentService
{
    public function __construct(private Database $db) {}


    public function getAllStudentsEnrollments(int $length, int $offset, ?string $searchTerm = null, ?string $course = null, ?string $yearLevel = null)
    {
        $searchTerm = addcslashes($searchTerm ?? '', '%_');

        // Build the WHERE clause dynamically
        $whereConditions = [];
        $params = [];


        if (!empty($searchTerm)) {
            $whereConditions[] = "u.full_name LIKE :full_name OR s.student_number LIKE :student_number OR c.code LIKE :code";
            $params['full_name'] = "%{$searchTerm}%";
            $params['student_number'] = "%{$searchTerm}%";
            $params['code'] = "%{$searchTerm}%";
        }


        if (!empty($course)) {
            $whereConditions[] = "c.code=:code";
            $params['code'] = $course;
        }

        if (!empty($yearLevel)) {
            $whereConditions[] = "s.year_level=:year_level";
            $params['year_level'] = (int)$yearLevel;
        }

        // Combine conditions with AND
        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';


        $students = $this->db->query(
            "SELECT s.id, s.student_number, c.name as course, c.code as course_code, s.year_level, 
                u.full_name,
                COUNT(DISTINCT e.id) as enrollment_count
         FROM students s
         JOIN users u ON s.user_id = u.id
         JOIN courses c ON s.course_id = c.id
         LEFT JOIN enrollments e ON s.id = e.student_id
         LEFT JOIN schedules sc ON e.schedule_id = sc.id
         LEFT JOIN (
             SELECT MAX(CONCAT(school_year, semester)) as current_sem
             FROM schedules
         ) cs ON sc.school_year = SUBSTRING_INDEX(cs.current_sem, 'st', 1) 
             AND sc.semester = SUBSTRING_INDEX(cs.current_sem, 'st', -1)
              {$whereClause} 
         GROUP BY s.id
         ORDER BY u.full_name
    
        LIMIT {$length} OFFSET {$offset}",
            $params
        )->findAll();

        $studentCount = $this->db->query(
            "SELECT COUNT(*)
        FROM students s
         JOIN users u ON s.user_id = u.id
         JOIN courses c ON s.course_id = c.id
         LEFT JOIN enrollments e ON s.id = e.student_id
         LEFT JOIN schedules sc ON e.schedule_id = sc.id
         LEFT JOIN (
             SELECT MAX(CONCAT(school_year, semester)) as current_sem
             FROM schedules
         ) cs ON sc.school_year = SUBSTRING_INDEX(cs.current_sem, 'st', 1) 
             AND sc.semester = SUBSTRING_INDEX(cs.current_sem, 'st', -1)
        {$whereClause}
        --  GROUP BY s.id
        --  ORDER BY u.full_name
        ",
            $params
        )->count();

        return [$students, $studentCount];
    }


    public function existingEnrollment(int $studentId, int $scheduleId)
    {
        // Check if student is already enrolled in this schedule
        $existingEnrollment = $this->db->query(
            "SELECT e.id 
         FROM enrollments e 
         WHERE e.student_id = :student_id 
         AND e.schedule_id = :schedule_id",
            [
                'student_id' => $studentId,
                'schedule_id' => $scheduleId
            ]
        )->find();

        return $existingEnrollment;
    }

    public function scheduleConflicts(int $studentId, int $scheduleId)
    {
        // Check for schedule conflicts
        $conflicts = $this->db->query(
            "SELECT sub.code, sub.name, s.day, s.time
         FROM enrollments e
         JOIN schedules s ON e.schedule_id = s.id
         JOIN subjects sub ON s.subject_id = sub.id
         JOIN schedules s2 ON s2.id = :new_schedule_id
         WHERE e.student_id = :student_id
         AND s.semester = s2.semester
         AND s.school_year = s2.school_year
         AND s.id != :new_schedule_id
         AND s.day = s2.day
         AND s.time = s2.time",
            [
                'student_id' => $studentId,
                'new_schedule_id' => $scheduleId
            ]
        )->findAll();

        return $conflicts;
    }
    public function createEnrollment(int $studentId, int $scheduleId)
    {
        try {
            // Begin transaction
            $this->db->beginTransaction();

            // Insert enrollment
            $this->db->query(
                "INSERT INTO enrollments (student_id, schedule_id) 
             VALUES (:student_id, :schedule_id)",
                [
                    'student_id' => $studentId,
                    'schedule_id' => $scheduleId
                ]
            );

            $enrollmentId = $this->db->id();

            // Create initial grade record (optional)
            $this->db->query(
                "INSERT INTO grades (enrollment_id, grade, remarks) 
             VALUES (:enrollment_id, NULL, 'Enrolled')",
                ['enrollment_id' => $enrollmentId]
            );

            // Commit transaction
            $this->db->commit();

            return [
                'success' => true,
                'message' => 'Student enrolled successfully'
            ];

            // $_SESSION['success'] = 'Student enrolled successfully in ' . ($scheduleInfo['subject_code'] ?? 'the class');
            //redirectTo('/admin/enrollments');
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Error enrolling student status: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to enroll a student to a class'
            ];
            //$_SESSION['error'] = 'Failed to enroll student. Please try again.';
            //redirectTo('/admin/enrollments/create');
        }
    }

    public function studentSchedules($studentId, $semester, $schoolYear)
    {

        $studentSchedules = $this->db->query(
            "SELECT s.day, s.time
             FROM enrollments e
             JOIN schedules s ON e.schedule_id = s.id
             WHERE e.student_id = :student_id
             AND s.semester = :semester
             AND s.school_year = :school_year",
            [
                'student_id' => $studentId,
                'semester' => $semester,
                'school_year' => $schoolYear
            ]
        )->findAll();

        return $studentSchedules;
    }

    public function getCurrentEnrollmentsSemester($studentId, $semester, $schoolYear)
    {
        $enrollments = $this->db->query(
            "SELECT sub.code, sub.name, sub.units, s.day, s.time
             FROM enrollments e
             JOIN schedules s ON e.schedule_id = s.id
             JOIN subjects sub ON s.subject_id = sub.id
             WHERE e.student_id = :student_id
             AND s.semester = :semester
             AND s.school_year = :school_year",
            [
                'student_id' => $studentId,
                'semester' => $semester,
                'school_year' => $schoolYear
            ]
        )->findAll();

        return $enrollments;
    }

    public function getAllEnrollments($studentId)
    {
        $enrollments = $this->db->query(
            "SELECT e.id, sub.code as subject_code, sub.name as subject_name, 
                sub.units, sc.day, sc.time, sc.room, sc.instructor,
                sc.semester, sc.school_year, g.grade, g.remarks
         FROM enrollments e
         JOIN schedules sc ON e.schedule_id = sc.id
         JOIN subjects sub ON sc.subject_id = sub.id
         LEFT JOIN grades g ON e.id = g.enrollment_id
         WHERE e.student_id = :student_id
         ORDER BY sc.school_year DESC, sc.semester DESC, sub.code",
            ['student_id' => $studentId]
        )->findAll();

        return $enrollments;
    }
}
