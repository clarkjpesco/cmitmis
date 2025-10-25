<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\StudentPortalService;

class StudentController
{
    public function __construct(
        private TemplateEngine $view,
        private StudentPortalService $studentPortalService
    ) {}

    /**
     * Student dashboard
     */
    public function dashboard()
    {
        // Get student information from session
        $userId = (int)$_SESSION['user']['id'];
        if (!$userId) {
            redirectTo('/login');
            return;
        }

        $student = $this->studentPortalService->getStudentByUserId($userId);


        // Get dashboard statistics
        $stats = $this->studentPortalService->getDashboardStats($student['id']);

        // Get current schedule for dashboard display
        $schedule = $this->studentPortalService->getCurrentSchedule($student['id']);

        echo $this->view->render('/student/dashboard.php', [
            'active' => 'dashboard',
            'stats' => $stats,
            'schedule' => $schedule,
            'student' => $student
        ]);
    }

    /**
     * Student schedule
     */
    public function schedule()
    {
        $userId = (int)$_SESSION['user']['id'];
        if (!$userId) {
            redirectTo('/login');
            return;
        }

        $student = $this->studentPortalService->getStudentByUserId($userId);

        // Get current schedule
        $schedule = $this->studentPortalService->getCurrentSchedule($student['id']);

        // Group schedule by day for better display
        $scheduleByDay = [];
        foreach ($schedule as $class) {
            $day = $class['day'];
            if (!isset($scheduleByDay[$day])) {
                $scheduleByDay[$day] = [];
            }
            $scheduleByDay[$day][] = $class;
        }

        // Define day order for consistent display
        $dayOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        echo $this->view->render('/student/schedule.php', [
            'active' => 'schedule',
            'schedule' => $schedule,
            'scheduleByDay' => $scheduleByDay,
            'dayOrder' => $dayOrder,
            'student' => $student
        ]);
    }

    /**
     * Student enrollments
     */
    public function enrollments()
    {
        $userId = (int)$_SESSION['user']['id'];
        if (!$userId) {
            redirectTo('/login');
            return;
        }

        $student = $this->studentPortalService->getStudentByUserId($userId);

        // Get all enrollments
        $enrollments = $this->studentPortalService->getAllEnrollments($student['id']);

        // Get available semesters for filtering
        $semesters = $this->studentPortalService->getAvailableSemesters($student['id']);

        echo $this->view->render('/student/enrollments.php', [
            'active' => 'enrollments',
            'enrollments' => $enrollments,
            'semesters' => $semesters,
            'student' => $student
        ]);
    }

    /**
     * Student grades
     */
    public function grades()
    {
        $userId = (int)$_SESSION['user']['id'];
        if (!$userId) {
            redirectTo('/login');
            return;
        }

        $student = $this->studentPortalService->getStudentByUserId($userId);

        // Get all grades
        $grades = $this->studentPortalService->getGrades($student['id']);

        // Get grade statistics
        $statistics = $this->studentPortalService->getGradeStatistics($student['id']);

        // Get available semesters for filtering
        $semesters = $this->studentPortalService->getAvailableSemesters($student['id']);

        echo $this->view->render('/student/grades.php', [
            'active' => 'grades',
            'grades' => $grades,
            'statistics' => $statistics,
            'semesters' => $semesters,
            'student' => $student
        ]);
    }

    /**
     * Student profile
     */
    public function profile()
    {
        $userId = (int)$_SESSION['user']['id'];
        if (!$userId) {
            redirectTo('/login');
            return;
        }

        // Get detailed student information
        $student = $this->studentPortalService->getStudentByUserId($userId);

        // Get dashboard stats for profile page
        $stats = $this->studentPortalService->getDashboardStats($student['id']);

        // Get grade statistics for profile page
        $statistics = $this->studentPortalService->getGradeStatistics($student['id']);

        echo $this->view->render('/student/profile.php', [
            'active' => 'profile',
            'student' => $student,
            'stats' => $stats,
            'statistics' => $statistics,
            'user' => $_SESSION['user'] ?? []
        ]);
    }

    /**
     * Get grades by semester (AJAX endpoint)
     */
    public function getGradesBySemester()
    {
        $userId = (int)$_SESSION['user']['id'];
        if (!$userId) {
            redirectTo('/login');
            return;
        }

        // Get detailed student information
        $student = $this->studentPortalService->getStudentByUserId($userId);
        $studentId = $student['id'];

        if (!$studentId) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
            return;
        }

        $semester = $_GET['semester'] ?? '';
        $schoolYear = $_GET['school_year'] ?? '';

        if (!$semester || !$schoolYear) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Missing required parameters'
            ]);
            return;
        }

        try {
            $grades = $this->studentPortalService->getGradesBySemester($studentId, $semester, $schoolYear);

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'grades' => $grades
            ]);
        } catch (\Exception $e) {
            error_log("Error fetching grades by semester: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to fetch grades'
            ]);
        }
    }

    /**
     * Get schedule by day (AJAX endpoint)
     */
    public function getScheduleByDay()
    {
        $userId = (int)$_SESSION['user']['id'];
        if (!$userId) {
            redirectTo('/login');
            return;
        }

        // Get detailed student information
        $student = $this->studentPortalService->getStudentByUserId($userId);
        $studentId = $student['id'];

        if (!$studentId) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
            return;
        }

        $day = $_GET['day'] ?? '';

        if (!$day) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Day parameter is required'
            ]);
            return;
        }

        try {
            $schedule = $this->studentPortalService->getScheduleByDay($studentId, $day);

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'schedule' => $schedule,
                'day' => $day
            ]);
        } catch (\Exception $e) {
            error_log("Error fetching schedule by day: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to fetch schedule'
            ]);
        }
    }
}
