<?php

declare(strict_types=1);

require __DIR__ . "/../../vendor/autoload.php";

use Framework\App;
use App\Config\Paths;
use Dotenv\Dotenv;
use function App\Config\{registerRoutes, registerMiddleware};

// Load environment variables - with fallback for Render
try {
    $dotenv = Dotenv::createImmutable(Paths::ROOT);
    $dotenv->load();
} catch (Exception $e) {
    // If .env doesn't exist, rely on environment variables (for Render)
    error_log('Dotenv load failed: ' . $e->getMessage());
}

$app = new App(Paths::SOURCE . "App/container-definitions.php");

registerRoutes($app);
registerMiddleware($app);

return $app;
