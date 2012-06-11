-- MySQL dump 10.13  Distrib 5.5.15, for osx10.6 (i386)
--
-- Host: localhost    Database: aruba
-- ------------------------------------------------------
-- Server version	5.5.15

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `boat`
--

DROP TABLE IF EXISTS `boat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `boat` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `color` enum('BLUE','NAVY_BLUE','GREEN','RED','PURPLE','WHITE','BLACK','YELLOW') NOT NULL DEFAULT 'BLUE',
  `last_student_change` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boat`
--

LOCK TABLES `boat` WRITE;
/*!40000 ALTER TABLE `boat` DISABLE KEYS */;
INSERT INTO `boat` VALUES (34,'Red Horizon',10000,'RED',NULL),(35,'Blue Streak',2000,'BLUE',NULL),(36,'Green Bastard',100000,'GREEN',NULL),(43,'Yellow Sub',2000,'YELLOW',NULL),(44,'Purple Haze',1000,'PURPLE',NULL),(45,'Black Draggon',20000,'BLACK',NULL),(46,'New Dawn',10000,'RED',NULL),(47,'Voyager',1000,'BLACK',NULL),(48,'Medea',25000,'WHITE',NULL),(49,'Jason',600,'RED',NULL),(50,'Zeus',1000000,'BLACK',NULL),(56,'Jupiter',3000,'RED',NULL),(57,'Hera',1000,'GREEN',NULL),(58,'Perseus',1000,'PURPLE',NULL),(59,'Thor',10000,'BLACK',NULL),(63,'Library',1,'BLACK',NULL);
/*!40000 ALTER TABLE `boat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `boat_has_book`
--

DROP TABLE IF EXISTS `boat_has_book`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `boat_has_book` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_boat` int(11) DEFAULT NULL,
  `id_book` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boat_has_book`
--

LOCK TABLES `boat_has_book` WRITE;
/*!40000 ALTER TABLE `boat_has_book` DISABLE KEYS */;
INSERT INTO `boat_has_book` VALUES (3,63,1);
/*!40000 ALTER TABLE `boat_has_book` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `boat_has_skipair`
--

DROP TABLE IF EXISTS `boat_has_skipair`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `boat_has_skipair` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_boat` int(11) DEFAULT NULL,
  `id_student_skipair1` int(11) DEFAULT NULL,
  `id_student_skipair2` int(11) DEFAULT NULL,
  `id_student_skipair3` int(11) DEFAULT NULL,
  `id_student_skipair4` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boat_has_skipair`
--

LOCK TABLES `boat_has_skipair` WRITE;
/*!40000 ALTER TABLE `boat_has_skipair` DISABLE KEYS */;
/*!40000 ALTER TABLE `boat_has_skipair` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `boat_has_student`
--

DROP TABLE IF EXISTS `boat_has_student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `boat_has_student` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_boat` int(11) DEFAULT NULL,
  `id_student` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=280 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boat_has_student`
--

LOCK TABLES `boat_has_student` WRITE;
/*!40000 ALTER TABLE `boat_has_student` DISABLE KEYS */;
INSERT INTO `boat_has_student` VALUES (254,57,51),(255,57,61),(256,57,59),(257,63,64),(258,57,73),(259,57,72),(260,57,66),(261,57,62),(262,36,86),(263,36,76),(264,36,55),(265,36,52),(266,36,75),(267,36,77),(268,36,81),(269,36,84),(270,45,80),(271,45,70),(272,45,54),(273,45,42),(274,45,53),(275,45,87),(276,45,74),(277,45,82),(278,63,56),(279,57,85);
/*!40000 ALTER TABLE `boat_has_student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `book`
--

DROP TABLE IF EXISTS `book`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `book` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `url_on_amazon` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `book`
--

LOCK TABLES `book` WRITE;
/*!40000 ALTER TABLE `book` DISABLE KEYS */;
/*!40000 ALTER TABLE `book` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(70) DEFAULT NULL,
  `last_name` varchar(70) DEFAULT NULL,
  `has_skipair` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=90 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student`
--

LOCK TABLES `student` WRITE;
/*!40000 ALTER TABLE `student` DISABLE KEYS */;
INSERT INTO `student` VALUES (53,'Rosa','Sanchez',0),(42,'Vero','Jimenez',1),(51,'Pedro','Gomez',1),(52,'Jose ','vanDerBuilt',1),(54,'Conchita','Gonzalez',1),(55,'Arturo','Sandoval',1),(56,'Chucho','Perez',1),(61,'Pancho','Villa',1),(59,'Chico','Izquierda',1),(62,'Gauchos','Medina',0),(64,'Carlos','Fuego',1),(66,'Jose','Montoya',0),(70,'Chavela','Vargas',1),(87,'Maria','Portero',0),(72,'Juan','Rodriquez',0),(73,'Paquito','Cerveza',1),(74,'Sancho','Cholo',0),(75,'Tepo','Mesa',0),(76,'Carlos','Bravo',1),(77,'Julio','Crujff',0),(86,'Pedro ','Martinez',1),(80,'Lila','Dawns',1),(81,'Francisco','Guerrero',0),(82,'Arancha','Coronina',0),(84,'Ernesto','Blanco',0),(85,'Paco','DeLucia',0),(88,'Hernan','Requielme',0),(89,'Jorge','Alcazar',1);
/*!40000 ALTER TABLE `student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'aruba'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-06-11  0:27:38
