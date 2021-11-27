<?php
/**
 *
 * SQL To Create the teacher Table

CREATE TABLE `teacher` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `teacher_name` VARCHAR(45) NULL,
  `teacher_image` VARCHAR(45) NULL,
  `teacher_phone` VARCHAR(45) NULL,
PRIMARY KEY (`id`));

 * If there are table changes, add the alter statements below. Make sure they are
 * in the order they should be executed in!!
 */

class Teacher_Lesson_Dao {

    public function getAllTeacherLessons() {
        $records = array();
        $arrayCounter = 0;
        try {
            $db = DbUtil::getConnection();

            $sql = "select * from teacher_lesson";
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

    public function getTeacherByLessonId($id) {
        $records = array();
        $arrayCounter = 0;
        try {
            $db = DbUtil::getConnection();

            $sql = "select teacherid from teacher_lesson where id=:id";
            $stmt = $db->prepare($sql);
            $stmt->bindValue("id", $id);
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

	public function getLessonByTeacherId($id) {
        $records = array();
        $arrayCounter = 0;
        try {
            $db = DbUtil::getConnection();

            $sql = "select lessonid from teacher_lesson where teacherid=:id";
            $stmt = $db->prepare($sql);
            $stmt->bindValue("id", $id);
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
	
    public function insert($teacher_lesson) {
        $sql = "insert into teacher_lesson (teacherid, lessonid) values (:teacherid, :lessonid);";
        $db = DbUtil::getConnection();
        try {
            $stmt = $db->prepare($sql);

            $stmt->bindValue("teacherid", $teacher_lesson->teacherid);
			$stmt->bindValue("lessonid", $teacher_lesson->lessonid);

            $stmt->execute();
            $db = null;
            
            return "Insert Successful";
            }
            catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }

    public function update($teacher_lesson) {
        $sql = "update teacher_lesson set teacherid=:tid, lessonid=:lesid where teacherid=:tid AND lessonid=:lesid;";
        $db = DbUtil::getConnection();
        try {
            $stmt = $db->prepare($sql);

            $stmt->bindValue("tid", $teacher_lesson->teacherid);
            $stmt->bindValue("lesid", $teacher_lesson->lessonid);

            $stmt->execute();
            $db = null;

            return "Update Successful";
        } catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }

    public function delete($teacher_lesson) {
        $sql = "delete from teacher_lesson where teacherid=:tid AND lessonid=:lesid";
        $db = DbUtil::getConnection();
        try {
            $stmt = $db->prepare($sql);

            $stmt->bindValue("id", $teacher_lesson->id);

            $stmt->execute();
            $db = null;

            return "Delete Successful";
        } catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }

    private function setRowValue($row) {
        $teacher_lesson = new teacher_lesson();

        // populate the fields
        $teacher_lesson->teacherid = $row["teacherid"];
        $teacher_lesson->lessonid = $row["lessonid"];
        return $teacher_lesson;
    }

}

// This class will be the model that represents the database table and html form
// teacher is a reserved word..... need to name this class something else
class Teacher_Lesson {
    public $teacherid = null;
    public $lessonid = null;

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