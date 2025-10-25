<?php

declare(strict_types=1);

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Set session save path for Render's ephemeral storage
session_save_path('/tmp/sessions');


include __DIR__ . '/../src/App/functions.php';

$app = include __DIR__ . "/../src/App/bootstrap.php";

$app->run();
