<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;

class StudentPortalService
{
    public function __construct(private Database $db) {}

    /**
     * Get student information by user ID
     */
    public function getStudentByUserId(int $userId): ?array
    {
        $student = $this->db->query(
            "SELECT s.*, u.full_name, u.username, c.name as course
             FROM students s
             JOIN users u ON s.user_id = u.id
             JOIN courses c ON s.course_id = c.id
             WHERE s.user_id = ?",
            [$userId]
        )->find();

        return $student ?: null;
    }

    /**
     * Get student dashboard statistics
     */
    public function getDashboardStats(int $studentId): array
    {
        // Get current semester
        $currentSemester = $this->db->query(
            "SELECT semester, school_year
             FROM schedules
             ORDER BY created_at DESC
             LIMIT 1"
        )->find();

        // Get current enrollments
        $currentEnrollments = $this->db->query(
            "SELECT COUNT(*) as count
             FROM enrollments e
             JOIN schedules sc ON e.schedule_id = sc.id
             WHERE e.student_id = ?
             AND sc.semester = ?
             AND sc.school_year = ?",
            [
                $studentId,
                $currentSemester['semester'] ?? '1st',
                $currentSemester['school_year'] ?? date('Y') . '-' . (date('Y') + 1)
            ]
        )->find();

        // Get current units
        $currentUnits = $this->db->query(
            "SELECT COALESCE(SUM(sub.units), 0) as total_units
             FROM enrollments e
             JOIN schedules sc ON e.schedule_id = sc.id
             JOIN subjects sub ON sc.subject_id = sub.id
             WHERE e.student_id = ?
             AND sc.semester = ?
             AND sc.school_year = ?",
            [
                $studentId,
                $currentSemester['semester'] ?? '1st',
                $currentSemester['school_year'] ?? date('Y') . '-' . (date('Y') + 1)
            ]
        )->find();

        // Get total completed subjects (passed with grade 1.0-3.0)
        $completedSubjects = $this->db->query(
            "SELECT COUNT(*) as count
             FROM enrollments e
             JOIN grades g ON e.id = g.enrollment_id
             WHERE e.student_id = ?
             AND g.grade >= 1.0 AND g.grade <= 3.0",
            [$studentId]
        )->find();

        // Get GWA (General Weighted Average - only passed subjects)
        $gwa = $this->db->query(
            "SELECT AVG(g.grade) as gwa
             FROM enrollments e
             JOIN grades g ON e.id = g.enrollment_id
             WHERE e.student_id = ?
             AND g.grade IS NOT NULL
             AND g.grade <= 3.0",  // Only passed subjects count toward GWA
            [$studentId]
        )->find();

        return [
            'current_semester' => $currentSemester,
            'current_enrollments' => $currentEnrollments['count'] ?? 0,
            'current_units' => $currentUnits['total_units'] ?? 0,
            'completed_subjects' => $completedSubjects['count'] ?? 0,
            'gpa' => $gwa['gwa'] ?? null  // This is actually GWA in Philippine system
        ];
    }

    /**
     * Get student's current schedule
     */
    public function getCurrentSchedule(int $studentId): array
    {
        $currentSemester = $this->db->query(
            "SELECT semester, school_year
             FROM schedules
             ORDER BY created_at DESC
             LIMIT 1"
        )->find();

        if (!$currentSemester) {
            return [];
        }

        $schedule = $this->db->query(
            "SELECT 
                sub.code, sub.name as subject_name, sub.units,
                sc.day, sc.time, sc.room, sc.instructor,
                sc.semester, sc.school_year
             FROM enrollments e
             JOIN schedules sc ON e.schedule_id = sc.id
             JOIN subjects sub ON sc.subject_id = sub.id
             WHERE e.student_id = ?
             AND sc.semester = ?
             AND sc.school_year = ?
             ORDER BY sc.day, sc.time",
            [
                $studentId,
                $currentSemester['semester'],
                $currentSemester['school_year']
            ]
        )->findAll();

        return $schedule;
    }

    /**
     * Get all student enrollments
     */
    public function getAllEnrollments(int $studentId): array
    {
        $enrollments = $this->db->query(
            "SELECT 
                sub.code, sub.name as subject_name, sub.units,
                sc.day, sc.time, sc.room, sc.instructor,
                sc.semester, sc.school_year,
                g.grade, g.remarks
             FROM enrollments e
             JOIN schedules sc ON e.schedule_id = sc.id
             JOIN subjects sub ON sc.subject_id = sub.id
             LEFT JOIN grades g ON e.id = g.enrollment_id
             WHERE e.student_id = ?
             ORDER BY sc.school_year DESC, sc.semester DESC, sub.code",
            [$studentId]
        )->findAll();

        return $enrollments;
    }

