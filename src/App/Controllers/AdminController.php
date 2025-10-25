<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{
    ValidatorService,
    UserService,
    StudentService,
    CourseService,
    SubjectService,
    ClassesService,
    EnrollmentService,
    GradeService,
    DashboardService,
    ImportProgressService
};

use Exception;
use InvalidArgumentException;

class AdminController
{

    public function __construct(
        private TemplateEngine $view,
        private ValidatorService $validatorService,
        private UserService $userService,
        private StudentService $studentService,
        private CourseService $courseService,
        private SubjectService $subjectService,
        private ClassesService $classesService,
        private EnrollmentService $enrollmentService,
        private GradeService $gradeService,
        private DashboardService $dashboardService,
        private ImportProgressService $importProgressService
    ) {}

    public function dashboard()
    {
        $stats = $this->dashboardService->getDashboardStats();
        $recentStudents = $this->dashboardService->getRecentStudents(5);

        echo $this->view->render('/admin/dashboard.php', [
            'active' => 'dashboard',
            'stats' => $stats,
            'recent_students' => $recentStudents
        ]);
    }

    public function students()
    {

        $page = $_GET['p'] ?? 1;
        $page = (int) $page;
        $length = 50;
        $offset = ($page - 1) * $length;
        $searchTerm = $_GET['s'] ?? null;
        $course =  $_GET['course'] ?? null;
        $yearLevel =  $_GET['year_level'] ?? null;
        $status = $_GET['status'] ?? null;


        [$students, $count] = $this->studentService->getAllStudents($length, $offset, $searchTerm, $course, $yearLevel, $status);

        $lastPage = ceil($count / $length);
        $pages = $lastPage ? range(1, $lastPage) : [];

        $pageLinks = array_map(
            fn($pageNum) => http_build_query([
                'p' => $pageNum,
                's' => $searchTerm,
                'course' => $course,
                'year_level' => $yearLevel,
                'status' => $status

            ]),
            $pages
        );

        $courses = $this->courseService->getCourses();


        echo $this->view->render('/admin/students.php', [
            'active' => 'students',
            'students' => $students,
            'courses' => $courses,

            'currentPage' => $page,
            'previousPageQuery' => http_build_query([
                'p' => $page - 1,
                's' => $searchTerm,
                'course' => $course,
                'year_level' => $yearLevel,
                'status' => $status

            ]),
            'lastPage' => $lastPage,
            'nextPageQuery' => http_build_query([
                'p' => $page + 1,
                's' => $searchTerm,
                'course' => $course,
                'year_level' => $yearLevel,
                'status' => $status

            ]),
            'pageLinks' => $pageLinks,
            'searchTerm' => $searchTerm,
            'selectedCourse' => $course,
            'selectedYearLevel' => $yearLevel,
            'selectedStatus' => $status,
            'offset' => $offset,
            'count' => $count
        ]);
    }

    public function createStudentView()
    {
        $courses = $this->courseService->getCourses();
        echo $this->view->render('/admin/students_add.php', [
            'active' => 'students',
            'courses' => $courses
        ]);
    }
    public function createStudent()
    {
        $this->validatorService->validateStudent($_POST);
        $this->userService->isUserNameTaken($_POST['username']);
        $this->studentService->createStudent($_POST);
        redirectTo('/admin/students');
    }

    //courses

    public function courses()
    {

        $courses = $this->courseService->getAllCourses();
        echo $this->view->render('/admin/courses.php', [
            'active' => 'courses',
            'courses' => $courses
        ]);
    }
    public function createCourseView()
    {
        echo $this->view->render('/admin/courses_add.php', [
            'active' => 'courses'
        ]);
    }

    public function createCourse()
    {
        $this->validatorService->validateCourse($_POST);
        $this->courseService->createCourse($_POST);
        redirectTo('/admin/courses');
    }

    public function editCourseView(array $params)
    {

        $course = $this->courseService->getCourse($params['course']);

        if (!$course) {
            redirectTo('/admin/courses');
        }

        echo $this->view->render("admin/courses_edit.php", [
            'active' => 'courses',
            'course' => $course
        ]);
    }

    public function editCourse(array $params)
    {


        $course = $this->courseService->getCourse($params['course']);


        if (!$course) {
            redirectTo('/admin/courses');
        }

        $this->validatorService->validateCourse($_POST);
        $this->courseService->updateCourse($_POST, $course['id']);
        redirectTo($_SERVER['HTTP_REFERER']);
    }





