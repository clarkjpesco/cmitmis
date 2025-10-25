<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidationException;
use Exception;


class StudentService
{
    private Database $progressDb;

    public function __construct(private Database $db)
    {
        // Create a separate connection for progress updates
        $host = $_ENV['MYSQLHOST'] ?? $_ENV['DB_HOST'] ?? 'localhost';
        $port = $_ENV['MYSQLPORT'] ?? $_ENV['DB_PORT'] ?? '3306';
        $database = $_ENV['MYSQLDATABASE'] ?? $_ENV['DB_NAME'] ?? 'cmitmis';
        $username = $_ENV['MYSQLUSER'] ?? $_ENV['DB_USER'] ?? 'root';
        $password = $_ENV['MYSQLPASSWORD'] ?? $_ENV['DB_PASS'] ?? '';

        $this->progressDb = new Database(
            $_ENV['DB_DRIVER'] ?? 'mysql',
            [
                'host' => $host,
                'port' => $port,
                'dbname' => $database
            ],
            $username,
            $password
        );
    }

    public function createStudent(array $formData)
    {
        try {
            $this->db->beginTransaction();
            $this->createStudentWithoutTransaction($formData);
            $this->db->commit();
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    /**
     * Create student without starting a new transaction
     * (assumes transaction is already started by caller)
     */
    private function createStudentWithoutTransaction(array $formData)
    {
        $password = password_hash($formData['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        $role = 'student';

        $this->db->query(
            "INSERT INTO users
            (username, password, full_name, role)
             VALUES 
            (:username,:password,:full_name,:role)",
            [
                'username' => $formData['username'],
                'password' => $password,
                'full_name' => $formData['full_name'],
                'role' => $role
            ]
        );

        $user_id = $this->db->id();

        $this->db->query(
            "INSERT INTO students
            (user_id, student_number, course_id, year_level)
             VALUES 
            (:user_id, :student_number, :course, :year_level)",
            [
                'user_id' => $user_id,
                'student_number' => $formData['student_number'],
                'course' => $formData['course'],
                'year_level' => $formData['year_level']
            ]
        );
    }

    public function getStudents()
    {
        $students = $this->db->query(
            "SELECT s.id, s.student_number, c.name as course, c.code as course_code, s.year_level, u.full_name
             FROM students s
             JOIN users u ON s.user_id = u.id
             JOIN courses c ON s.course_id = c.id
             ORDER BY u.full_name"
        )->findAll();

        return $students;
    }

    public function getStudentDetails($studentId)
    {
        $student = $this->db->query(
            "SELECT s.*,c.name as course, u.full_name, u.username
             FROM students s
             JOIN users u ON s.user_id = u.id
             JOIN courses c ON s.course_id = c.id
             WHERE s.id = :student_id",
            ['student_id' => $studentId]
        )->find();

        return $student;
    }

    public function getEnrollmentSummary($studentId, $currentSemester)
    {
        $summary = $this->db->query(
            "SELECT 
                COUNT(DISTINCT CASE 
                    WHEN sc.semester = :semester AND sc.school_year = :school_year 
                    THEN e.id END) as current_subjects,
                SUM(CASE 
                    WHEN sc.semester = :semester AND sc.school_year = :school_year 
                    THEN sub.units ELSE 0 END) as current_units,
                COUNT(DISTINCT e.id) as total_subjects,
                AVG(g.grade) as average_grade
             FROM students s
             LEFT JOIN enrollments e ON s.id = e.student_id
             LEFT JOIN schedules sc ON e.schedule_id = sc.id
             LEFT JOIN subjects sub ON sc.subject_id = sub.id
             LEFT JOIN grades g ON e.id = g.enrollment_id
             WHERE s.id = :student_id",
            [
                'student_id' => $studentId,
                'semester' => $currentSemester['semester'] ?? '1st',
                'school_year' => $currentSemester['school_year'] ?? date('Y') . '-' . (date('Y') + 1)
            ]
        )->find();

        return $summary;
    }

    public function getAllStudents(int $length, int $offset, ?string $searchTerm = null, ?string $course = null, ?string $yearLevel = null, ?string $status = null)
    {
        $searchTerm = addcslashes($searchTerm ?? '', '%_');

        // Build the WHERE clause dynamically
        $whereConditions = [];
        $params = [];

        if (!empty($searchTerm)) {
            $whereConditions[] = "u.full_name LIKE :full_name OR s.student_number LIKE :student_number";
            $params['full_name'] = "%{$searchTerm}%";
            $params['student_number'] = "%{$searchTerm}%";
        }

        if (!empty($course)) {
            $whereConditions[] = "c.code=:code";
            $params['code'] = $course;
        }

        if (!empty($yearLevel)) {
            $whereConditions[] = "s.year_level=:year_level";
            $params['year_level'] = (int)$yearLevel;
        }

        if (!empty($status)) {
            $whereConditions[] = "s.status=:status";
            $params['status'] = $status;
        }

        // Combine conditions with AND
        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

        // Query to get users
        $students = $this->db->query(
            "SELECT 
                s.*,
                c.code as course,
                u.full_name,
                u.created_at, 
                DATE_FORMAT(u.created_at, '%b %e, %Y') AS formatted_date 
            FROM students s 
            LEFT JOIN users u ON s.user_id = u.id
            LEFT JOIN courses c ON s.course_id = c.id
            {$whereClause} 
            LIMIT {$length} OFFSET {$offset}",
            $params
        )->findAll();

        // Query to get count
        $studentCount = $this->db->query(
            "SELECT COUNT(*)
            FROM students s
            LEFT JOIN users u ON s.user_id = u.id
            LEFT JOIN courses c ON s.course_id = c.id
             {$whereClause}",
            $params
        )->count();

        return [$students, $studentCount];
    }

    public function importStudentsFromExcel($filePath)
    {
        // Increase time limit for Excel processing
        set_time_limit(300); // 5 minutes
        ini_set('memory_limit', '512M'); // Increase memory limit

        try {
            require_once __DIR__ . '/../../../vendor/autoload.php';

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();

            // Remove header row
            $headers = array_shift($data);

            $importedCount = 0;
            $errors = [];

            foreach ($data as $rowIndex => $row) {
                try {
                    // Skip empty rows
                    if (empty(array_filter($row))) {
                        continue;
                    }

                    // Map Excel data to form data
                    $formData = $this->mapExcelRowToStudentData($row, $headers);

                    // Validate required fields
                    if ($this->validateImportData($formData)) {
                        $this->createStudent($formData);
                        $importedCount++;
                    } else {
                        $errors[] = "Row " . ($rowIndex + 2) . ": Missing required fields";
                    }
                } catch (Exception $e) {
                    $errors[] = "Row " . ($rowIndex + 2) . ": " . $e->getMessage();
                }
            }

            return [
                'success' => true,
                'imported' => $importedCount,
                'total' => count($data),
                'errors' => $errors
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'File processing failed: ' . $e->getMessage()
            ];
        }
    }

    public function importStudentsFromExcelWithProgress($filePath, $importId, $userId = null)
    {
        set_time_limit(300); // 5 minutes
        ini_set('memory_limit', '512M');

        try {
            // Initialize progress in database using separate connection
            $this->progressDb->query(
                "INSERT INTO import_progress (id, user_id, status, created_at) 
                 VALUES (:id, :user_id, 'starting', NOW())",
                [
                    'id' => $importId,
                    'user_id' => $userId
                ]
            );

            // Load the Excel file
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($filePath);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($filePath);

            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();

            // Remove header row
            $headers = array_shift($data);
            $totalRows = count($data);

            // Update total count using separate connection
            $this->progressDb->query(
                "UPDATE import_progress 
                 SET total_rows = :total, status = 'processing', updated_at = NOW()
                 WHERE id = :id",
                [
                    'total' => $totalRows,
                    'id' => $importId
                ]
            );

            $importedCount = 0;
            $errors = [];
            $batchSize = 10; // Process in batches to commit periodically

            // Process each row
            foreach ($data as $rowIndex => $row) {
                $currentRow = $rowIndex + 2;

                // Start transaction for batch
                if ($rowIndex % $batchSize === 0) {
                    $this->db->beginTransaction();
                }

                try {
                    // Skip empty rows
                    if (empty(array_filter($row))) {
                        continue;
                    }

                    // Map Excel row to student data
                    $formData = $this->mapExcelRowToStudentData($row, $headers);

                    // Validate and import
                    if ($this->validateImportData($formData)) {
                        $this->createStudentWithoutTransaction($formData);
                        $importedCount++;
                    } else {
                        $errorMsg = "Row {$currentRow}: Missing required fields";
                        $errors[] = $errorMsg;
                    }
                } catch (Exception $e) {
                    $errorMsg = "Row {$currentRow}: " . $e->getMessage();
                    $errors[] = $errorMsg;
                }

                // Commit batch and update progress
                if (($rowIndex + 1) % $batchSize === 0 || ($rowIndex + 1) === count($data)) {
                    if ($this->db->inTransaction()) {
                        $this->db->commit();
                    }

                    // Update progress after each batch (using separate connection)
                    $this->progressDb->query(
                        "UPDATE import_progress 
                         SET processed_rows = :processed, 
                             imported_rows = :imported,
                             current_row = :current_row,
                             errors = :errors,
                             updated_at = NOW()
                         WHERE id = :id",
                        [
                            'processed' => $rowIndex + 1,
                            'imported' => $importedCount,
                            'current_row' => $currentRow,
                            'errors' => json_encode($errors),
                            'id' => $importId
                        ]
                    );

                    // Optional: Small delay to prevent overwhelming the database
                    usleep(10000); // 0.01 second
                }
            }

            // Update final status using separate connection
            $this->progressDb->query(
                "UPDATE import_progress 
                 SET status = 'completed',
                     processed_rows = :processed,
                     imported_rows = :imported,
                     errors = :errors,
                     updated_at = NOW()
                 WHERE id = :id",
                [
                    'processed' => $totalRows,
                    'imported' => $importedCount,
                    'errors' => json_encode($errors),
                    'id' => $importId
                ]
            );

            // Clean up memory
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);

            return [
                'success' => true,
                'imported' => $importedCount,
                'total' => $totalRows,
                'errors' => $errors
            ];
        } catch (Exception $e) {
            // Rollback any pending transaction
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            // Update error status using separate connection
            $this->progressDb->query(
                "UPDATE import_progress 
                 SET status = 'error', 
                     errors = :errors,
                     updated_at = NOW()
                 WHERE id = :id",
                [
                    'id' => $importId,
                    'errors' => json_encode(['Fatal error: ' . $e->getMessage()])
                ]
            );

            return [
                'success' => false,
                'error' => 'File processing failed: ' . $e->getMessage()
            ];
        }
    }

    private function mapExcelRowToStudentData($row, $headers)
    {
        // Create associative array from headers and row data
        $rowData = array_combine($headers, $row);

        // Generate password
        $password = $this->generatePassword(
            $rowData['LName'] ?? '',
            $rowData['ID'] ?? ''
        );

        // Generate full name
        $fullName = $this->generateFullName(
            $rowData['FName'] ?? '',
            $rowData['LName'] ?? '',
            $rowData['MI'] ?? '',
            $rowData['Suffix'] ?? ''
        );

        return [
            'student_number' => $rowData['ID'] ?? '',
            'full_name' => $fullName,
            'username' => $rowData['ID'] ?? '',
            'password' => $password,
            'course' => $rowData['Course_ID'] ?? '',
            'year_level' => $rowData['Year_Level'] ?? ''
        ];
    }

    private function generatePassword($lastName, $studentId)
    {
        // Clean names and create password
        $cleanLast = preg_replace('/[^a-zA-Z]/', '', $lastName);
        $username = strtolower('tcm' . $cleanLast . $studentId);
        return $username;
    }

    private function generateFullName($firstName, $lastName, $middleInitial, $suffix)
    {
        $fullName = $firstName;

        if (!empty($middleInitial)) {
            $fullName .= ' ' . $middleInitial;
        }

        $fullName .= ' ' . $lastName;

        if (!empty($suffix)) {
            $fullName .= ' ' . $suffix;
        }

        return trim($fullName);
    }

    private function validateImportData($formData)
    {
        $requiredFields = ['username', 'password', 'full_name', 'student_number', 'course', 'year_level'];

        foreach ($requiredFields as $field) {
            if (empty($formData[$field])) {
                return false;
            }
        }

        return true;
    }
}
