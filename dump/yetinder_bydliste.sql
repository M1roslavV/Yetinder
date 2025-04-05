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
-- Table structure for table `bydliste`
--

DROP TABLE IF EXISTS `bydliste`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bydliste` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mesto` varchar(100) NOT NULL,
  `ulice` varchar(255) NOT NULL,
  `yeti_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `yeti_id` (`yeti_id`),
  CONSTRAINT `bydliste_ibfk_1` FOREIGN KEY (`yeti_id`) REFERENCES `yeti` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bydliste`
--

LOCK TABLES `bydliste` WRITE;
/*!40000 ALTER TABLE `bydliste` DISABLE KEYS */;
INSERT INTO `bydliste` VALUES (1,'Praha','Sněhová 1',1),(2,'Brno','Ledová 22',2),(3,'Ostrava','Mrazivá 5',3),(4,'Plzeň','Zimní 7',4),(5,'Liberec','Chladná 9',5),(6,'Olomouc','Mražená 3',6),(7,'Zlín','Šerá 12',7),(8,'Hradec','Zamrzlá 10',8),(9,'Pardubice','Fujavice 8',9),(10,'Ústí','Sněžená 11',10),(11,'Jihlava','Vrchovina',11),(12,'Brno','Branská',12);
/*!40000 ALTER TABLE `bydliste` ENABLE KEYS */;
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
