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
  `Profile_Id` int(11) DEFAULT NULL,
  `Title` varchar(255) NOT NULL,
  `EventDate` date NOT NULL,
  `EventTime` time DEFAULT NULL,
  `AssignedTo` int(11) DEFAULT NULL,
  `IsEveryone` tinyint(1) NOT NULL DEFAULT 0,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`Id`),
  KEY `fk_events_households` (`Household_Id`),
  CONSTRAINT `fk_events_households` FOREIGN KEY (`Household_Id`) REFERENCES `households` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (1,1,1,'School','2026-06-25','00:00:00',1,0,'2026-06-25 11:11:11'),(2,1,1,'📊 Scores en certificaten','2026-06-24','17:15:00',NULL,1,'2026-06-25 11:13:42');
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `games`
--

LOCK TABLES `games` WRITE;
/*!40000 ALTER TABLE `games` DISABLE KEYS */;
INSERT INTO `games` VALUES (1,'Hangman','You guess letters one by one to reveal a hidden secret word. Each incorrect guess draws another piece of a stick figure, and you must solve the word before the drawing is complete.',3,'/game/hangman'),(2,'TicTacToe','You take turns with an automated opponent placing your symbols on a 3x3 grid. The first to line up three in a row horizontally, vertically, or diagonally wins the game.',3,'/game/tictactoe'),(3,'Snake','You steer a growing snake around the board collecting food. Each piece eaten makes you longer, and you lose if you run into a wall or your own tail.',5,'/game/snake'),(4,'Memory','Cards are placed face-down in a grid and you flip two at a time. Find every matching pair before you run out of turns, relying on what you remember from earlier flips.',3,'/game/memory');
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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grocery`
--

LOCK TABLES `grocery` WRITE;
/*!40000 ALTER TABLE `grocery` DISABLE KEYS */;
INSERT INTO `grocery` VALUES (18,1,1,2,'Milk','2026-06-24 22:02:35','',''),(19,1,1,1,'Milk','2026-06-24 22:03:28','',''),(20,1,1,4,'Milk','2026-06-25 08:52:15','Whole','2 Liters');
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
  `Color` enum('green','red','yellow','blue','purple') DEFAULT 'green',
  `Coins` int(11) DEFAULT 0,
  `Tickets` int(11) DEFAULT 0,
  `Is_Creator` tinyint(1) NOT NULL DEFAULT 0,
  `Can_Edit_Events` tinyint(1) NOT NULL DEFAULT 0,
  `Can_Edit_Tasks` tinyint(1) NOT NULL DEFAULT 0,
  `Can_Edit_Grocery` tinyint(1) NOT NULL DEFAULT 0,
  `Can_Edit_Shop` tinyint(1) NOT NULL DEFAULT 0,
  `Can_Edit_Household` tinyint(1) NOT NULL DEFAULT 0,
  `Can_Edit_Permisions` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Id`),
  KEY `fk_profiles_households` (`Household_Id`),
  CONSTRAINT `fk_profiles_households` FOREIGN KEY (`Household_Id`) REFERENCES `households` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profiles`
--

LOCK TABLES `profiles` WRITE;
/*!40000 ALTER TABLE `profiles` DISABLE KEYS */;
INSERT INTO `profiles` VALUES (1,1,'Finn','','red',278,74,1,0,0,0,0,0,0),(5,1,'Finn4','0','green',-2503,2147483647,0,1,1,1,1,1,1),(11,2,'t','t','green',0,0,0,0,0,0,0,0,0),(12,2,'t2','','green',0,0,0,0,0,0,0,0,0),(16,1,'Finn7','','yellow',0,0,0,0,1,0,0,1,0),(17,1,'tess','','blue',0,0,0,0,0,0,0,0,0),(21,1,'test','','purple',0,0,0,0,1,1,0,1,1),(22,9,'Peter','','green',94,6,1,0,1,1,0,0,0),(23,9,'Jenifer','','red',0,0,0,0,1,1,0,0,0),(24,9,'Ben','','yellow',0,0,0,0,1,1,0,0,0),(25,1,'wa','','green',0,0,0,1,1,1,0,0,0);
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
-- Table structure for table `shop_items`
-- (added: did not exist in original file)
--

DROP TABLE IF EXISTS `shop_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `ticket_price` int(11) NOT NULL DEFAULT 0,
  `coin_price` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_items`
--

LOCK TABLES `shop_items` WRITE;
/*!40000 ALTER TABLE `shop_items` DISABLE KEYS */;
INSERT INTO `shop_items` VALUES (1,'Free Pass (1 day)','Skip chores for a whole day!',10,25,'2026-06-29 21:31:07'),(2,'Extra Game Token','Play one extra round of the game',5,15,'2026-06-29 21:31:07'),(3,'Choose Dinner','Pick what we eat tonight',20,50,'2026-06-29 21:31:07');
/*!40000 ALTER TABLE `shop_items` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taskcategory`
--

