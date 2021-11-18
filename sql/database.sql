CREATE TABLE `checkin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `checkin_date` timestamp(2) NULL DEFAULT NULL,
  `instructor` varchar(45) DEFAULT NULL,
  `instrument` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `lesson` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Lesson_Name` varchar(45) DEFAULT NULL,
  `Lesson_Type` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `parent` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `phone` int DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `student` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `DOB` varchar(12) DEFAULT NULL,
  `parent_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id_idx` (`parent_id`),
  CONSTRAINT `parent_id` FOREIGN KEY (`parent_id`) REFERENCES `parent` (`id`)
);

CREATE TABLE `teacher` (
  `id` int NOT NULL AUTO_INCREMENT,
  `teacher_name` varchar(45) DEFAULT NULL,
  `teacher_image` varchar(45) DEFAULT NULL,
  `teacher_phone` varchar(45) DEFAULT NULL,
  `teacher_email` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `teacher_lesson` (
  `teacherid` int NOT NULL,
  `lessonid` int DEFAULT NULL
);
