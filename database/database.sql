-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: GamePlanner
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Household_Id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Info` text DEFAULT NULL,
  `BeginDate` date DEFAULT NULL,
  `EndDate` date DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_events_households` (`Household_Id`),
  CONSTRAINT `fk_events_households` FOREIGN KEY (`Household_Id`) REFERENCES `households` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `games`
--

DROP TABLE IF EXISTS `games`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `games` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  `Info` text DEFAULT NULL,
  `Cost` int(11) DEFAULT 0,
  `Link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `games`
--

LOCK TABLES `games` WRITE;
/*!40000 ALTER TABLE `games` DISABLE KEYS */;
INSERT INTO `games` VALUES (1,'Hangman','You guess letters one by one to reveal a hidden secret word. Each incorrect guess draws another piece of a stick figure, and you must solve the word before the drawing is complete.',3,'/game/hangman'),(2,'TicTacToe','You take turns with an automated opponent placing your symbols on a 3x3 grid. The first to line up three in a row horizontally, vertically, or diagonally wins the game.',3,'/game/tictactoe');
/*!40000 ALTER TABLE `games` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grocery`
--

DROP TABLE IF EXISTS `grocery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grocery` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Household_Id` int(11) NOT NULL,
  `Profile_Id` int(11) NOT NULL,
  `Category_Id` int(11) DEFAULT NULL,
  `Name` varchar(100) NOT NULL,
  `DateAdded` datetime NOT NULL DEFAULT current_timestamp(),
  `Specification` varchar(225) NOT NULL,
  `Amount` varchar(25) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_grocery_households` (`Household_Id`),
  KEY `fk_grocery_category` (`Category_Id`),
  KEY `Profile_Id` (`Profile_Id`),
  CONSTRAINT `fk_grocery_category` FOREIGN KEY (`Category_Id`) REFERENCES `grocerycategory` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_grocery_households` FOREIGN KEY (`Household_Id`) REFERENCES `households` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grocery_ibfk_1` FOREIGN KEY (`Profile_Id`) REFERENCES `profiles` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grocery`
--

LOCK TABLES `grocery` WRITE;
/*!40000 ALTER TABLE `grocery` DISABLE KEYS */;
INSERT INTO `grocery` VALUES (18,1,1,2,'Milk','2026-06-24 22:02:35','',''),(19,1,1,1,'Milk','2026-06-24 22:03:28','','');
/*!40000 ALTER TABLE `grocery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grocerycategory`
--

DROP TABLE IF EXISTS `grocerycategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grocerycategory` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grocerycategory`
--

