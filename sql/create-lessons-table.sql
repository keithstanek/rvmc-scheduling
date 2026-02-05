DROP TABLE IF EXISTS `course`;
CREATE TABLE `course` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 ;

LOCK TABLES `course` WRITE;
INSERT INTO `course` VALUES (3,'Drums'),(4,'Guitar'),(5,'Piano'),(6,'Voice'),(7,'Woodwinds'),(8,'Brass'),(9,'Bass');
UNLOCK TABLES;

DROP TABLE IF EXISTS `lesson`;
CREATE TABLE `lesson` (
  `id` int NOT NULL AUTO_INCREMENT,
  `series_id` varchar(50) NOT NULL,
  `teacher_id` int NOT NULL,
  `student_id` int NOT NULL,
  `course_id` int NOT NULL,
  `lesson_date` date NOT NULL,
  `lesson_time` time NOT NULL,
  `duration` int NOT NULL DEFAULT '30' COMMENT 'Duration in minutes',
  `frequency` enum('single','weekly','biweekly','monthly') NOT NULL DEFAULT 'single',
  `notes` text,
  `payment_amount` decimal(10,2) DEFAULT '0.00',
  `payment_method` enum('cash','check','debit','ach','') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_teacher_date` (`teacher_id`,`lesson_date`),
  KEY `idx_series_date` (`series_id`,`lesson_date`),
  KEY `idx_student_date` (`student_id`,`lesson_date`),
  KEY `idx_course_date` (`course_id`,`lesson_date`),
  KEY `idx_lesson_datetime` (`lesson_date`,`lesson_time`),
  KEY `idx_frequency` (`frequency`),
  CONSTRAINT `fk_lesson_course` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_lesson_student` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_lesson_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 ;

LOCK TABLES `lesson` WRITE;
INSERT INTO `lesson` VALUES (12,'6982696ee8745',5,46,5,'2026-02-10','18:00:00',30,'weekly','',0.00,'','2026-02-03 21:32:30','2026-02-04 21:22:02'),(13,'6982696ee8745',5,46,5,'2026-02-17','18:00:00',30,'weekly','',0.00,'','2026-02-03 21:32:30','2026-02-04 21:22:02'),(14,'6982696ee8745',5,46,5,'2026-02-24','18:00:00',30,'weekly','',0.00,'','2026-02-03 21:32:30','2026-02-04 21:22:02'),(15,'6982696ee8745',5,46,5,'2026-03-03','18:00:00',30,'weekly','',0.00,'','2026-02-03 21:32:30','2026-02-04 21:22:02'),(16,'6982696ee8745',5,46,5,'2026-03-10','18:00:00',30,'weekly','',0.00,'','2026-02-03 21:32:30','2026-02-04 21:22:02'),(17,'6982696ee8745',5,46,5,'2026-03-17','18:00:00',30,'weekly','',0.00,'','2026-02-03 21:32:30','2026-02-04 21:22:02'),(18,'6982696ee8745',5,46,5,'2026-03-24','18:00:00',30,'weekly','',0.00,'','2026-02-03 21:32:30','2026-02-04 21:22:02'),(19,'6982696ee8745',5,46,5,'2026-03-31','18:00:00',30,'weekly','',0.00,'','2026-02-03 21:32:30','2026-02-04 21:22:02'),(20,'6982696ee8745',5,46,5,'2026-04-07','18:00:00',30,'weekly','',0.00,'','2026-02-03 21:32:30','2026-02-04 21:22:02'),(51,'6983880c7b238',5,47,6,'2026-02-10','17:30:00',30,'weekly','',0.00,'','2026-02-04 17:55:24','2026-02-04 21:24:01'),(52,'6983880c7b238',5,47,6,'2026-02-17','17:30:00',30,'weekly','',0.00,'','2026-02-04 17:55:24','2026-02-04 21:24:01'),(53,'6983880c7b238',5,47,6,'2026-02-24','17:30:00',30,'weekly','',0.00,'','2026-02-04 17:55:24','2026-02-04 21:24:01'),(54,'6983880c7b238',5,47,6,'2026-03-03','17:30:00',30,'weekly','',0.00,'','2026-02-04 17:55:24','2026-02-04 21:24:01'),(55,'6983880c7b238',5,47,6,'2026-03-10','17:30:00',30,'weekly','',0.00,'','2026-02-04 17:55:24','2026-02-04 21:24:01'),(56,'6983880c7b238',5,47,6,'2026-03-17','17:30:00',30,'weekly','',0.00,'','2026-02-04 17:55:24','2026-02-04 21:24:01'),(57,'6983880c7b238',5,47,6,'2026-03-24','17:30:00',30,'weekly','',0.00,'','2026-02-04 17:55:24','2026-02-04 21:24:01'),(58,'6983880c7b238',5,47,6,'2026-03-31','17:30:00',30,'weekly','',0.00,'','2026-02-04 17:55:24','2026-02-04 21:24:01'),(59,'6983880c7b238',5,47,6,'2026-04-07','17:30:00',30,'weekly','',0.00,'','2026-02-04 17:55:24','2026-02-04 21:24:01'),(60,'6983880c7b238',5,47,6,'2026-04-14','17:30:00',30,'weekly','',0.00,'','2026-02-04 17:55:24','2026-02-04 21:24:01'),(61,'6983882645f12',5,2,6,'2026-02-10','18:30:00',30,'weekly','',0.00,'','2026-02-04 17:55:50','2026-02-04 17:55:50'),(62,'6983882645f12',5,2,6,'2026-02-17','18:30:00',30,'weekly','',0.00,'','2026-02-04 17:55:50','2026-02-04 17:55:50'),(63,'6983882645f12',5,2,6,'2026-02-24','18:30:00',30,'weekly','',0.00,'','2026-02-04 17:55:50','2026-02-04 17:55:50'),(64,'6983882645f12',5,2,6,'2026-03-03','18:30:00',30,'weekly','',0.00,'','2026-02-04 17:55:50','2026-02-04 17:55:50'),(65,'6983882645f12',5,2,6,'2026-03-10','18:30:00',30,'weekly','',0.00,'','2026-02-04 17:55:50','2026-02-04 17:55:50'),(66,'6983882645f12',5,2,6,'2026-03-17','18:30:00',30,'weekly','',0.00,'','2026-02-04 17:55:50','2026-02-04 17:55:50'),(67,'6983882645f12',5,2,6,'2026-03-24','18:30:00',30,'weekly','',0.00,'','2026-02-04 17:55:50','2026-02-04 17:55:50'),(68,'6983882645f12',5,2,6,'2026-03-31','18:30:00',30,'weekly','',0.00,'','2026-02-04 17:55:50','2026-02-04 17:55:50'),(69,'6983882645f12',5,2,6,'2026-04-07','18:30:00',30,'weekly','',0.00,'','2026-02-04 17:55:50','2026-02-04 17:55:50'),(70,'6983882645f12',5,2,6,'2026-04-14','18:30:00',30,'weekly','',0.00,'','2026-02-04 17:55:50','2026-02-04 17:55:50');
UNLOCK TABLES;

DROP TABLE IF EXISTS `lesson_details`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = @saved_cs_client;

DROP TABLE IF EXISTS `lesson_notes`;
CREATE TABLE `lesson_notes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `lesson_id` int NOT NULL,
  `notes` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `lesson_id` (`lesson_id`),
  CONSTRAINT `lesson_notes_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `lesson` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;


DROP TABLE IF EXISTS `parent`;
CREATE TABLE `parent` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 ;

LOCK TABLES `parent` WRITE;
INSERT INTO `parent` VALUES (2,'Breanna','Stanek','brestanek@gmail.com','501 289-1501'),(3,'Keith','Stanek','keithstanek@gmail.com','5012891933');
UNLOCK TABLES;

DROP TABLE IF EXISTS `student`;
CREATE TABLE `student` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `DOB` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `parent_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id_idx` (`parent_id`),
  CONSTRAINT `parent` FOREIGN KEY (`parent_id`) REFERENCES `parent` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 ;

LOCK TABLES `student` WRITE;
INSERT INTO `student` VALUES (2,'Isaac','Stanek','2026-01-22 06:00:00',2),(46,'Noah','Stanek',NULL,3),(47,'Elijah','Stanek',NULL,2),(48,'Jacob','Stanek',NULL,3);
UNLOCK TABLES;

DROP TABLE IF EXISTS `teacher`;
CREATE TABLE `teacher` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 ;

LOCK TABLES `teacher` WRITE;
INSERT INTO `teacher` VALUES (1,'Chandler','Owen','501 289-1501','te@fgewsf.vom'),(2,'Ben','Smith','501 289-1501','ben.smith@rvmcmusic.com'),(4,'Casey','Carpenter','','c.carpenter@rvmcmusic.com'),(5,'Halle','Brown','','h.brown@rvmcmusic.com'),(6,'Ian','Emerson','','e.emerson@rvmcmusic.com'),(7,'Jesse','Wells','','j.wells@rvmcmusic.com'),(8,'Miguel','Diaz-Moreno','','j.wells@rvmcmusic.com'),(9,'Nathan','Bain','','n.bain@rvmcmusic.com'),(10,'Richard','Morton','','r.morton@rvmcmusic.com'),(11,'Roman','Gonzalez','','r.gonzalez@rvmcmusic.com');
UNLOCK TABLES;

DROP TABLE IF EXISTS `teacher_course`;
CREATE TABLE `teacher_course` (
  `teacherid` int NOT NULL,
  `courseid` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

LOCK TABLES `teacher_course` WRITE;
INSERT INTO `teacher_course` VALUES (3,1),(3,3),(1,5),(1,6),(2,3),(4,4),(5,5),(5,6),(6,7),(7,8),(7,9),(8,3),(9,3),(9,4),(10,3),(11,4),(11,5);
UNLOCK TABLES;


-- Dump completed on 2026-02-04 21:03:02
