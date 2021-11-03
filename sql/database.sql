CREATE TABLE `rvmc`.`lesson` (
  `lessonID` INT NOT NULL AUTO_INCREMENT,
  `Lesson_Name` VARCHAR(45) NULL,
  `Lesson_Type` VARCHAR(45) NULL,
  PRIMARY KEY (`lessonID`));

CREATE TABLE `rvmc`.`teacher` (
  `TeacherID` INT NOT NULL AUTO_INCREMENT,
  `teacher_name` VARCHAR(45) NULL,
  `teacher_image` VARCHAR(45) NULL,
  `teacher_phone` VARCHAR(45) NULL,
  PRIMARY KEY (`TeacherID`));

CREATE TABLE `rvmc`.`parent` (
  `parent_id` INT NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(45) NULL,
  `last_name` VARCHAR(45) NULL,
  `email` VARCHAR(45) NULL,
  `phone` INT(10) NULL,
  PRIMARY KEY (`parent_id`));

CREATE TABLE `rvmc`.`student` (
  `student_id` INT NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(45) NULL,
  `last_name` VARCHAR(45) NULL,
  `DOB` DATE NULL,
  `parent_id` INT NULL,
  PRIMARY KEY (`student_id`),
  INDEX `parent_id_idx` (`parent_id` ASC) VISIBLE,
  CONSTRAINT `parent_id`
    FOREIGN KEY (`parent_id`)
    REFERENCES `rvmc`.`parent` (`parent_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
   
CREATE TABLE `rvmc`.`checkin` (
  `idcheckin` INT NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(45) NULL,
  `last_name` VARCHAR(45) NULL,
  `checkin_date` TIMESTAMP(3) NULL DEFAULT NULL ;,
  `instructor` VARCHAR(45),
  `instrument` VARCHAR(45) NULL,
  PRIMARY KEY (`idcheckin`));
    