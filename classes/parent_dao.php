<?php


/*
 *
 * SQL To Create the Parent Table

CREATE TABLE `parent` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(75) NULL,
  `last_name` VARCHAR(75) NULL,
  `email` VARCHAR(75) NULL,
  `phone` VARCHAR(15) NULL,
PRIMARY KEY (`id`));

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

    public function getParentById($id) {
        $records = array();
        $arrayCounter = 0;
        try {
            $db = DbUtil::getConnection();

            $sql = "select * from parent where id=:id";
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

    public function insert($parent) {
        $sql = "insert into parent (first_name, last_name, email, phone) values (:first_name, :last_name, :email, :phone)";
        $db = DbUtil::getConnection();
        try {
            $stmt = $db->prepare($sql);

            $stmt->bindValue("first_name", $parent->firstName);
            $stmt->bindValue("last_name", $parent->lastName);
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
        $sql = "update parent set first_name=:first_name, last_name=:last_name, email=:email, phone=:phone where id=:id";
        $db = DbUtil::getConnection();
        try {
            $stmt = $db->prepare($sql);

            $stmt->bindValue("first_name", $parent->firstName);
            $stmt->bindValue("last_name", $parent->lastName);
            $stmt->bindValue("email", $parent->email);
            $stmt->bindValue("phone", $parent->phone);
            $stmt->bindValue("id", $parent->id);

            $stmt->execute();
            $db = null;

            return "Update Successful";
        } catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }

    public function delete($parent) {
        $sql = "delete from parent where id=:id";
        $db = DbUtil::getConnection();
        try {
            $stmt = $db->prepare($sql);

            $stmt->bindValue("id", $parent->id);

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
        $parent->id = $row["id"];
        $parent->firstName = $row["first_name"];
        $parent->lastName = $row["last_name"];
        $parent->email = $row["email"];
        $parent->phone = $row["phone"];

        return $parent;
    }

}

// This class will be the model that represents the database table and html form
// Parent is a reserved word..... need to name this class something else
class Guardian {
    public $id = 0;
    public $firstName = "";
    public $lastName = "";
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
