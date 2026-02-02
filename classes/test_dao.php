<?php

class TestDao {

    public function getAllRecords() {
        $records = array();
        $arrayCounter = 0;
        try {
            $db = DbUtil::getConnection();

            $sql = "select * from test";
            $stmt = $db->prepare($sql);
            if ($stmt->execute()) {
                // loop through the results from the database
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $records[$arrayCounter++] = $this->setRowValue($row);
                }
            }

            // close the db connection
            $db = null;
        } catch (PDOException $e) {
            echo 'DATABASE ERROR' . $e->getMessage() . '<br>';
            $db = null;
        }

        // return the array back to the function
        return $records;

    }

    private function setRowValue($row) {
        $test = new Test();

        // populate the fields
        $test->id = $row["id"];
        $test->first = $row["first"];
        $test->middle = $row["middle"];
        $test->last = $row["last"];

        return $test;
    }

}

// This class will be the model that represents the database table and html form
class Test {
    public $id = 0;
    public $first = "";
    public $middle = "";
    public $last = "";

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