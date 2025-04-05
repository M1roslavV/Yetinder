<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class UserRepository
{
    private Connection $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }


    public function findByEmail(string $email): ?array
    {
        try {
            return $this->db->fetchAssociative("SELECT * FROM user WHERE email = :email", ["email" => $email]);
        } catch (Exception $e) {
            error_log('Database insert error: ' . $e->getMessage());
            return null;
        }
    }

    public function createUser(string $email, string $hashedPassword): void
    {
        try {
            $this->db->insert('user', [
                'email' => $email,
                'password' => $hashedPassword
            ]);
        } catch (Exception $e) {
            error_log('Database insert error: ' . $e->getMessage());
        }
    }
}

