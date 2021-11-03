<?php
/**
 *
 * SQL To Create the Parent Table

CREATE TABLE `parent` (
  `parent_id` INT NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(75) NULL,
  `last_name` VARCHAR(75) NULL,
  `email` VARCHAR(75) NULL,
  `phone` VARCHAR(15) NULL,
PRIMARY KEY (`parent_id`));

 * If there are table changes, add the alter statements below. Make sure they are
 * in the order they should be executed in!!
 */

class ParentDao {

    public function getAllParents() {
        $records = array();
        $arrayCounter = 0;
        try {
            $db = DbUtil::getConnection();

            $sql = "select * from parent";
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

    public function getParentByparent_id($parent_id) {
        $records = array();
        $arrayCounter = 0;
        try {
            $db = DbUtil::getConnection();

            $sql = "select * from parent where parent_id=:parent_id";
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

    public function insert($parent) {
        $sql = "insert into parent (first_name, last_name, email, phone) values (:first_name, :last_name, :email, :phone)";
        $db = DbUtil::getConnection();
        try {
            $stmt = $db->prepare($sql);

            $stmt->bindValue("first_name", $parent->first_name);
            $stmt->bindValue("last_name", $parent->last_name);
            $stmt->bindValue("email", $parent->email);
            $stmt->bindValue("phone", $parent->phone);

            $stmt->execute();
            $db = null;

            return "Insert Successful";
        } catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }

    public function update($parent) {
        $sql = "update parent set first_name=:first_name, last_name=:last_name, email=:email, phone=:phone where parent_id=:parent_id";
        $db = DbUtil::getConnection();
        try {
            $stmt = $db->prepare($sql);

            $stmt->bindValue("first_name", $parent->first_name);
            $stmt->bindValue("last_name", $parent->last_name);
            $stmt->bindValue("email", $parent->email);
            $stmt->bindValue("phone", $parent->phone);
            $stmt->bindValue("parent_id", $parent->parent_id);

            $stmt->execute();
            $db = null;

            return "Update Successful";
        } catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }

    public function delete($parent) {
        $sql = "delete from parent where parent_id=:parent_id";
        $db = DbUtil::getConnection();
        try {
            $stmt = $db->prepare($sql);

            $stmt->bindValue("parent_id", $parent->parent_id);

            $stmt->execute();
            $db = null;

            return "Delete Successful";
        } catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }

    private function setRowValue($row) {
        $parent = new Guardian();

        // populate the fields
        $parent->parent_id = $row["parent_id"];
        $parent->first_name = $row["first_name"];
        $parent->last_name = $row["last_name"];
        $parent->email = $row["email"];
        $parent->phone = $row["phone"];

        return $parent;
    }

}

// This class will be the model that represents the database table and html form
// Parent is a reserved word..... need to name this class something else
class Guardian {
    public $parent_id = 0;
    public $first_name = "";
    public $last_name = "";
    public $email = "";
    public $phone = "";

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