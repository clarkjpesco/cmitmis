<?php

declare(strict_types=1);

use Framework\{TemplateEngine, Database, Container};
use App\Config\Paths;
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
    ImportProgressService,
    StudentPortalService
};

return [
    TemplateEngine::class => fn() => new TemplateEngine(Paths::VIEW),
    ValidatorService::class => fn() => new ValidatorService(),
    Database::class => fn() => new Database($_ENV['DB_DRIVER'], [
        'host' => $_ENV['DB_HOST'],
        'port' => $_ENV['DB_PORT'],
        'dbname' => $_ENV['DB_NAME']
    ], $_ENV['DB_USER'], $_ENV['DB_PASS']),
    UserService::class => function (Container $container) {
        $db = $container->get(Database::class);

        return new UserService($db);
    },
    StudentService::class => function (Container $container) {
        $db = $container->get(Database::class);

        return new StudentService($db);
    },
    CourseService::class => function (Container $container) {
        $db = $container->get(Database::class);

        return new CourseService($db);
    },
    SubjectService::class => function (Container $container) {
        $db = $container->get(Database::class);

        return new SubjectService($db);
    },
    ClassesService::class => function (Container $container) {
        $db = $container->get(Database::class);

        return new ClassesService($db);
    },
    EnrollmentService::class => function (Container $container) {
        $db = $container->get(Database::class);

        return new EnrollmentService($db);
    },
    GradeService::class => function (Container $container) {
        $db = $container->get(Database::class);

        return new GradeService($db);
    },
    DashboardService::class => function (Container $container) {
        $db = $container->get(Database::class);

        return new DashboardService($db);
    },
    ImportProgressService::class => function (Container $container) {
        $db = $container->get(Database::class);

        return new ImportProgressService($db);
    },
    StudentPortalService::class => function (Container $container) {
        $db = $container->get(Database::class);

        return new StudentPortalService($db);
    }

];