LOCK TABLES `grocerycategory` WRITE;
/*!40000 ALTER TABLE `grocerycategory` DISABLE KEYS */;
INSERT INTO `grocerycategory` VALUES (1,'Not specified'),(2,'Meat & Poultry'),(3,'Seafood'),(4,'Dairy & Eggs'),(5,'Bakery & Bread'),(6,'Beverages'),(7,'Canned & Jarred Goods'),(8,'Frozen Foods'),(9,'Snacks'),(10,'Condiments & Sauces'),(11,'Pasta & Rice'),(12,'Oils & Vinegars'),(13,'Spices & Herbs'),(14,'Cereals & Breakfast'),(15,'Household & Cleaning');
/*!40000 ALTER TABLE `grocerycategory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `households`
--

DROP TABLE IF EXISTS `households`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `households` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `households`
--

LOCK TABLES `households` WRITE;
/*!40000 ALTER TABLE `households` DISABLE KEYS */;
INSERT INTO `households` VALUES (1,'wa','wa','$2y$10$EbrMyGx27Q8fowbOy45kL.k9IfA0V51r1nT9vw/LHrNaqhr0KqpV.'),(2,'t','t','$2y$10$vo5SpRGagWCXqBspo05aJOfwX7cZUoQYMIFORm5af8ccld0JDFLGm'),(3,'te','te','$2y$10$6qL5/Hq9fh7EJgoDw9BZG.u7AC/4abNO4yYa29xAW1gs1ppfgYT9i'),(4,'tset','tset','$2y$10$rQMcD1tZ9SdC4Gw3FHv1dez1IJXLyYhwnLCHs2iMubsGAkBmmtkaW'),(5,'gayhouse','gay@house.to','$2y$10$SzvODC3T7eD5I3dnBfZbPeXWZhbFiy2iqsj5dbR2k8b0Rh4N4APzi'),(6,'blah','blah','$2y$10$QuQrkNU4VaHmfH51X2osyupgm/P0fp1BN6NoCxGqO9.xfp1hdNvWG'),(7,'testietest','wow@gmail.com','$2y$10$5qoBwdpf3exOeH3eX9KQ8enx/e8lvCoHMGGTTdoQySluSGUrBLuG6'),(9,'tess','tess@tess.tess','$2y$10$GbNuutN8QezBYoSMdi8VwOr9aFWCYjiho9dYdLL4xG2WfV3.Vno5q');
/*!40000 ALTER TABLE `households` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profileevents`
--

DROP TABLE IF EXISTS `profileevents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profileevents` (
  `Event_Id` int(11) NOT NULL,
  `Profile_Id` int(11) NOT NULL,
  PRIMARY KEY (`Event_Id`,`Profile_Id`),
  KEY `fk_profileevents_profiles` (`Profile_Id`),
  CONSTRAINT `fk_profileevents_events` FOREIGN KEY (`Event_Id`) REFERENCES `events` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_profileevents_profiles` FOREIGN KEY (`Profile_Id`) REFERENCES `profiles` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profileevents`
--

LOCK TABLES `profileevents` WRITE;
/*!40000 ALTER TABLE `profileevents` DISABLE KEYS */;
/*!40000 ALTER TABLE `profileevents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profiles`
--

DROP TABLE IF EXISTS `profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profiles` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Household_Id` int(11) NOT NULL,
  `Username` varchar(45) NOT NULL,
  `Pin` varchar(45) DEFAULT NULL,
  `Color` enum('green','red','yellow') DEFAULT 'green',
  `Coins` int(11) DEFAULT 0,
  `Tickets` int(11) DEFAULT 0,
  `Is_Creator` tinyint(1) NOT NULL DEFAULT 0,
  `Can_Edit_Tasks` tinyint(1) NOT NULL DEFAULT 0,
  `Can_Edit_Grocery` tinyint(1) NOT NULL DEFAULT 0,
  `Can_Edit_Household` tinyint(1) NOT NULL DEFAULT 0,
  `Can_Edit_Permisions` tinyint(1) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_profiles_households` (`Household_Id`),
  CONSTRAINT `fk_profiles_households` FOREIGN KEY (`Household_Id`) REFERENCES `households` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profiles`
--

LOCK TABLES `profiles` WRITE;
/*!40000 ALTER TABLE `profiles` DISABLE KEYS */;
INSERT INTO `profiles` VALUES (1,1,'Finn','','red',440,40,1,0,0,0,0),(5,1,'Finn4','0','green',-2503,2147483647,0,0,0,1,0),(11,2,'t','t','green',0,0,0,0,0,0,0),(12,2,'t2','','green',0,0,0,0,0,0,0),(16,1,'Finn7','','green',0,0,0,1,1,1,0),(17,1,'tess','','green',0,0,0,1,1,0,0),(18,9,'tess','','green',0,0,1,0,0,0,0),(19,9,'tess2','','green',0,0,0,1,1,0,0),(20,9,'tess3','','green',0,0,0,1,1,0,0),(21,1,'test','','green',0,0,0,1,1,0,0);
/*!40000 ALTER TABLE `profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profiletasks`
--

DROP TABLE IF EXISTS `profiletasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profiletasks` (
  `Task_Id` int(11) NOT NULL,
  `Profile_Id` int(11) NOT NULL,
  PRIMARY KEY (`Task_Id`,`Profile_Id`),
  KEY `fk_profiletasks_profiles` (`Profile_Id`),
  CONSTRAINT `fk_profiletasks_profiles` FOREIGN KEY (`Profile_Id`) REFERENCES `profiles` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_profiletasks_tasks` FOREIGN KEY (`Task_Id`) REFERENCES `tasks` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profiletasks`
--

LOCK TABLES `profiletasks` WRITE;
/*!40000 ALTER TABLE `profiletasks` DISABLE KEYS */;
/*!40000 ALTER TABLE `profiletasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `taskcategory`
--

DROP TABLE IF EXISTS `taskcategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `taskcategory` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taskcategory`
--

LOCK TABLES `taskcategory` WRITE;
/*!40000 ALTER TABLE `taskcategory` DISABLE KEYS */;
/*!40000 ALTER TABLE `taskcategory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tasks` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Household_Id` int(11) NOT NULL,
  `Category_Id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Info` text DEFAULT NULL,
  `Completed` tinyint(1) DEFAULT 0,
  `Deadline` date DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_tasks_households` (`Household_Id`),
  KEY `fk_tasks_category` (`Category_Id`),
  CONSTRAINT `fk_tasks_category` FOREIGN KEY (`Category_Id`) REFERENCES `taskcategory` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_tasks_households` FOREIGN KEY (`Household_Id`) REFERENCES `households` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-25  0:14:04
