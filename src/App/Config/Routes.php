<?php

declare(strict_types=1);

namespace App\Config;

use Framework\App;
use App\Controllers\{
    HomeController,
    AuthController,
    AdminController,
    StudentController
};
use App\Middleware\{
    AuthRequiredMiddleware,
    GuestOnlyMiddleware,
    AdminOnlyMiddleware,
    StudentOnlyMiddleware
};

function registerRoutes(App $app)
{
    $app->get('/', [HomeController::class, 'home']);

    $app->get('/login', [AuthController::class, 'loginView'])->add(GuestOnlyMiddleware::class);
    $app->post('/login', [AuthController::class, 'login'])->add(GuestOnlyMiddleware::class);

    $app->get('/logout', [AuthController::class, 'logout'])->add(AuthRequiredMiddleware::class);

    //admin
    $app->get('/admin/dashboard', [AdminController::class, 'dashboard'])->add(AdminOnlyMiddleware::class);

    $app->get('/admin/students', [AdminController::class, 'students'])->add(AdminOnlyMiddleware::class);
    $app->get('/admin/students/create', [AdminController::class, 'createStudentView'])->add(AdminOnlyMiddleware::class);
    $app->post('/admin/students/create', [AdminController::class, 'createStudent'])->add(AdminOnlyMiddleware::class);

    $app->get('/admin/subjects', [AdminController::class, 'subjects'])->add(AdminOnlyMiddleware::class);
    $app->get('/admin/subjects/create', [AdminController::class, 'createSubjectView'])->add(AdminOnlyMiddleware::class);
    $app->post('/admin/subjects/create', [AdminController::class, 'createSubject'])->add(AdminOnlyMiddleware::class);

    $app->get('/admin/classes', [AdminController::class, 'classes'])->add(AdminOnlyMiddleware::class);
    $app->get('/admin/classes/create', [AdminController::class, 'createClassesView'])->add(AdminOnlyMiddleware::class);
    $app->post('/admin/classes/create', [AdminController::class, 'createClasses'])->add(AdminOnlyMiddleware::class);
    $app->get('/admin/classes/edit', [AdminController::class, 'showEditScheduleForm'])->add(AdminOnlyMiddleware::class);
    $app->post('/admin/classes/update', [AdminController::class, 'updateSchedule'])->add(AdminOnlyMiddleware::class);
    $app->post('/admin/classes/delete', [AdminController::class, 'deleteSchedule'])->add(AdminOnlyMiddleware::class);

    //courses
    $app->get('/admin/courses', [AdminController::class, 'courses'])->add(AdminOnlyMiddleware::class);

    $app->get('/admin/courses/create', [AdminController::class, 'createCourseView'])->add(AdminOnlyMiddleware::class);
    $app->post('/admin/courses/create', [AdminController::class, 'createCourse'])->add(AdminOnlyMiddleware::class);

    $app->get('/admin/courses/{course}/edit', [AdminController::class, 'editCourseView'])->add(AdminOnlyMiddleware::class);
    $app->post('/admin/courses/{course}/edit', [AdminController::class, 'editCourse'])->add(AdminOnlyMiddleware::class);
    //end courses


    // API endpoints for subjects
    $app->get('/admin/api/subjects', [AdminController::class, 'getSubjects'])->add(AdminOnlyMiddleware::class);

    // Enrollment routes
    $app->get('/admin/enrollments', [AdminController::class, 'enrollments'])->add(AdminOnlyMiddleware::class);
    //  $app->get('/admin/enrollments/create', [AdminController::class, 'createEnrollmentView'])->add(AdminOnlyMiddleware::class);
    $app->post('/admin/enrollments/create', [AdminController::class, 'createEnrollment'])->add(AdminOnlyMiddleware::class);
    $app->get('/admin/enrollments/student/{id}/details', [AdminController::class, 'studentEnrollmentsView'])->add(AdminOnlyMiddleware::class);
    $app->get('/admin/enrollments/create/{id}', [AdminController::class, 'createEnrollmentView'])->add(AdminOnlyMiddleware::class);


    // API endpoints for enrollments
    $app->get('/admin/api/students', [AdminController::class, 'getStudents'])->add(AdminOnlyMiddleware::class);
    $app->get('/admin/api/student-info', [AdminController::class, 'getStudentInfo'])->add(AdminOnlyMiddleware::class);
    $app->get('/admin/api/schedules', [AdminController::class, 'getAvailableSchedules'])->add(AdminOnlyMiddleware::class);



    // Grade routes
    $app->get('/admin/grades', [AdminController::class, 'grades'])->add(AdminOnlyMiddleware::class);
    $app->get('/admin/grades/student/{id}/details', [AdminController::class, 'studentGradesView'])->add(AdminOnlyMiddleware::class);
    $app->get('/admin/grades/input/{id}', [AdminController::class, 'gradeInputView'])->add(AdminOnlyMiddleware::class);
    $app->post('/admin/grades/update', [AdminController::class, 'updateGrade'])->add(AdminOnlyMiddleware::class);

    // API endpoint for grades
    $app->get('/admin/api/grades-by-semester', [AdminController::class, 'getGradesBySemester'])->add(AdminOnlyMiddleware::class);
    $app->get('/admin/settings', [AdminController::class, 'settings'])->add(AdminOnlyMiddleware::class);



    // Excel Import routes
    $app->get('/admin/students/import', [AdminController::class, 'importStudentsView'])->add(AdminOnlyMiddleware::class);
    $app->post('/admin/students/import', [AdminController::class, 'importStudents'])->add(AdminOnlyMiddleware::class);
    $app->get('/admin/students/import/template', [AdminController::class, 'downloadImportTemplate'])->add(AdminOnlyMiddleware::class);



    $app->get('/admin/students/import/progress', [AdminController::class, 'getImportProgress']);
    $app->get('/admin/students/import/progress-page', [AdminController::class, 'importProgressPage']);

    // NEW: Start import endpoint
    $app->post('/admin/students/import/start', [AdminController::class, 'startImport'])->add(AdminOnlyMiddleware::class);



    // Student Portal Routes
    $app->get('/student/dashboard', [StudentController::class, 'dashboard'])->add(StudentOnlyMiddleware::class);

    $app->get('/student/schedule', [StudentController::class, 'schedule'])->add(StudentOnlyMiddleware::class);

    $app->get('/student/enrollments', [StudentController::class, 'enrollments'])->add(StudentOnlyMiddleware::class);

    $app->get('/student/grades', [StudentController::class, 'grades'])->add(StudentOnlyMiddleware::class);

    $app->get('/student/profile', [StudentController::class, 'profile'])->add(StudentOnlyMiddleware::class);
}