    public function subjects()
    {

        $page = $_GET['p'] ?? 1;
        $page = (int) $page;
        $length = 25;
        $offset = ($page - 1) * $length;
        $searchTerm = $_GET['s'] ?? null;
        $units =  $_GET['units'] ?? null;
        $status = $_GET['status'] ?? null;


        [$subjects, $count] = $this->subjectService->getSubjects($length, $offset, $searchTerm, $units, $status);

        $lastPage = ceil($count / $length);
        $pages = $lastPage ? range(1, $lastPage) : [];

        $pageLinks = array_map(
            fn($pageNum) => http_build_query([
                'p' => $pageNum,
                's' => $searchTerm,
                'units' => $units,
                'status' => $status
            ]),
            $pages
        );


        echo $this->view->render('/admin/subjects.php', [
            'active' => 'subjects',
            'active' => 'subjects',
            'subjects' => $subjects,
            'currentPage' => $page,
            'previousPageQuery' => http_build_query([
                'p' => $page - 1,
                's' => $searchTerm,
                'units' => $units,
                'status' => $status

            ]),
            'lastPage' => $lastPage,
            'nextPageQuery' => http_build_query([
                'p' => $page + 1,
                's' => $searchTerm,
                'units' => $units,
                'status' => $status

            ]),
            'pageLinks' => $pageLinks,
            'searchTerm' => $searchTerm,
            'selectedUnits' => $units,
            'selectedStatus' => $status,
            'offset' => $offset,
            'count' => $count
        ]);
    }

    public function createSubjectView()
    {
        echo $this->view->render('/admin/subjects_add.php', [
            'active' => 'subjects'
        ]);
    }
    public function createSubject()
    {
        $this->validatorService->validateSubject($_POST);
        $this->subjectService->createSubject($_POST);
        redirectTo('/admin/subjects');
    }


    //classes start


    public function classes()
    {

        // Get pagination parameters
        $page = $_GET['p'] ?? 1;
        $page = (int) $page;
        $length = 25; // schedules per page
        $offset = ($page - 1) * $length;

        // Get filter parameters
        $searchTerm = $_GET['s'] ?? null;
        $semester = $_GET['semester'] ?? null;
        $schoolYear = $_GET['school_year'] ?? null;
        $day = $_GET['day'] ?? null;
        $subjectId = $_GET['subject_id'] ?? null;

        $filters = array_filter([
            'semester' => $semester,
            'school_year' => $schoolYear,
            'day' => $day,
            'subject_id' => $subjectId,
            'search' => $searchTerm
        ]);

        // Get schedules and subjects
        [$schedules, $count] = $this->classesService->getSchedulesPaginated($length, $offset, $filters);
        $subjects = $this->classesService->getAllSubjects();

        // Calculate pagination
        $lastPage = ceil($count / $length);
        $pages = $lastPage ? range(1, $lastPage) : [];

        $pageLinks = array_map(
            fn($pageNum) => http_build_query([
                'p' => $pageNum,
                's' => $searchTerm,
                'semester' => $semester,
                'school_year' => $schoolYear,
                'day' => $day,
                'subject_id' => $subjectId
            ]),
            $pages
        );

        // Get statistics
        $stats = $this->classesService->getScheduleStatistics();
        $schoolYears = $this->classesService->getAllSchoolYear();
        echo $this->view->render('/admin/classes.php', [
            'active' => 'classes',
            'schedules' => $schedules,
            'subjects' => $subjects,
            'schoolYears' => $schoolYears,
            'totalSchedules' => $stats['total_schedules'] ?? 0,
            'currentPage' => $page,
            'lastPage' => $lastPage,
            'pageLinks' => $pageLinks,
            'previousPageQuery' => http_build_query([
                'p' => $page - 1,
                's' => $searchTerm,
                'semester' => $semester,
                'school_year' => $schoolYear,
                'day' => $day,
                'subject_id' => $subjectId
            ]),
            'nextPageQuery' => http_build_query([
                'p' => $page + 1,
                's' => $searchTerm,
                'semester' => $semester,
                'school_year' => $schoolYear,
                'day' => $day,
                'subject_id' => $subjectId
            ]),
            'searchTerm' => $searchTerm,
            'selectedSemester' => $semester,
            'selectedSchoolYear' => $schoolYear,
            'selectedDay' => $day,
            'selectedSubjectId' => $subjectId,
            'offset' => $offset,
            'count' => $count
        ]);
    }

