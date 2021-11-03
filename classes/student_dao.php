<?php
/**
 *
 * SQL To Create the student Table

CREATE TABLE `student` (
  `student_id` INT NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(45) NULL,
  `last_name` VARCHAR(45) NULL,
  `DOB` DATE(10) NULL,
  `parent_id` INT(11) NULL,
PRIMARY KEY (`student_id`));

 * If there are table changes, add the alter statements below. Make sure they are
 * in the order they should be executed in!!
 */

class StudentDao {

    public function getAllStudents() {
        $records = array();
        $arrayCounter = 0;
        try {
            $db = DbUtil::getConnection();

            $sql = "select * from student";
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

    public function getStudentByID($student_id) {
        $records = array();
        $arrayCounter = 0;
        try {
            $db = DbUtil::getConnection();

            $sql = "select * from student where student_id=:student_id";
            $stmt = $db->prepare($sql);
            $stmt->bindValue("student_id", $student_id);
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
	
	public function getStudentIDFromParentID($parent_id) {
        $records = array();
        $arrayCounter = 0;
        try {
            $db = DbUtil::getConnection();

            $sql = "select student_id from student where parent_id=:parent_id";
            $stmt = $db->prepare($sql);
            $stmt->bindValue("parent_id", $parent_id);
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

    public function insert($student) {
        $sql = "insert into student (first_name, last_name, DOB, parent_id) values (:first_name, :last_name, :DOB, :parent_id)";
        $db = DbUtil::getConnection();
        try {
            $stmt = $db->prepare($sql);

            $stmt->bindValue("first_name", $student->first_name);
            $stmt->bindValue("last_name", $student->last_name);
            $stmt->bindValue("DOB", $student->DOB);
            $stmt->bindValue("parent_id", $student->parent_id);

            $stmt->execute();
            $db = null;

            return "Insert Successful";
        } catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }

    public function update($student) {
        $sql = "update student set first_name=:first_name, last_name=:last_name, DOB=:DOB, parent_id=:parent_id where student_id=:student_id";
        $db = DbUtil::getConnection();
		
        try {
            $stmt = $db->prepare($sql);

            $stmt->bindValue("first_name", $student->first_name);
            $stmt->bindValue("last_name", $student->last_name);
            $stmt->bindValue("DOB", $student->DOB);
            $stmt->bindValue("parent_id", $student->parent_id);
            $stmt->bindValue("student_id", $student->student_id);

            $stmt->execute();
            $db = null;

            return "Update Successful";
        } catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }
	
	public function updateParent($student) {
        $sql = "update student set first_name=:first_name, last_name=:last_name, DOB=:DOB, parent_id=:parent_id where student_id=:student_id";
        $db = DbUtil::getConnection();
		
        try {
            $stmt = $db->prepare($sql);

            $stmt->bindValue("first_name", $student->first_name);
            $stmt->bindValue("last_name", $student->last_name);
            $stmt->bindValue("DOB", $student->DOB);
            $stmt->bindValue("parent_id", $student->parent_id);
            $stmt->bindValue("student_id", $student->student_id);

            $stmt->execute();
            $db = null;

            return "Update Successful";
        } catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }

    public function delete($student) {
        $sql = "delete from student where student_id=:student_id";
        $db = DbUtil::getConnection();
        try {
            $stmt = $db->prepare($sql);

            $stmt->bindValue("student_id", $student->student_id);

            $stmt->execute();
            $db = null;

            return "Delete Successful";
        } catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }

    private function setRowValue($row) {
        $student = new Student();

        // populate the fields
        $student->student_id = $row["student_id"];
        $student->first_name = $row["first_name"];
        $student->last_name = $row["last_name"];
        $student->DOB = $row["DOB"];
        $student->parent_id = $row["parent_id"];

        return $student;
    }

}

// This class will be the model that represents the database table and html form
// student is a reserved word..... need to name this class something else
class Student {
    public $student_id = 0;
    public $first_name = "";
    public $last_name = "";
    public $DOB = "";
    public $parent_id = "";

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