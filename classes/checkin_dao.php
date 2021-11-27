<?php

class CheckinDao {

    public function getAllCheckins() {
        $records = array();
        $arrayCounter = 0;
        try {
            $db = DbUtil::getConnection();

            $sql = "select * from checkin";
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

    public function getCheckinByDate($id) {
        $records = array();
        $arrayCounter = 0;
        try {
            $db = DbUtil::getConnection();

            $sql = "select * from lesson where checkin_date ???";
            $stmt = $db->prepare($sql);
            $stmt->bindValue("checkin_date", $c_date);
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

    public function insert($checkin) {
        $sql = "insert into checkin (fist_name, last_name, checkin_date, instructor, instrument values (:f_name, :l_name, :checkin_date, :instruct, :instrum)";
        $db = DbUtil::getConnection();
        try {
            $stmt = $db->prepare($sql);

            $stmt->bindValue("first_name", $checkin->f_name);
            $stmt->bindValue("last_name", $checkin->l_name);
            $stmt->bindValue("checkin_date", $checkin->checkin_date);
            $stmt->bindValue("instructor", $checkin->instruct);
            $stmt->bindValue("instrument", $checkin->instrum);

            $stmt->execute();
            $db = null;

            return "Insert Successful";
        } catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }

    private function setRowValue($row) {
        $checkin = new checkin();

        // populate the fields
        $lesson->id = $row["id"];
        $lesson->Lesson_Name = $row["Lesson_Name"];
        $lesson->Lesson_Type = $row["Lesson_Type"];

        return $lesson;
    }

}

// This class will be the model that represents the database table and html form
// Parent is a reserved word..... need to name this class something else
class checkin {
    public $id = 0;
    public $checkin->f_name;
    public $checkin->l_name = "";
    public $checkin->checkin_date;
    public $checkin->instructor;
    public $checkin->instrument;

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