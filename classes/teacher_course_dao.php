<?php
/**
 *
 * SQL To Create the teacher Table

CREATE TABLE `teacher_course` (
  `teacherid` int NOT NULL,
  `courseid` int DEFAULT NULL
)
 * If there are table changes, add the alter statements below. Make sure they are
 * in the order they should be executed in!!
 */

class TeacherCourseDao {

    public function getAllTeacherCourses() {
        $records = array();
        $arrayCounter = 0;
        try {
            $db = DbUtil::getConnection();

            $sql = "select * from teacher_course";
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

    public function getTeacherByCourseId($id) {
        $records = array();
        $arrayCounter = 0;
        try {
            $db = DbUtil::getConnection();

            $sql = "select teacherid from teacher_course where id=:id";
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

	public function getCourseByTeacherId($id) {
        $records = array();
        $arrayCounter = 0;
        try {
            $db = DbUtil::getConnection();

            $sql = "select teacherid, courseid from teacher_course where teacherid=:id";
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
        return $records;
    }
	
    public function insert($teacherid, $courseid) {
        $sql = "insert into teacher_course (teacherid, courseid) values (:teacherid, :courseid);";
        $db = DbUtil::getConnection();
        try {
            $stmt = $db->prepare($sql);

            $stmt->bindValue("teacherid", $teacherid);
			$stmt->bindValue("courseid", $courseid);

            $stmt->execute();
            $db = null;
            
            return "Insert Successful";
            }
            catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }

    public function update($teacher_course) {
        $sql = "update teacher_course set teacherid=:tid, courseid=:lesid where teacherid=:tid AND courseid=:lesid;";
        $db = DbUtil::getConnection();
        try {
            $stmt = $db->prepare($sql);

            $stmt->bindValue("tid", $teacher_course->teacherid);
            $stmt->bindValue("lesid", $teacher_course->courseid);

            $stmt->execute();
            $db = null;

            return "Update Successful";
        } catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }

    public function delete($teacher_course) {
        $sql = "delete from teacher_course where teacherid=:tid AND courseid=:lesid";
        $db = DbUtil::getConnection();
        try {
            $stmt = $db->prepare($sql);

            $stmt->bindValue("tid", $teacher_course->id);

            $stmt->execute();
            $db = null;

            return "Delete Successful";
        } catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }

    public function deleteByTeacherID($teacherid) {
        $sql = "delete from teacher_course where teacherid=:tid";
        $db = DbUtil::getConnection();
        try {
            $stmt = $db->prepare($sql);

            $stmt->bindValue("tid", $teacherid);

            $stmt->execute();
            $db = null;

            return "Delete Successful";
        } catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }

    private function setRowValue($row) {
        $teacher_course = new TeacherCourse();

        // populate the fields
        $teacher_course->teacherid = $row["teacherid"];
        $teacher_course->courseid = $row["courseid"];
        return $teacher_course;
    }

}

// This class will be the model that represents the database table and html form
// teacher is a reserved word..... need to name this class something else
class TeacherCourse {
    public $teacherid = 0;
    public $courseid = 0;

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