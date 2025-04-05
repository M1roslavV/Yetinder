<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class ScoreRepository
{
    private Connection $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function getAverageRatingByDay($userId): ?array
    {
        $sql = "WITH RECURSIVE days AS (
                SELECT CURRENT_DATE() AS day
                UNION ALL
                SELECT day - INTERVAL 1 DAY 
                FROM days 
                WHERE day > CURRENT_DATE() - INTERVAL 30 DAY 
                )
                SELECT 
                    d.day, 
                    ROUND(AVG(h.hodnota), 2) AS avg_rating
                FROM days d
                LEFT JOIN hodnoceni h 
                    ON DATE(h.create_at) = d.day ";

        if ($userId!==null) {
            $sql .= " AND h.user_id = :userId ";
        }
            $sql .= "GROUP BY d.day
                ORDER BY d.day ASC;";



        try {
            if ($userId!==null) {
                return $this->db->fetchAllAssociative($sql, ['userId' => $userId]);
            } else {
                return $this->db->fetchAllAssociative($sql);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function getAverageRatingByMonth($userId): ?array
    {
        $sql = "WITH RECURSIVE months AS (
                SELECT DATE_FORMAT(CURRENT_DATE, '%Y-%m-01') AS month
                UNION ALL
                SELECT month - INTERVAL 1 MONTH 
                FROM months 
                WHERE month > CURRENT_DATE - INTERVAL 11 MONTH
                )
                SELECT 
                    m.month, 
                    ROUND(AVG(h.hodnota), 2) AS avg_rating
                FROM months m
                LEFT JOIN hodnoceni h 
                    ON DATE_FORMAT(h.create_at, '%Y-%m-01') = m.month ";

        if ($userId!==null) {
            $sql .= " AND h.user_id = :userId ";
        }
            $sql .= "GROUP BY m.month
                    ORDER BY m.month ASC;";

        try {
            if ($userId!==null) {
                return $this->db->fetchAllAssociative($sql, ['userId' => $userId]);
            } else {
                return $this->db->fetchAllAssociative($sql);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function getAverageRatingByYear($userId): ?array
    {
        $sql = "WITH RECURSIVE years AS (
                SELECT DATE_FORMAT(CURRENT_DATE, '%Y-01-01') AS year 
                UNION ALL
                SELECT year - INTERVAL 1 YEAR 
                FROM years 
                WHERE year > CURRENT_DATE - INTERVAL 11 YEAR 
                )
                SELECT 
                    y.year, 
                    ROUND(AVG(h.hodnota), 2) AS avg_rating
                FROM years y
                LEFT JOIN hodnoceni h 
                    ON DATE_FORMAT(h.create_at, '%Y-01-01') = y.year ";

        if ($userId!==null) {
            $sql .= " AND h.user_id = :userId ";
        }
            $sql .= "GROUP BY y.year
                    ORDER BY y.year ASC;";

        try {
            if ($userId!==null) {
                return $this->db->fetchAllAssociative($sql, ['userId' => $userId]);
            } else {
                return $this->db->fetchAllAssociative($sql);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }


    public function avgRateByYetiId($yetiId): ?array
    {
        $sql = "SELECT ROUND(AVG(h.hodnota), 2) AS avg FROM hodnoceni h
                WHERE h.yeti_id = :yetiId";

        try {
            return $this->db->fetchAllAssociative($sql, ['yetiId' => $yetiId]);

        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function insert($score, $userId,$yetiId, $createAt): void
    {
        try {
            $this->db->insert('hodnoceni', [
                'hodnota' => $score,
                'user_id' => $userId,
                'yeti_id' => $yetiId,
                'create_at' => $createAt

            ]);
        } catch (Exception $e) {
            error_log('Database insert error: ' . $e->getMessage());
        }
    }
}
