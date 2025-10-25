<?php

require __DIR__ . "/vendor/autoload.php";

use Framework\Database;
use Dotenv\Dotenv;
use App\Config\Paths;

// Load environment variables with error handling
try {
    $dotenv = Dotenv::createImmutable(Paths::ROOT);
    $dotenv->load();
} catch (Exception $e) {
    error_log('Dotenv warning: ' . $e->getMessage());
}

try {
    // Railway provides these variables when MySQL is linked
    $host = $_ENV['MYSQLHOST'] ?? $_ENV['DB_HOST'] ?? 'localhost';
    $port = $_ENV['MYSQLPORT'] ?? $_ENV['DB_PORT'] ?? '3306';
    $database = $_ENV['MYSQLDATABASE'] ?? $_ENV['DB_NAME'] ?? 'cmitmis';
    $username = $_ENV['MYSQLUSER'] ?? $_ENV['DB_USER'] ?? 'root';
    $password = $_ENV['MYSQLPASSWORD'] ?? $_ENV['DB_PASS'] ?? '';

    echo "Attempting database connection...\n";
    echo "Host: {$host}:{$port}\n";
    echo "Database: {$database}\n";
    echo "Username: {$username}\n";

    // Check if required variables are set
    if (empty($host) || empty($database) || empty($username)) {
        throw new Exception("Required database environment variables are not set");
    }

    $db = new Database($_ENV['DB_DRIVER'] ?? 'mysql', [
        'host' => $host,
        'port' => $port,
        'dbname' => $database
    ], $username, $password);

    echo "Database connection successful!\n";

    // Execute the main database schema
    $sqlFile = file_get_contents("./database.sql");
    if ($sqlFile === false) {
        throw new Exception("Could not read database.sql file");
    }

    echo "Creating database schema...\n";

    // Split the SQL file into individual statements
    $sqlStatements = array_filter(array_map('trim', explode(';', $sqlFile)));

    foreach ($sqlStatements as $statement) {
        if (!empty($statement)) {
            try {
                $db->query($statement);
            } catch (Exception $e) {
                // Ignore table already exists errors
                if (!str_contains($e->getMessage(), 'already exists')) {
                    throw $e;
                }
            }
        }
    }

    echo "Schema created successfully!\n";

    // Create admin user
    $hashedPassword = password_hash("admin123", PASSWORD_DEFAULT);
    $existingAdmin = $db->query("SELECT id FROM users WHERE username = 'admin'")->find();

    if (!$existingAdmin) {
        $db->query(
            "INSERT INTO users (username, password, role, full_name) VALUES (?,?,?,?)",
            ['admin', $hashedPassword, 'admin', 'System Administrator']
        );
        echo "Admin user created successfully!\n";
    } else {
        echo "Admin user already exists.\n";
    }


    $courses = [
        ['bsit', 'Bachelor of Science in Information Technology', 'Prepares student to be IT professionals who are able to perform installation, operation, development, maintenance, and administration of computer applications'],
        ['bsn', 'Bachelor of Science in Nursing', 'Includes the assessment of patient health problems, developing and implementing nursing care plans, and maintaining medical records. They also administer nursing care to the ill, injured, convalescent, or disabled patients'],
        ['laed', 'Liberal Arts in Education', "Refers to college studies that provide general knowledge and develop intellectual ability. This type of education can prepare you for many fields in today's workplace."],
        ['bsba', 'Bachelor of Science in Business Administration', "Degree program in General Management is a 120-credit program designed for students interested in having exposure to a number of business areas rather than focusing in depth on just one."],
        ['bsa', 'Bachelor of Science in Accountancy', "Is composed of subjects in accounting ( financial, public, managerial), audit, administration, business laws and taxation."],
        ['fpst', 'Food Preparation & Service Technology', "Is a 2 years degree program which deals with the scientific preparation, processing and distribution of foods. It is also concerned with the improvement of food products flavor, appearance, storage qualities as well as in the control of quality changes during processing, marketing and distribution."]

    ];

    echo "Inserting courses...\n";
    foreach ($courses as $course) {
        try {
            $db->query("INSERT INTO courses(code,name,description) VALUES (?,?,?)", $course);
        } catch (Exception $e) {
            // Ignore duplicate entry errors
            if (!str_contains($e->getMessage(), 'Duplicate entry')) {
                throw $e;
            }
        }
    }



    echo "\n=================================\n";
    echo "Database setup completed successfully!\n";
    echo "=================================\n";
    echo "Default data inserted:\n";
    echo "- Courses: bsit, bsn, laed, bsba, bsa, fpst\n";
    echo "- Admin user: username='admin', password='admin123'\n";
    echo "=================================\n";
} catch (Exception $e) {
    error_log("Setup failed: " . $e->getMessage());
    echo "\n=================================\n";
    echo "ERROR: Setup failed!\n";
    echo "=================================\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "\nPlease check:\n";
    echo "1. MySQL service is added to your Railway project\n";
    echo "2. Environment variables are correctly set\n";
    echo "3. Database credentials are valid\n";
    echo "=================================\n";
    exit(1);
}
