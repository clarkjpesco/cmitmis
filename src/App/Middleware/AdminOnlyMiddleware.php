<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;

class AdminOnlyMiddleware implements MiddlewareInterface
{
    public function process(callable $next)
    {
        if (empty($_SESSION['user'])) {
            redirectTo('/login');
        }

        if ($_SESSION['user']['role'] !== 'admin') {
            // Alternatively, redirect to their respective dashboard
            // redirectTo('/dashboard');
            redirectTo('/');
        }

        $next();
    }
}
