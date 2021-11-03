<?php
/**
 *
 * SQL To Create the Parent Table

CREATE TABLE `lesson` (
  `lessonID` INT NOT NULL AUTO_INCREMENT,
  `Lesson_Name` VARCHAR(45) NULL,
  `Lesson_Type` VARCHAR(45) NULL,
PRIMARY KEY (`lessonID`));

 * If there are table changes, add the alter statements below. Make sure they are
 * in the order they should be executed in!!
 */

class LessonDao {

    public function getAllLessons() {
        $records = array();
        $arrayCounter = 0;
        try {
            $db = DbUtil::getConnection();

            $sql = "select * from lesson";
            $stmt = $db->prepare($sql);
            if ($stmt->execute()) {
                // loop through the results from the database
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $records[$arrayCounter++] = $this->setRowValue($row);
                }
            }

            // always close the db connection
            $db = null;
        } catch (PDOException $e) {
            echo 'DATABASE ERROR' . $e->getMessage() . '<br>';
            $db = null;
        }

        // return the array back to the function
        return $records;
    }

    public function getLessonBylessonID($lessonID) {
        $records = array();
        $arrayCounter = 0;
        try {
            $db = DbUtil::getConnection();

            $sql = "select * from lesson where LessonlessonID=:LessonlessonID";
            $stmt = $db->prepare($sql);
            $stmt->bindValue("lessonID", $lessonID);
            if ($stmt->execute()) {
                // loop through the results from the database
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $records[$arrayCounter++] = $this->setRowValue($row);
                }
            }

            // always close the db connection
            $db = null;
        } catch (PDOException $e) {
            echo 'DATABASE ERROR' . $e->getMessage() . '<br>';
            $db = null;
        }

        // return the array back to the function
        return $records[0];
    }

    public function insert($lesson) {
        $sql = "insert into lesson (Lesson_Name, Lesson_Type) values (:Lesson_Name, :Lesson_Type)";
        $db = DbUtil::getConnection();
        try {
            $stmt = $db->prepare($sql);

            $stmt->bindValue("Lesson_Name", $lesson->Lesson_Name);
            $stmt->bindValue("Lesson_Type", $lesson->Lesson_Type);

            $stmt->execute();
            $db = null;

            return "Insert Successful";
        } catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }

    public function update($lesson) {
        $sql = "update lesson set Lesson_Name=:Lesson_Name, Lesson_Type=:Lesson_Type where LessonlessonID=:LessonlessonID";
        $db = DbUtil::getConnection();
        try {
            $stmt = $db->prepare($sql);

            $stmt->bindValue("Lesson_Name", $lesson->Lesson_Name);
            $stmt->bindValue("Lesson_Type", $lesson->Lesson_Type);
            $stmt->bindValue("lessonID", $lesson->lessonID);

            $stmt->execute();
            $db = null;

            return "Update Successful";
        } catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }

    public function delete($lesson) {
        $sql = "delete from lesson where LessonlessonID=:LessonlessonID";
        $db = DbUtil::getConnection();
        try {
            $stmt = $db->prepare($sql);

            $stmt->bindValue("lessonID", $lesson->lessonID);

            $stmt->execute();
            $db = null;

            return "Delete Successful";
        } catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }

    private function setRowValue($row) {
        $lesson = new Lesson();

        // populate the fields
        $lesson->lessonID = $row["lessonID"];
        $lesson->Lesson_Name = $row["Lesson_Name"];
        $lesson->Lesson_Type = $row["Lesson_Type"];

        return $lesson;
    }

}

// This class will be the model that represents the database table and html form
// Parent is a reserved word..... need to name this class something else
class Lesson {
    public $lessonID = 0;
    public $Lesson_Name = "";
    public $Lesson_Type = "";

    // if the above fields were private, you would use the two methods below
    // to get and set the value of the property *** We just call the varibles
    // becuase they are delcared as public and not private
    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }

        return $this;
    }
}

?>