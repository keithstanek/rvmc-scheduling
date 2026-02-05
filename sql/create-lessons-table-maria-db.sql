/* =========================
   COURSE
========================= */
DROP TABLE IF EXISTS `course`;
CREATE TABLE `course` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `course` WRITE;
INSERT INTO `course` (`id`,`name`) VALUES
(3,'Drums'),
(4,'Guitar'),
(5,'Piano'),
(6,'Voice'),
(7,'Woodwinds'),
(8,'Brass'),
(9,'Bass');
UNLOCK TABLES;


/* =========================
   PARENT
========================= */
DROP TABLE IF EXISTS `parent`;
CREATE TABLE `parent` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(45),
  `last_name` VARCHAR(45),
  `email` VARCHAR(100),
  `phone` VARCHAR(20),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_parent_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `parent` WRITE;
INSERT INTO `parent` VALUES
(2,'Breanna','Stanek','brestanek@gmail.com','5012891501'),
(3,'Keith','Stanek','keithstanek@gmail.com','5012891933');
UNLOCK TABLES;


/* =========================
   STUDENT
========================= */
DROP TABLE IF EXISTS `student`;
CREATE TABLE `student` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(45),
  `last_name` VARCHAR(45),
  `DOB` DATE DEFAULT NULL,
  `parent_id` INT UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_parent_id` (`parent_id`),
  CONSTRAINT `fk_student_parent`
    FOREIGN KEY (`parent_id`) REFERENCES `parent` (`id`)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `student` WRITE;
INSERT INTO `student` VALUES
(2,'Isaac','Stanek','2026-01-22',2),
(46,'Noah','Stanek',NULL,3),
(47,'Elijah','Stanek',NULL,2),
(48,'Jacob','Stanek',NULL,3);
UNLOCK TABLES;


/* =========================
   TEACHER
========================= */
DROP TABLE IF EXISTS `teacher`;
CREATE TABLE `teacher` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(45),
  `last_name` VARCHAR(45),
  `phone` VARCHAR(20),
  `email` VARCHAR(100),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_teacher_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `teacher` WRITE;
INSERT INTO `teacher` VALUES
(1,'Chandler','Owen','5012891501','te@fgewsf.vom'),
(2,'Ben','Smith','5012891501','ben.smith@rvmcmusic.com'),
(4,'Casey','Carpenter',NULL,'c.carpenter@rvmcmusic.com'),
(5,'Halle','Brown',NULL,'h.brown@rvmcmusic.com'),
(6,'Ian','Emerson',NULL,'e.emerson@rvmcmusic.com'),
(7,'Jesse','Wells',NULL,'j.wells@rvmcmusic.com'),
(8,'Miguel','Diaz-Moreno',NULL,'m.diaz@rvmcmusic.com'),
(9,'Nathan','Bain',NULL,'n.bain@rvmcmusic.com'),
(10,'Richard','Morton',NULL,'r.morton@rvmcmusic.com'),
(11,'Roman','Gonzalez',NULL,'r.gonzalez@rvmcmusic.com');
UNLOCK TABLES;


/* =========================
   TEACHER ↔ COURSE (JUNCTION)
========================= */
DROP TABLE IF EXISTS `teacher_course`;
CREATE TABLE `teacher_course` (
  `teacher_id` INT UNSIGNED NOT NULL,
  `course_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`teacher_id`,`course_id`),
  CONSTRAINT `fk_tc_teacher`
    FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_tc_course`
    FOREIGN KEY (`course_id`) REFERENCES `course` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `teacher_course` WRITE;
INSERT INTO `teacher_course` VALUES
(1,5),(1,6),(2,3),(4,4),(5,5),(5,6),
(6,7),(7,8),(7,9),(8,3),(9,3),(9,4),(10,3),(11,4),(11,5);
UNLOCK TABLES;


/* =========================
   LESSON
========================= */
DROP TABLE IF EXISTS `lesson`;
CREATE TABLE `lesson` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `series_id` VARCHAR(50) NOT NULL,
  `teacher_id` INT UNSIGNED NOT NULL,
  `student_id` INT UNSIGNED NOT NULL,
  `course_id` INT UNSIGNED NOT NULL,
  `lesson_date` DATE NOT NULL,
  `lesson_time` TIME NOT NULL,
  `duration` INT UNSIGNED NOT NULL DEFAULT 30 COMMENT 'Duration in minutes',
  `frequency` ENUM('single','weekly','biweekly','monthly') NOT NULL DEFAULT 'single',
  `notes` TEXT,
  `payment_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `payment_method` ENUM('cash','check','debit','ach') DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),
  KEY `idx_teacher_date` (`teacher_id`,`lesson_date`),
  KEY `idx_student_date` (`student_id`,`lesson_date`),
  KEY `idx_course_date` (`course_id`,`lesson_date`),

  CONSTRAINT `fk_lesson_teacher`
    FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_lesson_student`
    FOREIGN KEY (`student_id`) REFERENCES `student` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_lesson_course`
    FOREIGN KEY (`course_id`) REFERENCES `course` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


/* =========================
   LESSON NOTES
========================= */
DROP TABLE IF EXISTS `lesson_notes`;
CREATE TABLE `lesson_notes` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `lesson_id` INT UNSIGNED NOT NULL,
  `notes` TEXT NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_lesson_id` (`lesson_id`),
  CONSTRAINT `fk_lesson_notes_lesson`
    FOREIGN KEY (`lesson_id`) REFERENCES `lesson` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;