    /**
     * Show add schedule form
     */
    public function createClassesView()
    {

        $subjects = $this->classesService->getAllSubjects();

        echo $this->view->render('/admin/classes_add.php', [
            'active' => 'classes',
            'subjects' => $subjects
        ]);
    }

    /**
     * Create a new class schedule
     */
    public function createClasses()
    {
        try {
            // Handle both POST data and JSON
            if ($_SERVER['CONTENT_TYPE'] && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
                $input = json_decode(file_get_contents('php://input'), true);
            } else {
                $input = $_POST;
            }

            // Prepare data
            $data = [
                'subject_id' => $input['subject_id'] ?? '',
                'semester' => $input['semester'] ?? '',
                'school_year' => $input['school_year'] ?? '',
                'days' => $input['days'] ?? [],
                'start_time' => $input['start_time'] ?? '',
                'end_time' => $input['end_time'] ?? '',
                'room' => $input['room'] ?? '',
                'capacity' => $input['capacity'] ?? null,
                'instructor' => $input['instructor'] ?? null,
                'notes' => $input['notes'] ?? null
            ];

            // Ensure days is an array
            if (isset($data['days']) && !is_array($data['days'])) {
                $data['days'] = [$data['days']];
            }

            // Create the schedule
            $createdSchedule = $this->classesService->createSchedule($data);

            // Return JSON response
            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'Schedule created successfully',
                'schedule' => $createdSchedule,
                'new_token' => $_SESSION['new_csrf_token'] ?? null
            ]);
        } catch (InvalidArgumentException $e) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'errors' => [$e->getMessage()],
                'new_token' => $_SESSION['new_csrf_token'] ?? null
            ]);
        } catch (Exception $e) {
            error_log("Error creating schedule: " . $e->getMessage());
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'errors' => ['An error occurred while creating the schedule. Please try again.'],
                'new_token' => $_SESSION['new_csrf_token'] ?? null
            ]);
        }
    }

    /**
     * Show edit schedule form
     */
    public function showEditScheduleForm()
    {
        try {
            $scheduleId = (int)($_GET['id'] ?? 0);

            if (!$scheduleId) {
                http_response_code(404);
                echo $this->view->render('/admin/error.php', [
                    'message' => 'Schedule not found'
                ]);
                return;
            }

            $schedule = $this->classesService->getScheduleById($scheduleId);
            $subjects = $this->classesService->getAllSubjects();

            echo $this->view->render('/admin/edit-schedule.php', [
                'active' => 'classes',
                'schedule' => $schedule,
                'subjects' => $subjects
            ]);
        } catch (InvalidArgumentException $e) {
            http_response_code(404);
            echo $this->view->render('/admin/error.php', [
                'message' => 'Schedule not found'
            ]);
        } catch (Exception $e) {
            error_log("Error loading edit schedule form: " . $e->getMessage());
            echo $this->view->render('/admin/error.php', [
                'message' => 'Failed to load edit form'
            ]);
        }
    }

    /**
     * Update a class schedule
     */
    public function updateSchedule()
    {
        try {
            $scheduleId = (int)($_POST['id'] ?? $_GET['id'] ?? 0);

            if (!$scheduleId) {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'errors' => ['Invalid schedule ID'],
                    'new_token' => $_SESSION['new_csrf_token'] ?? null
                ]);
                return;
            }

            // Handle both POST data and JSON
            if ($_SERVER['CONTENT_TYPE'] && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
                $input = json_decode(file_get_contents('php://input'), true);
            } else {
                $input = $_POST;
            }

            $data = [
                'subject_id' => $input['subject_id'] ?? '',
                'semester' => $input['semester'] ?? '',
                'school_year' => $input['school_year'] ?? '',
                'days' => $input['days'] ?? [],
                'start_time' => $input['start_time'] ?? '',
                'end_time' => $input['end_time'] ?? '',
                'room' => $input['room'] ?? '',
                'capacity' => $input['capacity'] ?? null,
                'instructor' => $input['instructor'] ?? null,
                'notes' => $input['notes'] ?? null
            ];

            // Ensure days is an array
            if (isset($data['days']) && !is_array($data['days'])) {
                $data['days'] = [$data['days']];
            }

            $updatedSchedule = $this->classesService->updateSchedule($scheduleId, $data);

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Schedule updated successfully',
                'schedule' => $updatedSchedule,
                'new_token' => $_SESSION['new_csrf_token'] ?? null
            ]);
        } catch (InvalidArgumentException $e) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'errors' => [$e->getMessage()],
                'new_token' => $_SESSION['new_csrf_token'] ?? null
            ]);
        } catch (Exception $e) {
            error_log("Error updating schedule: " . $e->getMessage());
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'errors' => ['An error occurred while updating the schedule.'],
                'new_token' => $_SESSION['new_csrf_token'] ?? null
            ]);
        }
    }

    /**
     * Delete a class schedule
     */
    public function deleteSchedule()
    {
        try {
            $scheduleId = (int)($_POST['id'] ?? $_GET['id'] ?? 0);

            if (!$scheduleId) {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'errors' => ['Invalid schedule ID'],
                    'new_token' => $_SESSION['new_csrf_token'] ?? null
                ]);
                return;
            }

            $this->classesService->deleteSchedule($scheduleId);

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Schedule deleted successfully',
                'new_token' => $_SESSION['new_csrf_token'] ?? null
            ]);
        } catch (InvalidArgumentException $e) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'errors' => [$e->getMessage()],
                'new_token' => $_SESSION['new_csrf_token'] ?? null
            ]);
        } catch (Exception $e) {
            error_log("Error deleting schedule: " . $e->getMessage());
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'errors' => ['An error occurred while deleting the schedule.'],
                'new_token' => $_SESSION['new_csrf_token'] ?? null
            ]);
        }
    }

    /**
     * Get all subjects (API endpoint)
     */
    public function getSubjects()
    {
        try {
            $subjects = $this->classesService->getAllSubjects();

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'subjects' => $subjects
            ]);
        } catch (Exception $e) {
            error_log("Error fetching subjects: " . $e->getMessage());
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'errors' => ['Failed to load subjects']
            ]);
        }
    }

    /**
     * Get schedule statistics (API endpoint)
     */
    public function getScheduleStatistics()
    {
        try {
            $stats = $this->classesService->getScheduleStatistics();

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'statistics' => $stats
            ]);
        } catch (Exception $e) {
            error_log("Error fetching schedule statistics: " . $e->getMessage());
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'errors' => ['Failed to load statistics']
            ]);
        }
    }




    // public function enrollments()
    // {
    //     $students = $this->enrollmentService->enrollments();
    //     echo $this->view->render('/admin/enrollments.php', [
    //         'active' => 'enrollments',
    //         'students' => $students

    //     ]);
    // }
    public function enrollments()
    {

        $page = $_GET['p'] ?? 1;
        $page = (int) $page;
        $length = 50;
        $offset = ($page - 1) * $length;
        $searchTerm = $_GET['s'] ?? null;
        $course =  $_GET['course'] ?? null;
        $yearLevel =  $_GET['year_level'] ?? null;


        [$students, $count] = $this->enrollmentService->getAllStudentsEnrollments($length, $offset, $searchTerm, $course, $yearLevel);

        $lastPage = ceil($count / $length);
        $pages = $lastPage ? range(1, $lastPage) : [];

        $pageLinks = array_map(
            fn($pageNum) => http_build_query([
                'p' => $pageNum,
                's' => $searchTerm,
                'course' => $course,
                'year_level' => $yearLevel
            ]),
            $pages
        );

        $courses = $this->courseService->getCourses();
        echo $this->view->render('/admin/enrollments.php', [
            'active' => 'enrollments',
            'students' => $students,
            'courses' => $courses,
            'currentPage' => $page,
            'previousPageQuery' => http_build_query([
                'p' => $page - 1,
                's' => $searchTerm,
                'course' => $course,
                'year_level' => $yearLevel
            ]),
            'lastPage' => $lastPage,
            'nextPageQuery' => http_build_query([
                'p' => $page + 1,
                's' => $searchTerm,
                'course' => $course,
                'year_level' => $yearLevel
            ]),
            'pageLinks' => $pageLinks,
            'searchTerm' => $searchTerm,
            'selectedCourse' => $course,
            'selectedYearLevel' => $yearLevel,
            'offset' => $offset,
            'count' => $count
        ]);
    }

    public function studentEnrollmentsView(array $params)
    {
        $studentId = (int)$params['id'];

        $student = $this->studentService->getStudentDetails($studentId);
        $currentSemester = $this->classesService->getCurrentSemester();
        $summary = $this->studentService->getEnrollmentSummary($studentId, $currentSemester);
        $enrollments = $this->enrollmentService->getAllEnrollments($studentId);
        $semesters = $this->classesService->getAvailableSemesters($studentId);


        if (!$student) {
            redirectTo('/admin/enrollments');
            return;
        }


        echo $this->view->render('/admin/enrollments_student.php', [
            'active' => 'enrollments',
            'student' => $student,
            'summary' => $summary,
            'enrollments' => $enrollments,
            'semesters' => $semesters,
            'currentSemester' => $currentSemester
        ]);
    }


    public function createEnrollmentView(array $params)
    {
        $studentId = (int)$params['id'];
        $student = $this->studentService->getStudentDetails($studentId);
        $subjects = $this->subjectService->getAllSubjects();

        if (!$student) {
            redirectTo('/admin/enrollments');
            return;
        }

        echo $this->view->render('/admin/enrollments_add.php', [
            'active' => 'enrollments',
            'student' => $student,
            'subjects' => $subjects
        ]);
    }


    public function createEnrollment()
    {


        //dd($_POST);
        // $this->validatorService->validateEnrollment($input);

        try {

            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid JSON data',
                    'new_token' => $_SESSION['new_csrf_token'] ?? null
                ]);
                return;
            }

            $studentId = (int)$input['student_id'];
            $scheduleId = (int)$input['schedule_id'];


            // Check if student is already enrolled in this schedule
            $existingEnrollment = $this->enrollmentService->existingEnrollment($studentId, $scheduleId);

            if ($existingEnrollment) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Student is already enrolled in this class',
                    'new_token' => $_SESSION['new_csrf_token'] ?? null
                ]);
                return;

                // $_SESSION['error'] = 'Student is already enrolled in this class';
                // redirectTo('/admin/enrollments/create');
            }

            // Check schedule capacity
            $scheduleInfo = $this->classesService->scheduleInfo($scheduleId);


            if ($scheduleInfo && $scheduleInfo['enrolled_count'] >= $scheduleInfo['capacity']) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'This class has reached maximum capacity (' . $scheduleInfo['capacity'] . ' students)',
                    'new_token' => $_SESSION['new_csrf_token'] ?? null
                ]);
                return;
            }

            // Check for schedule conflicts
            $conflicts = $this->enrollmentService->scheduleConflicts($studentId, $scheduleId);

            if (!empty($conflicts)) {
                $conflictMsg = 'Schedule conflict detected with: ';
                foreach ($conflicts as $conflict) {
                    $conflictMsg .= $conflict['code'] . ' - ' . $conflict['name'] . ' (' . $conflict['day'] . ', ' . $conflict['time'] . '); ';
                }
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => $conflictMsg,
                    'new_token' => $_SESSION['new_csrf_token'] ?? null
                ]);
                return;
            }

            $result = $this->enrollmentService->createEnrollment($studentId, $scheduleId);

            // Return response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => $result['success'],
                'message' => $result['message'],
                'new_token' => $_SESSION['new_csrf_token'] ?? null
            ]);
        } catch (Exception $e) {
            error_log("Error creating  enrollment: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Internal server error',
                'new_token' => $_SESSION['new_csrf_token'] ?? null
            ]);
        }
    }

    public function getAvailableSchedules()
    {
        // header('Content-Type: application/json');

        $subjectId = $_GET['subject_id'] ?? null;
        $semester = $_GET['semester'] ?? null;
        $schoolYear = $_GET['school_year'] ?? null;
        $studentId = $_GET['student_id'] ?? null;

        if (!$subjectId || !$semester || !$schoolYear) {
            // echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
            // exit;

            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Missing required parameters',
                'new_token' => $_SESSION['new_csrf_token'] ?? null
            ]);
            return;
        }

        $schedules = $this->classesService->getAvailableSchedules($subjectId, $semester, $schoolYear);

        // Check for conflicts if student is provided
        if ($studentId) {
            $studentSchedules = $this->enrollmentService->studentSchedules($studentId, $semester, $schoolYear);

            foreach ($schedules as &$schedule) {
                $schedule['has_conflict'] = false;
                foreach ($studentSchedules as $studentSchedule) {
                    if (
                        $studentSchedule['day'] === $schedule['day'] &&
                        $studentSchedule['time'] === $schedule['time']
                    ) {
                        $schedule['has_conflict'] = true;
                        break;
                    }
                }
                $schedule['is_full'] = $schedule['enrolled_count'] >= $schedule['capacity'];
                $schedule['available_slots'] = $schedule['capacity'] - $schedule['enrolled_count'];
            }
        }

        // echo json_encode(['success' => true, 'schedules' => $schedules]);
        // exit;

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'schedules' => $schedules,
            'new_token' => $_SESSION['new_csrf_token'] ?? null
        ]);
    }

    public function getStudentInfo()
    {
        header('Content-Type: application/json');

        $studentId = $_GET['student_id'] ?? null;
        $semester = $_GET['semester'] ?? null;
        $schoolYear = $_GET['school_year'] ?? null;

        if (!$studentId) {

            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Student ID required',
                'new_token' => $_SESSION['new_csrf_token'] ?? null
            ]);
            return;

            // echo json_encode(['success' => false, 'message' => 'Student ID required']);
            // exit;
        }

        // Get student details
        $student = $this->studentService->getStudentDetails($studentId);

        if (!$student) {
            // echo json_encode(['success' => false, 'message' => 'Student not found']);
            // exit;

            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Student not found',
                'new_token' => $_SESSION['new_csrf_token'] ?? null
            ]);
            return;
        }

        // Get current enrollments and units for the semester
        $enrollmentData = ['enrollments' => [], 'total_units' => 0, 'total_enrollments' => 0];

        if ($semester && $schoolYear) {
            $enrollments = $this->enrollmentService->getCurrentEnrollmentsSemester($studentId, $semester, $schoolYear);

            $totalUnits = 0;
            foreach ($enrollments as $enrollment) {
                $totalUnits += $enrollment['units'];
            }

            $enrollmentData = [
                'enrollments' => $enrollments,
                'total_units' => $totalUnits,
                'total_enrollments' => count($enrollments)
            ];
        }

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'student' => $student,
            'enrollment_data' => $enrollmentData,
            'new_token' => $_SESSION['new_csrf_token'] ?? null
        ]);

        // echo json_encode([
        //     'success' => true,
        //     'student' => $student,
        //     'enrollment_data' => $enrollmentData
        // ]);
        // exit;
    }

    public function getStudents()
    {


        $students = $this->studentService->getStudents();

        // echo json_encode(['success' => true, 'students' => $students]);
        // exit;

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'students' => $students,
            'new_token' => $_SESSION['new_csrf_token'] ?? null
        ]);
    }

    // Add these methods to your AdminController class

    /**
     * Display list of students for grade management
     */
    public function grades()
    {
        $page = $_GET['p'] ?? 1;
        $page = (int) $page;
        $length = 50;
        $offset = ($page - 1) * $length;
        $searchTerm = $_GET['s'] ?? null;
        $course =  $_GET['course'] ?? null;
        $yearLevel =  $_GET['year_level'] ?? null;

        [$students, $count] = $this->gradeService->getAllStudentsWithGrades($length, $offset, $searchTerm, $course, $yearLevel);

        $lastPage = ceil($count / $length);
        $pages = $lastPage ? range(1, $lastPage) : [];

        $pageLinks = array_map(
            fn($pageNum) => http_build_query([
                'p' => $pageNum,
                's' => $searchTerm,
                'course' => $course,
                'year_level' => $yearLevel
            ]),
            $pages
        );

        $courses = $this->courseService->getCourses();
        echo $this->view->render('/admin/grades.php', [
            'active' => 'grades',
            'students' => $students,
            'courses' => $courses,
            'currentPage' => $page,
            'previousPageQuery' => http_build_query([
                'p' => $page - 1,
                's' => $searchTerm,
                'course' => $course,
                'year_level' => $yearLevel
            ]),
            'lastPage' => $lastPage,
            'nextPageQuery' => http_build_query([
                'p' => $page + 1,
                's' => $searchTerm,
                'course' => $course,
                'year_level' => $yearLevel
            ]),
            'pageLinks' => $pageLinks,
            'searchTerm' => $searchTerm,
            'selectedCourse' => $course,
            'selectedYearLevel' => $yearLevel,
            'offset' => $offset,
            'count' => $count
        ]);
    }

    /**
     * Display individual student grades
     */
    public function studentGradesView(array $params)
    {
        $studentId = (int)$params['id'];

        $student = $this->gradeService->getStudentDetails($studentId);

        if (!$student) {
            redirectTo('/admin/grades');
            return;
        }

        $grades = $this->gradeService->getStudentGrades($studentId);
        $statistics = $this->gradeService->getGradeStatistics($studentId);
        $semesters = $this->gradeService->getStudentSemesters($studentId);

        echo $this->view->render('/admin/grades_student.php', [
            'active' => 'grades',
            'student' => $student,
            'grades' => $grades,
            'statistics' => $statistics,
            'semesters' => $semesters
        ]);
    }

    /**
     * Display grade input/update form
     */
    public function gradeInputView(array $params)
    {
        $enrollmentId = (int)$params['id'];

        $enrollment = $this->gradeService->getEnrollmentForGrading($enrollmentId);

        if (!$enrollment) {
            redirectTo('/admin/grades');
            return;
        }

        echo $this->view->render('/admin/grades_input.php', [
            'active' => 'grades',
            'enrollment' => $enrollment
        ]);
    }

    /**
     * Update grade (API endpoint)
     */
    public function updateGrade()
    {
        try {
            // Handle JSON input
            $input = json_decode(file_get_contents('php://input'), true);

            if (!$input) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid JSON data',
                    'new_token' => $_SESSION['new_csrf_token'] ?? null
                ]);
                return;
            }

            $enrollmentId = (int)($input['enrollment_id'] ?? 0);
            $grade = isset($input['grade']) ? (float)$input['grade'] : null;
            $remarks = $input['remarks'] ?? '';

            if (!$enrollmentId) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid enrollment ID',
                    'new_token' => $_SESSION['new_csrf_token'] ?? null
                ]);
                return;
            }

            if (!$remarks) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Remarks are required',
                    'new_token' => $_SESSION['new_csrf_token'] ?? null
                ]);
                return;
            }

            // Update the grade
            $result = $this->gradeService->updateGrade($enrollmentId, $grade, $remarks);

            header('Content-Type: application/json');
            echo json_encode([
                'success' => $result['success'],
                'message' => $result['message'],
                'new_token' => $_SESSION['new_csrf_token'] ?? null
            ]);
        } catch (Exception $e) {
            error_log("Error updating grade: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Internal server error',
                'new_token' => $_SESSION['new_csrf_token'] ?? null
            ]);
        }
    }

    /**
     * Get grades by semester (API endpoint)
     */
    public function getGradesBySemester()
    {
        try {
            $studentId = (int)($_GET['student_id'] ?? 0);
            $semester = $_GET['semester'] ?? '';
            $schoolYear = $_GET['school_year'] ?? '';

            if (!$studentId || !$semester || !$schoolYear) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Missing required parameters',
                    'new_token' => $_SESSION['new_csrf_token'] ?? null
                ]);
                return;
            }

            $grades = $this->gradeService->getGradesBySemester($studentId, $semester, $schoolYear);

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'grades' => $grades,
                'new_token' => $_SESSION['new_csrf_token'] ?? null
            ]);
        } catch (Exception $e) {
            error_log("Error fetching grades: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to fetch grades',
                'new_token' => $_SESSION['new_csrf_token'] ?? null
            ]);
        }
    }


    //import students frome excel
    public function importStudentsView()
    {
        $courses = $this->courseService->getCourses();
        echo $this->view->render('/admin/students_import.php', [
            'active' => 'students',
            'courses' => $courses
        ]);
    }

    public function importStudents()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new InvalidArgumentException('Invalid request method');
            }

            if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
                throw new InvalidArgumentException('Please select a valid Excel file');
            }

            $uploadedFile = $_FILES['excel_file'];

            // Validate file type
            $allowedTypes = ['xlsx', 'xls'];
            $fileExtension = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);

            if (!in_array(strtolower($fileExtension), $allowedTypes)) {
                throw new InvalidArgumentException('Only Excel files (.xlsx, .xls) are allowed');
            }

            // Generate unique import ID
            $importId = uniqid('import_', true);

            // Store import ID in session for the progress page
            $_SESSION['import_id'] = $importId;

            // Store file in a temporary location
            $tempFile = tempnam(sys_get_temp_dir(), 'import_');
            move_uploaded_file($uploadedFile['tmp_name'], $tempFile);

            $_SESSION['import_file'] = $tempFile;

            // Redirect to progress page
            redirectTo('/admin/students/import/progress-page');
        } catch (InvalidArgumentException $e) {
            $_SESSION['error'] = $e->getMessage();
            redirectTo('/admin/students/import');
        } catch (Exception $e) {
            error_log("Import error: " . $e->getMessage());
            $_SESSION['error'] = 'An error occurred during import. Please try again.';
            redirectTo('/admin/students/import');
        }
    }

    public function importProgressPage()
    {
        $importId = $_SESSION['import_id'] ?? null;

        if (!$importId || !isset($_SESSION['import_file']) || !file_exists($_SESSION['import_file'])) {
            $_SESSION['error'] = 'No import in progress';
            redirectTo('/admin/students/import');
            return;
        }

        // Display the progress page (without starting import yet)
        echo $this->view->render('/admin/students_import_progress.php', [
            'active' => 'students',
            'import_id' => $importId
        ]);
    }

    // NEW METHOD: Start the actual import process
    public function startImport()
    {
        header('Content-Type: application/json');

        try {
            // Handle JSON input
            $input = json_decode(file_get_contents('php://input'), true);
            $importId = $input['import_id'] ?? null;

            if (!$importId) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Import ID required',
                    'new_token' => $_SESSION['new_csrf_token'] ?? null
                ]);
                exit;
            }

            // Check if file exists in session
            if (!isset($_SESSION['import_file']) || !file_exists($_SESSION['import_file'])) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Import file not found',
                    'new_token' => $_SESSION['new_csrf_token'] ?? null
                ]);
                exit;
            }

            $filePath = $_SESSION['import_file'];
            $userId = $_SESSION['user']['id'] ?? null;

            // Start the import process in the background
            // This will run while the frontend polls for updates
            $result = $this->studentService->importStudentsFromExcelWithProgress($filePath, $importId, $userId);

            // Clean up
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            unset($_SESSION['import_file']);
            unset($_SESSION['import_id']);

            echo json_encode([
                'success' => true,
                'message' => 'Import completed successfully',
                'result' => $result,
                'new_token' => $_SESSION['new_csrf_token'] ?? null
            ]);
        } catch (Exception $e) {
            error_log("Import error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage(),
                'new_token' => $_SESSION['new_csrf_token'] ?? null
            ]);
        }
        exit;
    }



    public function downloadImportTemplate()
    {
        try {
            $templateData = [
                ['ID', 'LName', 'FName', 'Suffix', 'MI', 'Gender', 'Course_ID', 'Year_Level'],
                ['20250001', 'Doe', 'John', '', '', 'Male', '1', '1'],
                ['20250002', 'Smith', 'Jane', '', 'A.', 'Female', '1', '2'],
                ['20250003', 'Johnson', 'Bob', 'Jr', 'B.', 'Male', '2', '1']
            ];

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set data
            $sheet->fromArray($templateData, NULL, 'A1');

            // Style header
            $headerStyle = [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E6E6FA']
                ]
            ];
            $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);

            // Auto-size columns
            foreach (range('A', 'H') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            // Output file
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="student_import_template.xlsx"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;
        } catch (Exception $e) {
            error_log("Template download error: " . $e->getMessage());
            $_SESSION['error'] = 'Failed to download template.';
            redirectTo('/admin/students/import');
        }
    }



    public function getImportProgress()
    {
        header('Content-Type: application/json');

        $importId = $_GET['import_id'] ?? null;

        if (!$importId) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Import ID required'
            ]);
            exit;
        }

        try {
            // Fetch progress from database
            $progress = $this->importProgressService->getImportProgress($importId);

            if (!$progress) {
                echo json_encode([
                    'status' => 'not_found',
                    'total' => 0,
                    'processed' => 0,
                    'imported' => 0,
                    'errors' => [],
                    'percentage' => 0
                ]);
                exit;
            }

            // Parse errors JSON
            $errors = [];
            if (!empty($progress['errors'])) {
                $errors = json_decode($progress['errors'], true) ?? [];
            }

            // Calculate percentage
            $percentage = 0;
            if ($progress['total_rows'] > 0) {
                $percentage = round(($progress['processed_rows'] / $progress['total_rows']) * 100, 2);
            }

            echo json_encode([
                'status' => $progress['status'],
                'total' => (int)$progress['total_rows'],
                'processed' => (int)$progress['processed_rows'],
                'imported' => (int)$progress['imported_rows'],
                'current_row' => (int)$progress['current_row'],
                'errors' => $errors,
                'percentage' => $percentage,
                'updated_at' => $progress['updated_at']
            ]);
        } catch (Exception $e) {
            error_log("Error fetching import progress: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to fetch progress'
            ]);
        }
        exit;
    }
}
