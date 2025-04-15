<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class YetiRepository
{
    private Connection $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }
    public function createYeti(string $username, int $weight, int $height, string $specialAbility, string $img, int $user_id): void
    {
        try {
            $this->db->insert('yeti', [
                'vyska' => $height,
                'vaha' => $weight,
                'username' => $username,
                'specialni_schopnost' => $specialAbility,
                'img' => $img,
                'user_id' => $user_id

            ]);
        } catch (Exception $e) {
            error_log('Database insert error: ' . $e->getMessage());
        }
    }

    public function findByUsername(string $username) : ?array
    {
        try{
            $result = $this->db->fetchAssociative("SELECT * FROM yeti WHERE username = :username LIMIT 1", ["username" => $username]);

            if ($result === false) {
                return [];
            }

            return $result;

        }catch(Exception $e){
            error_log('Database insert error: ' . $e->getMessage());
            return null;
        }
    }

    public function findById(string $Id) : ?array
    {
        try{
            $result = $this->db->fetchAssociative("SELECT * FROM yeti WHERE id = :id", ["id" => $Id]);

            if ($result === false) {
                return [];
            }

            return $result;

        }catch(Exception $e){
            error_log('Database insert error: ' . $e->getMessage());
            return null;
        }
    }

    public function findByUserId(string $userId) : ?array
    {
        $sql = "SELECT * FROM yeti WHERE user_id = :userId";
        try {
            return $this->db->fetchAllAssociative($sql, ["userId" => $userId]);
        }catch(Exception $e){
            error_log('Database insert error: ' . $e->getMessage());
            return null;
        }
    }


    public function findAllYeti() : ?array
    {
        $sql = "SELECT * 
                FROM yeti";

        try {
            return $this->db->fetchAllAssociative($sql);
        }catch(Exception $e){
            error_log('Database insert error: ' . $e->getMessage());
            return null;
        }
    }


    public function selectTop10() : ?array
    {
        $sql = "SELECT 
                y.id,
                y.username,
                y.vyska,
                y.vaha,
                b.mesto,
                b.ulice,
                y.img,
                ROUND(AVG(h.hodnota), 2) AS prumerne_hodnoceni
                FROM yeti y
                LEFT JOIN hodnoceni h ON y.id = h.yeti_id
                RIGHT JOIN bydliste b ON y.id = b.yeti_id
                GROUP BY y.id, y.username, y.vyska, y.vaha, b.mesto, b.ulice
                ORDER BY prumerne_hodnoceni DESC
                LIMIT 10;";

        try {
            return $this->db->fetchAllAssociative($sql);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }
}
