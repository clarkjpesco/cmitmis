<?php
// Router for PHP built-in server

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . '/public' . $path;

// Check if it's a static file
if (is_file($file)) {
    // Get file extension
    $ext = pathinfo($file, PATHINFO_EXTENSION);

    // Set appropriate headers based on file type
    $mimeTypes = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'eot' => 'application/vnd.ms-fontobject',
    ];

    if (isset($mimeTypes[$ext])) {
        header('Content-Type: ' . $mimeTypes[$ext]);

        // Set cache headers for static assets
        if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'svg', 'ico', 'woff', 'woff2', 'ttf', 'eot'])) {
            // Cache images and fonts for 1 week
            header('Cache-Control: max-age=604800, public');
        } elseif (in_array($ext, ['css', 'js'])) {
            // Cache CSS/JS for 1 day
            header('Cache-Control: max-age=86400, public');
        }

        readfile($file);
        return true;
    }
}

// Not a static file, route to index.php
require __DIR__ . '/public/index.php';
