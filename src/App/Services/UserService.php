<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidationException;

class UserService
{

    public function __construct(private Database $db) {}
    public function isEmailTaken(string $email)
    {

        $emailCount = $this->db->query("SELECT COUNT(*) FROM users WHERE email=:email", [
            'email' => $email
        ])->count();

        if ($emailCount > 0) {
            throw new ValidationException(['email' => ['Email already taken']]);
        }
    }
    public function isUserNameTaken(string $username)
    {

        $userNameCount = $this->db->query("SELECT COUNT(*) FROM users WHERE username=:username", [
            'username' => $username
        ])->count();

        if ($userNameCount > 0) {
            throw new ValidationException(['username' => ['Username already taken']]);
        }
    }
    public function create(array $formData)
    {
        $password = password_hash($formData['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        $this->db->query(
            "INSERT INTO users
                (username, password, email, full_name, address, phone)
             VALUES 
                (:username,:password,:email,:fullName,:address,:phone)
             ",
            [
                'username' => $formData['username'],
                'password' => $password,
                'email' => $formData['email'],
                'fullName' => $formData['fullName'],
                'address' => $formData['address'],
                'phone' => $formData['phone']

            ]
        );

        session_regenerate_id();
        $_SESSION['user'] = [
            'id' => $this->db->id(),
            'username' => $formData['username'],
            'email' => $formData['email'],
            'full_name' => $formData['fullName'],
            'user_type' => $formData['userType'] ?? 'customer',
            'login_time' => time()
        ];
    }

    public function login(array $formData)
    {
        $user = $this->db->query(
            "SELECT * FROM users WHERE username = :username",
            [
                'username' => $formData['username']

            ]
        )->find();

        $passwordsMatch = password_verify($formData['password'], $user['password'] ?? '');

        if (!$user || !$passwordsMatch) {
            throw new ValidationException(['password' => ['Invalid credentials']]);
        }

        session_regenerate_id();

        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'full_name' => $user['full_name'],
            'role' => $user['role'],
            'login_time' => time()
        ];
    }

    public function logout()
    {
        unset($_SESSION['user']);
        session_regenerate_id();
    }
}
