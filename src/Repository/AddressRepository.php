<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class AddressRepository
{
    private Connection $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }
    public function createAddress(string $town, string $street, int $idYeti): void
    {
        try {
            $this->db->insert('bydliste', [
                'mesto' => $town,
                'ulice' => $street,
                'yeti_id' => $idYeti

            ]);
        } catch (Exception $e) {
            error_log('Database insert error: ' . $e->getMessage());
        }
    }

    public function findByYetiId($yetiId) : ?array
    {
        $sql = "SELECT * 
                FROM bydliste
                WHERE yeti_id = :yetiId;";

        try {
            $result = $this->db->fetchAssociative($sql, ["yetiId" => $yetiId]);
            return $result !== false ? $result : null;
        }catch(Exception $e){
            error_log('Database insert error: ' . $e->getMessage());
            return null;
        }
    }


}
