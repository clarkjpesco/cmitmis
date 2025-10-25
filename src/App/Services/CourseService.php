<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use InvalidArgumentException;
use Exception;

class CourseService
{
    public function __construct(private Database $db) {}

    public function getCourses()
    {
        $courses = $this->db->query("SELECT id,code,name FROM courses where is_active=1")->findAll();

        return $courses;
    }

    public function getAllCourses()
    {
        $searchTerm = addcslashes($_GET['s'] ?? '', '%_');
        $params =    [
            'name' => "%{$searchTerm}%",
            'code' => "%{$searchTerm}%"

        ];

        $courses = $this->db->query("
        SELECT * 
        FROM courses        
        WHERE name LIKE :name OR code LIKE :code    
        ", $params)->findAll();
        return $courses;
    }

    public function getCourse(string $id)
    {
        return $this->db->query(
            "SELECT * FROM courses WHERE id =:id ",
            [
                'id' => $id,

            ]
        )->find();
    }


    public function createCourse(array $formData)
    {

        $this->db->query(
            "INSERT INTO courses
                (code,name,description)
             VALUES 
                (:code,:name,:description)
             ",
            [
                'code' => $formData['courseCode'],
                'name' => $formData['courseName'],
                'description' => $formData['courseDescription']
            ]
        );
    }

    public function updateCourse(array $formData, int $id)
    {


        $this->db->query(
            "UPDATE courses SET 
            code=:code, 
            name=:name,
            description=:description,
            is_active=:is_active 
            WHERE id =:id
             ",
            [
                'code' => $formData['courseCode'],
                'name' => $formData['courseName'],
                'description' => $formData['courseDescription'],
                'is_active' => $formData['courseActive'],
                'id' => $id
            ]
        );
    }
}
