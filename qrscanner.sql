-- MySQL dump 10.13  Distrib 8.3.0, for Win64 (x86_64)
--
-- Host: localhost    Database: qr
-- ------------------------------------------------------
-- Server version	8.3.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `qrcode`
--

DROP TABLE IF EXISTS `qrcode`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `qrcode` (
  `idqrcode` int NOT NULL AUTO_INCREMENT,
  `qrname` varchar(45) NOT NULL,
  `qrbody` varchar(255) NOT NULL,
  `userid` int NOT NULL,
  `deletetime` datetime DEFAULT NULL,
  PRIMARY KEY (`idqrcode`),
  KEY `user_id_idx` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=281 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qrcode`
--

LOCK TABLES `qrcode` WRITE;
/*!40000 ALTER TABLE `qrcode` DISABLE KEYS */;
INSERT INTO `qrcode` VALUES (260,'dsadsad','google.com11',155,'2024-02-13 12:30:46'),(261,'hla','hla',155,'2024-02-13 12:56:41'),(263,'dsad','dsd',156,'2024-02-13 12:32:00'),(264,'dsd','dsds',156,'2024-02-13 12:31:45'),(265,'hla','hla',156,'2024-02-13 12:56:44'),(266,'111','www.reddit.com',169,'2024-02-13 17:52:48'),(267,'222','youtube.com.com',169,'2024-02-13 17:52:50'),(268,'1','www.google.com',169,'2024-02-13 18:11:51'),(269,'2','www.youtube.com',169,'2024-02-13 18:11:52'),(270,'1','www.google.com',169,'2024-02-13 18:20:59'),(271,'2','www.wwww.com',169,'2024-02-14 11:39:40'),(272,'dsadsdsd','saddsd',169,'2024-02-14 11:40:05'),(273,'dsad','dasd',169,'2024-02-14 11:42:25'),(274,'dsadsa','dsadsa',105,NULL),(275,'dsad','sad',169,NULL),(276,'dsad','sadsa',105,NULL),(277,'dsad','asd',105,NULL),(278,'created by admin','dadsd',105,'2024-02-14 12:48:43'),(279,'dsad','sdad',105,NULL),(280,'edited','admin',105,'2024-02-14 12:48:40');
/*!40000 ALTER TABLE `qrcode` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scan`
--

DROP TABLE IF EXISTS `scan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `scan` (
  `idscan` int NOT NULL AUTO_INCREMENT,
  `idqrcode` varchar(45) NOT NULL,
  `qrbody` varchar(255) NOT NULL,
  `scantime` datetime DEFAULT NULL,
  PRIMARY KEY (`idscan`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scan`
--

LOCK TABLES `scan` WRITE;
/*!40000 ALTER TABLE `scan` DISABLE KEYS */;
INSERT INTO `scan` VALUES (35,'252','www.google.com','2024-02-12 14:39:34'),(36,'260','google.com11','2024-02-13 11:56:17'),(37,'261','wewedsadsd','2024-02-13 11:56:27');
/*!40000 ALTER TABLE `scan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `idusers` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `deletetime` datetime DEFAULT NULL,
  PRIMARY KEY (`idusers`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=173 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (105,'admin@executech.sa','$2y$10$inc.Ding6uib01vIgmwvpecjb4F.kjAyuoMEnOlsPeYFYOQ4AM9La',NULL),(169,'a@gmail.com','$2y$10$TAZCe73tekUCteu3D3m7leA772Msg/EHTyAiK2znXAjwK4Wh.KS3O',NULL),(170,'5@gmail.com','$2y$10$/zm9X23ioOio9VSYk.TcC.T2IInZ7MYLzVXa0dE8zn9h3fLZr7IPO',NULL),(171,'dsad@gmail.com','$2y$10$rMn7bXhi/L/jr19qrsPwPu87XlFQ0Ywh4N814C3yNVK/SAaHze0h6','2024-02-14 11:39:36'),(172,'dsadsa@gmail.com','$2y$10$PIvj3xQUgGcG2o.oyOhzXennNDmjbwxY1njA6q2CqSb.we3lULBzm','2024-02-14 11:38:57');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-02-14 12:54:48