LOCK TABLES `taskcategory` WRITE;
/*!40000 ALTER TABLE `taskcategory` DISABLE KEYS */;
INSERT INTO `taskcategory` VALUES (1,'Daily'),(2,'Weekly'),(3,'Monthly'),(4,'Seasonal'),(5,'One-Time');
/*!40000 ALTER TABLE `taskcategory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tasks` (
  `Id` int(11) NOT NULL,
  `Household_Id` int(11) NOT NULL,
  `Assigned_To` int(11) DEFAULT NULL,
  `Category_Id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Info` text DEFAULT NULL,
  `Completed` tinyint(1) DEFAULT 0,
  `Deadline` date DEFAULT NULL,
  `Coins` int(11) NOT NULL DEFAULT 1,
  `Completed_By` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_tasks_assigned` (`Assigned_To`),
  KEY `fk_tasks_households` (`Household_Id`),
  KEY `fk_tasks_category` (`Category_Id`),
  CONSTRAINT `fk_tasks_assigned` FOREIGN KEY (`Assigned_To`) REFERENCES `profiles` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_tasks_category` FOREIGN KEY (`Category_Id`) REFERENCES `taskcategory` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_tasks_households` FOREIGN KEY (`Household_Id`) REFERENCES `households` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
INSERT INTO `tasks` VALUES (0,1,1,1,'Hello','Hello..',0,'2026-06-30',2,NULL),(1,1,NULL,3,'Take','Hello',0,'2026-06-04',1,NULL),(2,1,1,1,'Take out the trash','Trash',1,NULL,0,1);
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Fixes for task completion bug (added, nothing above was changed)
--
-- Root cause: `tasks`.`Id` was missing AUTO_INCREMENT, unlike every other
-- table's primary key in this database. Inserting a new task without
-- explicitly supplying an Id would either fail (strict mode) or try to
-- insert Id=0, which already exists ('Hello'), causing a primary key
-- collision. That breaks task creation/saving, which then breaks
-- completion since the row never saved correctly to begin with.
--
-- This also adds the missing foreign key on Completed_By -> profiles,
-- which existed as a plain column but was never enforced, so a deleted
-- profile could leave a dangling, invalid Completed_By reference.
--

ALTER TABLE `tasks` MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tasks` AUTO_INCREMENT = 3;
ALTER TABLE `tasks` ADD KEY `fk_tasks_completedby` (`Completed_By`);
ALTER TABLE `tasks` ADD CONSTRAINT `fk_tasks_completedby` FOREIGN KEY (`Completed_By`) REFERENCES `profiles` (`Id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Table structure for table `user_purchases`
-- (added: did not exist in original file)
--

DROP TABLE IF EXISTS `user_purchases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_purchases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `currency_used` enum('ticket','coin') NOT NULL,
  `cost` int(11) NOT NULL,
  `purchased_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_purchases_user` (`user_id`),
  KEY `fk_purchases_item` (`item_id`),
  CONSTRAINT `fk_purchases_item` FOREIGN KEY (`item_id`) REFERENCES `shop_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_purchases_user` FOREIGN KEY (`user_id`) REFERENCES `profiles` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- No data dumped for table `user_purchases`
-- (the original second file's sample row referenced Profile_Id 4,
-- which does not exist in this database's profiles table, so it was
-- left out rather than inserting an invalid reference)
--

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-30 12:37:38