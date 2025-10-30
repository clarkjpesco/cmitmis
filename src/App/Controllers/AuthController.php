<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{ValidatorService, UserService};

class AuthController
{


    public function __construct(
        private TemplateEngine $view,
        private ValidatorService $validatorService,
        private UserService $userService

    ) {}

    public function loginView()
    {
        echo $this->view->render('login.php');
    }
    public function login()
    {
        $this->validatorService->validateLogin($_POST);
        $this->userService->login($_POST);
        $role = $_SESSION['user']['role'] ?? 'student';
        $this->redirectToDashboard($role);
    }

    private function redirectToDashboard(string $role): void
    {
        $dashboardRoutes = [
            'admin' => '/admin/dashboard',
            'student' => '/student/dashboard'
        ];

        $redirectTo = $dashboardRoutes[$role] ?? '/dashboard';
        redirectTo($redirectTo);
    }

    public function logout()
    {
        $this->userService->logout();
        redirectTo('/login');
    }
}
