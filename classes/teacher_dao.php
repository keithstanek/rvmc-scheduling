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

class TeacherDao {

    public function getAllTeachers() {
        $records = array();
        $arrayCounter = 0;
        try {
            $db = DbUtil::getConnection();

            $sql = "select * from teacher";
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

    public function getTeacherByid($id) {
        $records = array();
        $arrayCounter = 0;
        try {
            $db = DbUtil::getConnection();

            $sql = "select * from teacher where id=:id";
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

    public function insert($teacher) {
        $sql = "insert into teacher (teacher_name, teacher_image, teacher_phone) values (:teacher_name, :teacher_image, :teacher_phone)";
        $db = DbUtil::getConnection();
        try {
            $stmt = $db->prepare($sql);

            $stmt->bindValue("teacher_name", $teacher->teacher_name);
            $stmt->bindValue("teacher_image", $teacher->teacher_image);
            $stmt->bindValue("teacher_phone", $teacher->teacher_phone);

            $stmt->execute();
            $db = null;

            return "Insert Successful";
        } catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }

    public function update($teacher) {
        $sql = "update teacher set teacher_name=:teacher_name, teacher_image=:teacher_image, teacher_phone=:teacher_phone where id=:id";
        $db = DbUtil::getConnection();
        try {
            $stmt = $db->prepare($sql);

            $stmt->bindValue("teacher_name", $teacher->teacher_name);
            $stmt->bindValue("teacher_image", $teacher->teacher_image);
            $stmt->bindValue("teacher_phone", $teacher->teacher_phone);
            $stmt->bindValue("id", $teacher->id);

            $stmt->execute();
            $db = null;

            return "Update Successful";
        } catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }

    public function delete($teacher) {
        $sql = "delete from teacher where id=:id";
        $db = DbUtil::getConnection();
        try {
            $stmt = $db->prepare($sql);

            $stmt->bindValue("id", $teacher->id);

            $stmt->execute();
            $db = null;

            return "Delete Successful";
        } catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }

    private function setRowValue($row) {
        $teacher = new Teacher();

        // populate the fields
        $teacher->id = $row["id"];
        $teacher->teacher_name = $row["teacher_name"];
        $teacher->teacher_image = $row["teacher_image"];
        $teacher->teacher_phone = $row["teacher_phone"];

        return $teacher;
    }

}

// This class will be the model that represents the database table and html form
// teacher is a reserved word..... need to name this class something else
class Teacher {
    public $id = 0;
    public $teacher_name = "";
    public $teacher_image = "";
    public $teacher_phone = "";

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