    /**
     * Get student grades
     */
    public function getGrades(int $studentId): array
    {
        $grades = $this->db->query(
            "SELECT 
                sub.code, sub.name as subject_name, sub.units,
                sc.semester, sc.school_year,
                g.grade, g.remarks,
                sc.instructor
             FROM enrollments e
             JOIN schedules sc ON e.schedule_id = sc.id
             JOIN subjects sub ON sc.subject_id = sub.id
             LEFT JOIN grades g ON e.id = g.enrollment_id
             WHERE e.student_id = ?
             ORDER BY sc.school_year DESC, sc.semester DESC, sub.code",
            [$studentId]
        )->findAll();

        return $grades;
    }

    /**
     * Get grade statistics
     * Note: In Philippine system, MIN returns highest grade (1.0 is best)
     *       and MAX returns lowest grade (3.0 is worst passing)
     */
    public function getGradeStatistics(int $studentId): array
    {
        $stats = $this->db->query(
            "SELECT 
                COUNT(DISTINCT e.id) as total_subjects,
                COUNT(CASE WHEN g.grade IS NOT NULL THEN 1 END) as graded_subjects,
                COUNT(CASE WHEN g.grade IS NULL THEN 1 END) as pending_subjects,
                COUNT(CASE WHEN g.grade >= 1.0 AND g.grade <= 3.0 THEN 1 END) as passed_subjects,
                COUNT(CASE WHEN g.grade = 5.0 THEN 1 END) as failed_subjects,
                AVG(CASE WHEN g.grade >= 1.0 AND g.grade <= 3.0 THEN g.grade END) as gpa,
                MIN(CASE WHEN g.grade >= 1.0 AND g.grade <= 3.0 THEN g.grade END) as highest_grade,
                MAX(CASE WHEN g.grade >= 1.0 AND g.grade <= 3.0 THEN g.grade END) as lowest_grade,
                SUM(sub.units) as total_units,
                SUM(CASE WHEN g.grade >= 1.0 AND g.grade <= 3.0 THEN sub.units ELSE 0 END) as earned_units
             FROM enrollments e
             JOIN schedules sc ON e.schedule_id = sc.id
             JOIN subjects sub ON sc.subject_id = sub.id
             LEFT JOIN grades g ON e.id = g.enrollment_id
             WHERE e.student_id = ?",
            [$studentId]
        )->find();

        return $stats ?: [];
    }

    /**
     * Get available semesters for student
     */
    public function getAvailableSemesters(int $studentId): array
    {
        $semesters = $this->db->query(
            "SELECT DISTINCT sc.semester, sc.school_year
             FROM enrollments e
             JOIN schedules sc ON e.schedule_id = sc.id
             WHERE e.student_id = ?
             ORDER BY sc.school_year DESC, sc.semester DESC",
            [$studentId]
        )->findAll();

        return $semesters;
    }

    /**
     * Get grades by semester
     */
    public function getGradesBySemester(int $studentId, string $semester, string $schoolYear): array
    {
        $grades = $this->db->query(
            "SELECT 
                sub.code, sub.name as subject_name, sub.units,
                g.grade, g.remarks,
                sc.instructor
             FROM enrollments e
             JOIN schedules sc ON e.schedule_id = sc.id
             JOIN subjects sub ON sc.subject_id = sub.id
             LEFT JOIN grades g ON e.id = g.enrollment_id
             WHERE e.student_id = ?
             AND sc.semester = ?
             AND sc.school_year = ?
             ORDER BY sub.code",
            [$studentId, $semester, $schoolYear]
        )->findAll();

        return $grades;
    }

    /**
     * Get schedule for a specific day
     */
    public function getScheduleByDay(int $studentId, string $day): array
    {
        $currentSemester = $this->db->query(
            "SELECT semester, school_year
             FROM schedules
             ORDER BY created_at DESC
             LIMIT 1"
        )->find();

        if (!$currentSemester) {
            return [];
        }

        $schedule = $this->db->query(
            "SELECT 
                sub.code, sub.name as subject_name,
                sc.time, sc.room, sc.instructor
             FROM enrollments e
             JOIN schedules sc ON e.schedule_id = sc.id
             JOIN subjects sub ON sc.subject_id = sub.id
             WHERE e.student_id = ?
             AND sc.semester = ?
             AND sc.school_year = ?
             AND sc.day LIKE ?
             ORDER BY sc.time",
            [
                $studentId,
                $currentSemester['semester'],
                $currentSemester['school_year'],
                '%' . $day . '%'
            ]
        )->findAll();

        return $schedule;
    }
}
