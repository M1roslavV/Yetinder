-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: yetinder
-- ------------------------------------------------------
-- Server version	8.0.36

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `yeti`
--

DROP TABLE IF EXISTS `yeti`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `yeti` (
  `id` int NOT NULL AUTO_INCREMENT,
  `vyska` decimal(5,2) NOT NULL,
  `vaha` decimal(5,2) NOT NULL,
  `username` varchar(255) NOT NULL,
  `specialni_schopnost` varchar(255) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `yeti_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `yeti`
--

LOCK TABLES `yeti` WRITE;
/*!40000 ALTER TABLE `yeti` DISABLE KEYS */;
INSERT INTO `yeti` VALUES (1,250.00,300.00,'Yeti1',NULL,'/uploads/67f073b86bcdd.jpg',1),(2,245.00,320.00,'Yeti2',NULL,'/uploads/67f073b86bcdd.jpg',1),(3,260.00,310.00,'Yeti3',NULL,'/uploads/67f073b86bcdd.jpg',1),(4,255.00,305.00,'Yeti4',NULL,'/uploads/67f073b86bcdd.jpg',1),(5,240.00,295.00,'Yeti5',NULL,'img5.png',1),(6,265.00,330.00,'Yeti6',NULL,'img6.png',1),(7,250.00,310.00,'Yeti7',NULL,'img7.png',1),(8,248.00,290.00,'Yeti8',NULL,'img8.png',1),(9,255.00,300.00,'Yeti9',NULL,'/uploads/67f073b86bcdd.jpg',NULL),(10,260.00,320.00,'Yeti10',NULL,'/uploads/67f073b86bcdd.jpg',NULL),(11,100.00,10.00,'Yetis','rychlost','/uploads/67f071cbee83c.jpg',NULL),(12,100.00,10.00,'Yetis','rychlost','/uploads/67f073b86bcdd.jpg',NULL);
/*!40000 ALTER TABLE `yeti` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-05 14:04:10
