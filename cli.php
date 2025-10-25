<?php

// Add error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . "/vendor/autoload.php";

use Framework\Database;
use Dotenv\Dotenv;
use App\Config\Paths;

// Load environment variables with error handling
try {
    $dotenv = Dotenv::createImmutable(Paths::ROOT);
    $dotenv->load();
} catch (Exception $e) {
    // Continue with environment variables
    error_log('Dotenv warning: ' . $e->getMessage());
}



try {
    // Check if required database environment variables are set
    $requiredEnvVars = ['DB_DRIVER', 'DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASS'];
    foreach ($requiredEnvVars as $var) {
        if (empty($_ENV[$var]) && empty(getenv($var))) {
            throw new Exception("Required environment variable {$var} is not set");
        }
    }

    // Use getenv as fallback
    $dbDriver = $_ENV['DB_DRIVER'] ?? getenv('DB_DRIVER');
    $dbHost = $_ENV['DB_HOST'] ?? getenv('DB_HOST');
    $dbPort = $_ENV['DB_PORT'] ?? getenv('DB_PORT');
    $dbName = $_ENV['DB_NAME'] ?? getenv('DB_NAME');
    $dbUser = $_ENV['DB_USER'] ?? getenv('DB_USER');
    $dbPass = $_ENV['DB_PASS'] ?? getenv('DB_PASS');

    $db = new Database($dbDriver, [
        'host' => $dbHost,
        'port' => $dbPort,
        'dbname' => $dbName
    ], $dbUser, $dbPass);

    // Execute the main database schema
    $sqlFile = file_get_contents("./database.sql");
    if ($sqlFile === false) {
        throw new Exception("Could not read database.sql file");
    }

    // Split the SQL file into individual statements
    $sqlStatements = array_filter(array_map('trim', explode(';', $sqlFile)));

    foreach ($sqlStatements as $statement) {
        if (!empty($statement)) {
            $db->query($statement);
        }
    }


    // Insert paper sizes (using single statements to avoid multiple statement issues)
    $courses = [
        ['bsit', 'Bachelor of Science in Information Technology', 'Prepares student to be IT professionals who are able to perform installation, operation, development, maintenance, and administration of computer applications'],
        ['bsn', 'Bachelor of Science in Nursing', 'Includes the assessment of patient health problems, developing and implementing nursing care plans, and maintaining medical records. They also administer nursing care to the ill, injured, convalescent, or disabled patients'],
        ['laed', 'Liberal Arts in Education', "Refers to college studies that provide general knowledge and develop intellectual ability. This type of education can prepare you for many fields in today's workplace."],
        ['bsba', 'Bachelor of Science in Business Administration', "Degree program in General Management is a 120-credit program designed for students interested in having exposure to a number of business areas rather than focusing in depth on just one."],
        ['bsa', 'Bachelor of Science in Accountancy', "Is composed of subjects in accounting ( financial, public, managerial), audit, administration, business laws and taxation."],
        ['fpst', 'Food Preparation & Service Technology', "Is a 2 years degree program which deals with the scientific preparation, processing and distribution of foods. It is also concerned with the improvement of food products’ flavor, appearance, storage qualities as well as in the control of quality changes during processing, marketing and distribution."]

    ];

    foreach ($courses as $course) {
        $db->query("INSERT INTO courses(code,name,description) VALUES (?,?,?)", $course);
    }

    // Create admin user
    $hashedPassword = password_hash("admin123", PASSWORD_DEFAULT);
    // Check if admin user already exists
    $existingAdmin = $db->query("SELECT id FROM users WHERE username = 'admin'")->find();

    if (!$existingAdmin) {
        $db->query(
            "INSERT INTO users (username, password, role,full_name) VALUES (?,?, ?, ?)",
            ['admin', $hashedPassword,  'admin', 'System Administrator']
        );
        echo "Admin user created successfully!\n";
    } else {
        echo "Admin user already exists.\n";
    }

    echo "Database setup completed successfully!\n";
    echo "Default data inserted:\n";
    echo "- courses: bsit, bsn, laed,bsba, bsa, fpst\n";
} catch (Exception $e) {
    error_log("Setup failed: " . $e->getMessage());
    die("Setup failed: " . $e->getMessage());
}
