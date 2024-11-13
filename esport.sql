-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: esport
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.20-MariaDB

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
-- Table structure for table `achievement`
--

DROP TABLE IF EXISTS `achievement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `achievement` (
  `idachievement` int(11) NOT NULL AUTO_INCREMENT,
  `idteam` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`idachievement`),
  KEY `fk_achievement_team1_idx` (`idteam`),
  CONSTRAINT `fk_achievement_team1` FOREIGN KEY (`idteam`) REFERENCES `team` (`idteam`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `achievement`
--

LOCK TABLES `achievement` WRITE;
/*!40000 ALTER TABLE `achievement` DISABLE KEYS */;
INSERT INTO `achievement` VALUES (1,7,'Dota2 Winner','2017-07-12','Winning in the biggest major tournament in Moba game'),(2,3,'PMGC Winner','2017-07-12','Winning in the biggest major tournament in Battle Royal game'),(4,3,'Dota2 MVP','2017-07-12','MVP in the biggest major tournament in Moba game'),(7,3,'Juara 1','2024-10-24','1221'),(8,3,'Most Longest Match ','2024-10-01','F'),(9,3,'Dota2 Runner-Up Winner','2024-10-31','s');
/*!40000 ALTER TABLE `achievement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event`
--

DROP TABLE IF EXISTS `event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `event` (
  `idevent` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`idevent`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event`
--

LOCK TABLES `event` WRITE;
/*!40000 ALTER TABLE `event` DISABLE KEYS */;
INSERT INTO `event` VALUES (1,'Valorant Champions','2024-11-05','Turnamen dunia untuk game Valorant yang diadakan setiap bulan'),(2,'Dota 2 The International','2024-10-15','Turnamen e-sport terbesar untuk game Dota 2 dengan hadiah jutaan dolar.'),(4,'PUBG Global Championship','2024-12-01','Turnamen global untuk game PUBG yang mempertemukan tim-tim terbaik dari seluruh dunia.'),(5,'Overwatch World Cup','2024-10-25','Kejuaraan dunia untuk game Overwatch yang mempertemukan tim nasional dari berbagai negara.'),(6,'eFootball Konami Cup','2024-10-02','Turnamen eFootball yang mempertemukan antar tim-tim eSport untuk meraih titel terbaik di Konami Cup'),(7,'Clash Royale League: 2024 World Finals','2024-10-30','Turnamen Clash Royale yang mempertemukan player dari seluruh dunia'),(8,'Pokemon Go World Championships ','2024-10-12','Turnamen yang mempertemukan Trainer Pokemon dari seluruh dunia untuk menjadi Player terbaik');
/*!40000 ALTER TABLE `event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_teams`
--

DROP TABLE IF EXISTS `event_teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `event_teams` (
  `idevent` int(11) NOT NULL,
  `idteam` int(11) NOT NULL,
  PRIMARY KEY (`idevent`,`idteam`),
  KEY `fk_event_has_team_team1_idx` (`idteam`),
  KEY `fk_event_has_team_event1_idx` (`idevent`),
  CONSTRAINT `fk_event_has_team_event1` FOREIGN KEY (`idevent`) REFERENCES `event` (`idevent`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_event_has_team_team1` FOREIGN KEY (`idteam`) REFERENCES `team` (`idteam`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_teams`
--

LOCK TABLES `event_teams` WRITE;
/*!40000 ALTER TABLE `event_teams` DISABLE KEYS */;
INSERT INTO `event_teams` VALUES (1,3),(1,7),(2,3),(2,7),(4,3),(5,3),(6,3),(7,3),(7,7);
/*!40000 ALTER TABLE `event_teams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game`
--

DROP TABLE IF EXISTS `game`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `game` (
  `idgame` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`idgame`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game`
--

LOCK TABLES `game` WRITE;
/*!40000 ALTER TABLE `game` DISABLE KEYS */;
INSERT INTO `game` VALUES (1,'Valorant','FPS Game'),(2,'DotA','RTS Game'),(3,'PUBG','Battle Royale Game'),(7,'eFootball','Football Multiplayer Game');
/*!40000 ALTER TABLE `game` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `join_proposal`
--

DROP TABLE IF EXISTS `join_proposal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `join_proposal` (
  `idjoin_proposal` int(11) NOT NULL AUTO_INCREMENT,
  `idmember` int(11) NOT NULL,
  `idteam` int(11) NOT NULL,
  `description` varchar(100) DEFAULT 'role preference: support, attacker, dll',
  `status` enum('waiting','approved','rejected') DEFAULT NULL,
  PRIMARY KEY (`idjoin_proposal`),
  KEY `fk_join_proposal_member1_idx` (`idmember`),
  KEY `fk_join_proposal_team1_idx` (`idteam`),
  CONSTRAINT `fk_join_proposal_member1` FOREIGN KEY (`idmember`) REFERENCES `member` (`idmember`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_join_proposal_team1` FOREIGN KEY (`idteam`) REFERENCES `team` (`idteam`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `join_proposal`
--

LOCK TABLES `join_proposal` WRITE;
/*!40000 ALTER TABLE `join_proposal` DISABLE KEYS */;
INSERT INTO `join_proposal` VALUES (1,2,3,'saya ingin join','approved'),(2,3,3,'kepengen aja','approved'),(3,3,7,'lorem','rejected'),(4,4,8,'hmm','waiting'),(5,3,8,'16-10\r\n','waiting'),(8,1,8,'testtest','rejected'),(9,2,8,'hmmmm hmmmmm','waiting'),(10,1,14,'gggg','waiting'),(11,2,19,'ets ','waiting'),(12,2,10,'fff','waiting');
/*!40000 ALTER TABLE `join_proposal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `member`
--

DROP TABLE IF EXISTS `member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `member` (
  `idmember` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(45) DEFAULT NULL,
  `lname` varchar(45) DEFAULT NULL,
  `username` varchar(45) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `profile` enum('admin','member') DEFAULT NULL,
  PRIMARY KEY (`idmember`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member`
--

LOCK TABLES `member` WRITE;
/*!40000 ALTER TABLE `member` DISABLE KEYS */;
INSERT INTO `member` VALUES (1,'admin','','admin','$2y$10$tXl9jQAJulNKhYx.PrT4r.qU9bM6LBpde5VShdHrV.m6gEqHjSsLC','admin'),(2,'A','111','usera','$2y$10$jlHbq2Ig7fm72G/elHL8Cuv6S5KAb2HGkI3OMJgzqJHnCd15aCt8G','member'),(3,'B','222','userb','$2y$10$jlHbq2Ig7fm72G/elHL8Cuv6S5KAb2HGkI3OMJgzqJHnCd15aCt8G','member'),(4,'C','333','userc','$2y$10$jlHbq2Ig7fm72G/elHL8Cuv6S5KAb2HGkI3OMJgzqJHnCd15aCt8G','member'),(5,'D','444','userd','$2y$10$jlHbq2Ig7fm72G/elHL8Cuv6S5KAb2HGkI3OMJgzqJHnCd15aCt8G','member'),(6,'E','555','usere','$2y$10$6zzPUqPDfXmbzYrl9Re7dOdUVobeJiqGJt5JumibpKKTYPQOi42..','member'),(7,'dsaf','ddd','ddd','$2y$10$STqM625s.J9IekO3/7SN1eou/UZVxlFDSDwaX.0POE1JGTotHyEQO','member');
/*!40000 ALTER TABLE `member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team`
--

DROP TABLE IF EXISTS `team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `team` (
  `idteam` int(11) NOT NULL AUTO_INCREMENT,
  `idgame` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `extention` varchar(4) NOT NULL,
  PRIMARY KEY (`idteam`),
  KEY `fk_team_game1_idx` (`idgame`),
  CONSTRAINT `fk_team_game1` FOREIGN KEY (`idgame`) REFERENCES `game` (`idgame`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team`
--

LOCK TABLES `team` WRITE;
/*!40000 ALTER TABLE `team` DISABLE KEYS */;
INSERT INTO `team` VALUES (3,3,'OOO',''),(7,2,'Rtu',''),(8,2,'MLO',''),(9,3,'OPQER',''),(10,3,'T-1',''),(11,1,'T-2',''),(14,1,'MXC',''),(17,1,'DDD','jpg'),(18,1,'DDDD','jpg'),(19,1,'D1D1','jpg');
/*!40000 ALTER TABLE `team` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_members`
--

DROP TABLE IF EXISTS `team_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `team_members` (
  `idteam` int(11) NOT NULL,
  `idmember` int(11) NOT NULL,
  `description` varchar(75) DEFAULT NULL,
  PRIMARY KEY (`idteam`,`idmember`),
  KEY `fk_team_has_member_member1_idx` (`idmember`),
  KEY `fk_team_has_member_team_idx` (`idteam`),
  CONSTRAINT `fk_team_has_member_member1` FOREIGN KEY (`idmember`) REFERENCES `member` (`idmember`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_team_has_member_team` FOREIGN KEY (`idteam`) REFERENCES `team` (`idteam`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_members`
--

LOCK TABLES `team_members` WRITE;
/*!40000 ALTER TABLE `team_members` DISABLE KEYS */;
INSERT INTO `team_members` VALUES (3,2,'saya ingin join'),(3,3,'kepengen aja');
/*!40000 ALTER TABLE `team_members` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-11-13 21:40:48
