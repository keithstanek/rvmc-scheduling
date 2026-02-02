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

    public function insert($teacher, $courses) {
        $sql = "insert into teacher (first_name, last_name, email, phone) values (:first_name, :last_name, :email, :phone)";
        $db = DbUtil::getConnection();
        try {
            $stmt = $db->prepare($sql);

            $stmt->bindValue("first_name", $teacher->first_name);
            $stmt->bindValue("last_name", $teacher->last_name);
            $stmt->bindValue("email", $teacher->email);
            $stmt->bindValue("phone", $teacher->phone);
            $stmt->execute();

            $teacherid = $db->lastInsertId();
            
            $teacherCourseDao = new TeacherCourseDao();
            foreach($courses as $id) {
                $teacherCourseDao->insert($teacherid, $id);
            }

            $db = null;

            return "Insert Successful";
        }
        catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }

    public function update($teacher, $courses) {
        $sql = "update teacher set first_name=:first_name, last_name=:last_name, phone=:phone, email=:email where id=:id";
        $db = DbUtil::getConnection();
        echo json_encode($teacher);
        try {
            $stmt = $db->prepare($sql);

            $stmt->bindValue("first_name", $teacher->first_name);
            $stmt->bindValue("last_name", $teacher->last_name);
            $stmt->bindValue("phone", $teacher->phone);
            $stmt->bindValue("email", $teacher->email);
            $stmt->bindValue("id", $teacher->id);
            $stmt->execute();

            $teacherCourseDao = new TeacherCourseDao();
            $teacherCourseDao->deleteByTeacherID($teacher->id);
            foreach($courses as $id) {
                $teacherCourseDao->insert($teacher->id, $id);
            }

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
        $teacher = new teacher();

        $teacher->id = $row["id"];
        $teacher->first_name = $row["first_name"];
        $teacher->last_name = $row["last_name"];
        $teacher->phone = $row["phone"];
        $teacher->email = $row["email"];

        $coursesDao = new CourseDao();
        $courses = $coursesDao->getAllCoursesByTeacherId($teacher->id);
        $teacher->courses = $courses;
        return $teacher;
    }

}

// This class will be the model that represents the database table and html form
// teacher is a reserved word..... need to name this class something else
class Teacher {
    public $id = 0;
    public $first_name = "";
    public $last_name = "";
    public $phone = "";
    public $email ="";
    public $courses = array();

    public function __construct(array $data = []) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

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