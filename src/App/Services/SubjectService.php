<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidationException;
use Exception;

class SubjectService
{

    public function __construct(private Database $db) {}

    public function createSubject(array $formData)
    {
        $this->db->query(
            "INSERT INTO subjects
                (code, name, description, units)
             VALUES 
                (:code, :name, :description, :units)
             ",
            [
                'code' => $formData['subject_code'],
                'name' => $formData['subject_name'],
                'description' => $formData['description'],
                'units' => $formData['units'],

            ]
        );
    }

    public function getSubjects(int $length, int $offset, ?string $searchTerm = null, ?string $units = null,  ?string $status = null)
    {

        $searchTerm = addcslashes($searchTerm ?? '', '%_');

        // Build the WHERE clause dynamically
        $whereConditions = [];
        $params = [];


        if (!empty($searchTerm)) {
            $whereConditions[] = "name LIKE :name OR code LIKE :code";
            $params['name'] = "%{$searchTerm}%";
            $params['code'] = "%{$searchTerm}%";
        }


        if (!empty($units)) {
            $whereConditions[] = "units=:units";
            $params['units'] = $units;
        }

        if (!empty($status)) {
            $whereConditions[] = "status=:status";
            $params['status'] = $status;
        }

        // Combine conditions with AND
        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

        // Query to get users
        $subjects = $this->db->query(
            "SELECT *
        FROM subjects 
        {$whereClause} 
        LIMIT {$length} OFFSET {$offset}",
            $params
        )->findAll();


        // Query to get count
        $subjectCount = $this->db->query(
            "SELECT COUNT(*)
        FROM subjects
         {$whereClause}",
            $params
        )->count();

        return [$subjects, $subjectCount];
    }

    public function getAllSubjects()
    {
        $subjects = $this->db->query(
            "SELECT id, code, name, units
         FROM subjects
         ORDER BY code"
        )->findAll();
        return $subjects;
    }
}
