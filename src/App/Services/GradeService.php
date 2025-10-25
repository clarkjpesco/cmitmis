<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use InvalidArgumentException;
use Exception;

class GradeService
{
    public function __construct(private Database $db) {}

    /**
     * Get all students with their grade statistics
     */
    public function getStudentsWithGrades(): array
    {
        $students = $this->db->query(
            "SELECT s.id, s.student_number, c.name as course, s.year_level, 
                    u.full_name,
                    COUNT(DISTINCT e.id) as total_enrollments,
                    COUNT(DISTINCT CASE WHEN g.grade IS NOT NULL THEN g.id END) as graded_count,
                    COUNT(DISTINCT CASE WHEN g.grade IS NULL THEN g.id END) as pending_count,
                    AVG(g.grade) as average_grade
             FROM students s
             JOIN users u ON s.user_id = u.id
             LEFT JOIN courses c ON s.course_id = c.id
             LEFT JOIN enrollments e ON s.id = e.student_id
             LEFT JOIN grades g ON e.id = g.enrollment_id
             
             GROUP BY s.id
             ORDER BY u.full_name"
        )->findAll();

        return $students;
    }
    public function getAllStudentsWithGrades(int $length, int $offset, ?string $searchTerm = null, ?string $course = null, ?string $yearLevel = null)
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
            "SELECT s.id, s.student_number, c.name as course,c.code as course_code, s.year_level, 
                    u.full_name,
                    COUNT(DISTINCT e.id) as total_enrollments,
                    COUNT(DISTINCT CASE WHEN g.grade IS NOT NULL THEN g.id END) as graded_count,
                    COUNT(DISTINCT CASE WHEN g.grade IS NULL THEN g.id END) as pending_count,
                    AVG(g.grade) as average_grade
             FROM students s
             JOIN users u ON s.user_id = u.id
             LEFT JOIN courses c ON s.course_id = c.id
             LEFT JOIN enrollments e ON s.id = e.student_id
             LEFT JOIN grades g ON e.id = g.enrollment_id
             {$whereClause} 
             GROUP BY s.id
             ORDER BY u.full_name
             LIMIT {$length} OFFSET {$offset}
             ",
            $params
        )->findAll();

        $studentCount = $this->db->query(
            "SELECT COUNT(*)
        FROM students s
             JOIN users u ON s.user_id = u.id
             LEFT JOIN courses c ON s.course_id = c.id
             LEFT JOIN enrollments e ON s.id = e.student_id
             LEFT JOIN grades g ON e.id = g.enrollment_id
             {$whereClause} 
            --  GROUP BY s.id
            --  ORDER BY u.full_name
        ",
            $params
        )->count();

        return [$students, $studentCount];
    }

    /**
     * Get student details
     */
    public function getStudentDetails(int $studentId): ?array
    {
        $student = $this->db->query(
            "SELECT s.*,c.name as course, u.full_name, u.username
             FROM students s
             JOIN users u ON s.user_id = u.id
             JOIN courses c ON s.course_id = c.id
             WHERE s.id = ?",
            [$studentId]
        )->find();

        return $student ?: null;
    }

    /**
     * Get all grades for a student
     */
    public function getStudentGrades(int $studentId): array
    {
        $grades = $this->db->query(
            "SELECT g.id as grade_id, g.grade, g.remarks,
                    e.id as enrollment_id,
                    sub.code as subject_code, sub.name as subject_name, sub.units,
                    sc.day, sc.time, sc.room, sc.instructor,
                    sc.semester, sc.school_year
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
     * Get grade statistics for a student
     */
    public function getGradeStatistics(int $studentId): array
    {
        $stats = $this->db->query(
            "SELECT 
                COUNT(DISTINCT e.id) as total_subjects,
                COUNT(DISTINCT CASE WHEN g.grade IS NOT NULL THEN g.id END) as graded_subjects,
                COUNT(DISTINCT CASE WHEN g.grade IS NULL THEN g.id END) as pending_subjects,
                AVG(CASE WHEN g.grade >= 1.0 AND g.grade <= 3.0 THEN g.grade END) as average_grade,
                MIN(CASE WHEN g.grade >= 1.0 AND g.grade <= 3.0 THEN g.grade END) as highest_grade,
                MAX(CASE WHEN g.grade >= 1.0 AND g.grade <= 3.0 THEN g.grade END) as lowest_grade,
                COUNT(DISTINCT CASE WHEN g.grade >= 1.0 AND g.grade <= 3.0 THEN g.id END) as passed_subjects,
                COUNT(DISTINCT CASE WHEN g.grade = 5.0 THEN g.id END) as failed_subjects
             FROM enrollments e
             LEFT JOIN grades g ON e.id = g.enrollment_id
             WHERE e.student_id = ?",
            [$studentId]
        )->find();

        return $stats ?: [];
    }

    /**
     * Get enrollment details for grade input
     */
    public function getEnrollmentForGrading(int $enrollmentId): ?array
    {
        $enrollment = $this->db->query(
            "SELECT e.id as enrollment_id, e.student_id,
                    s.id as student_id, s.student_number, s.course_id, c.name as course, s.year_level,
                    u.full_name as student_name,
                    sub.code as subject_code, sub.name as subject_name, sub.units,
                    sc.day, sc.time, sc.room, sc.instructor,
                    sc.semester, sc.school_year,
                    g.id as grade_id, g.grade, g.remarks
             FROM enrollments e
             JOIN students s ON e.student_id = s.id
             JOIN users u ON s.user_id = u.id
             JOIN courses c ON s.course_id = c.id
             JOIN schedules sc ON e.schedule_id = sc.id
             JOIN subjects sub ON sc.subject_id = sub.id
             LEFT JOIN grades g ON e.id = g.enrollment_id
             WHERE e.id = ?",
            [$enrollmentId]
        )->find();

        return $enrollment ?: null;
    }

    /**
     * Update or create grade
     */
    public function updateGrade(int $enrollmentId, ?float $grade, string $remarks): array
    {
        try {
            // Validate grade if provided (School system: 1.0-3.0 in 0.1 increments, 4.0, 5.0, 7.0, 9.0)
            if ($grade !== null) {
                $validGrades = [
                    1.0,
                    1.1,
                    1.2,
                    1.3,
                    1.4,
                    1.5,
                    1.6,
                    1.7,
                    1.8,
                    1.9,
                    2.0,
                    2.1,
                    2.2,
                    2.3,
                    2.4,
                    2.5,
                    2.6,
                    2.7,
                    2.8,
                    2.9,
                    3.0,
                    4.0,
                    5.0,
                    7.0,
                    9.0
                ];
                if (!in_array($grade, $validGrades)) {
                    throw new InvalidArgumentException('Invalid grade. Must be 1.0-3.0 (in 0.1 increments), 4.0 (INC), 5.0 (Failed), 7.0 (Withdrawn), or 9.0 (Dropped)');
                }
            }

            // Check if grade record exists
            $existingGrade = $this->db->query(
                "SELECT id FROM grades WHERE enrollment_id = ?",
                [$enrollmentId]
            )->find();

            $this->db->beginTransaction();

            if ($existingGrade) {
                // Update existing grade
                $this->db->query(
                    "UPDATE grades 
                     SET grade = ?, remarks = ?
                     WHERE enrollment_id = ?",
                    [$grade, $remarks, $enrollmentId]
                );
            } else {
                // Insert new grade
                $this->db->query(
                    "INSERT INTO grades (enrollment_id, grade, remarks)
                     VALUES (?, ?, ?)",
                    [$enrollmentId, $grade, $remarks]
                );
            }

            $this->db->commit();

            return [
                'success' => true,
                'message' => 'Grade updated successfully'
            ];
        } catch (InvalidArgumentException $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Error updating grade: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to update grade'
            ];
        }
    }

    /**
     * Get grades by semester
     */
    public function getGradesBySemester(int $studentId, string $semester, string $schoolYear): array
    {
        $grades = $this->db->query(
            "SELECT g.id as grade_id, g.grade, g.remarks,
                    e.id as enrollment_id,
                    sub.code as subject_code, sub.name as subject_name, sub.units,
                    sc.day, sc.time, sc.room, sc.instructor
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
     * Get available semesters for a student
     */
    public function getStudentSemesters(int $studentId): array
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
}
