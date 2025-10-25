<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;

class DashboardService
{
    public function __construct(private Database $db) {}

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(): array
    {
        // Get total students
        $totalStudents = $this->db->query("SELECT COUNT(*) FROM students")->count();
        $activeStudents = $this->db->query("SELECT COUNT(*) FROM students WHERE status = 'active'")->count();

        // Get total subjects
        $totalSubjects = $this->db->query("SELECT COUNT(*) FROM subjects WHERE status = 'active'")->count();
        $totalUnits = $this->db->query("SELECT COALESCE(SUM(units), 0) as total_units FROM subjects WHERE status = 'active'")->find();

        // Get total schedules
        $totalSchedules = $this->db->query("SELECT COUNT(*) FROM schedules")->count();

        // Get total enrollments
        $totalEnrollments = $this->db->query("SELECT COUNT(*) FROM enrollments")->count();

        // Get courses distribution
        $coursesDistribution = $this->getCoursesDistribution();

        // Get year level distribution
        $yearDistribution = $this->getYearDistribution();

        // Get grade statistics
        $gradeStats = $this->getGradeStatistics();

        return [
            'total_students' => $totalStudents,
            'active_students' => $activeStudents,
            'total_subjects' => $totalSubjects,
            'total_units' => $totalUnits['total_units'] ?? 0,
            'total_schedules' => $totalSchedules,
            'total_enrollments' => $totalEnrollments,
            'courses_distribution' => $coursesDistribution,
            'year_distribution' => $yearDistribution,
            'grade_stats' => $gradeStats
        ];
    }

    /**
     * Get courses distribution
     */
    public function getCoursesDistribution(): array
    {
        return $this->db->query(
            "SELECT c.code, c.name, COUNT(s.id) as student_count
             FROM courses c
             LEFT JOIN students s ON c.id = s.course_id
             WHERE c.is_active = 1
             GROUP BY c.id
             ORDER BY student_count DESC"
        )->findAll();
    }

    /**
     * Get year level distribution
     */
    public function getYearDistribution(): array
    {
        return $this->db->query(
            "SELECT year_level, COUNT(*) as student_count
             FROM students
             WHERE status = 'active'
             GROUP BY year_level
             ORDER BY year_level"
        )->findAll();
    }

    /**
     * Get grade statistics
     */
    public function getGradeStatistics(): array
    {
        $stats = $this->db->query(
            "SELECT 
                COUNT(CASE WHEN grade >= 1.0 AND grade <= 3.0 THEN 1 END) as passed,
                COUNT(CASE WHEN grade IS NULL THEN 1 END) as pending,
                COUNT(CASE WHEN grade = 5.0 THEN 1 END) as failed
             FROM grades"
        )->find();

        return $stats ?: [
            'passed' => 0,
            'pending' => 0,
            'failed' => 0
        ];
    }

    /**
     * Get recent students
     */
    public function getRecentStudents(int $limit = 5): array
    {
        return $this->db->query(
            "SELECT s.id, s.student_number, s.year_level, s.status,
                    u.full_name, 
                    c.name as course, c.code as course_code
             FROM students s
             JOIN users u ON s.user_id = u.id
             LEFT JOIN courses c ON s.course_id = c.id
             ORDER BY u.created_at DESC
             LIMIT {$limit}"

        )->findAll();
    }

    /**
     * Get enrollment statistics by semester
     */
    public function getEnrollmentStatsBySemester(): array
    {
        return $this->db->query(
            "SELECT sc.semester, sc.school_year, COUNT(e.id) as enrollment_count
             FROM enrollments e
             JOIN schedules sc ON e.schedule_id = sc.id
             GROUP BY sc.semester, sc.school_year
             ORDER BY sc.school_year DESC, sc.semester DESC
             LIMIT 5"
        )->findAll();
    }

    /**
     * Get popular subjects
     */
    public function getPopularSubjects(int $limit = 5): array
    {
        return $this->db->query(
            "SELECT sub.code, sub.name, sub.units,
                    COUNT(e.id) as enrollment_count
             FROM subjects sub
             LEFT JOIN schedules sc ON sub.id = sc.subject_id
             LEFT JOIN enrollments e ON sc.id = e.schedule_id
             WHERE sub.status = 'active'
             GROUP BY sub.id
             ORDER BY enrollment_count DESC
             LIMIT ?",
            [$limit]
        )->findAll();
    }

    /**
     * Get schedule statistics by day
     */
    public function getSchedulesByDay(): array
    {
        return $this->db->query(
            "SELECT 
                SUM(CASE WHEN day LIKE '%Monday%' THEN 1 ELSE 0 END) as monday,
                SUM(CASE WHEN day LIKE '%Tuesday%' THEN 1 ELSE 0 END) as tuesday,
                SUM(CASE WHEN day LIKE '%Wednesday%' THEN 1 ELSE 0 END) as wednesday,
                SUM(CASE WHEN day LIKE '%Thursday%' THEN 1 ELSE 0 END) as thursday,
                SUM(CASE WHEN day LIKE '%Friday%' THEN 1 ELSE 0 END) as friday,
                SUM(CASE WHEN day LIKE '%Saturday%' THEN 1 ELSE 0 END) as saturday,
                SUM(CASE WHEN day LIKE '%Sunday%' THEN 1 ELSE 0 END) as sunday
             FROM schedules"
        )->find();
    }

    /**
     * Get current semester info
     */
    public function getCurrentSemester(): ?array
    {
        return $this->db->query(
            "SELECT semester, school_year
             FROM schedules
             ORDER BY created_at DESC
             LIMIT 1"
        )->find();
    }
}
