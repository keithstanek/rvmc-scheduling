<?php


class CheckinDao {

   
    public function insert($checkin) {
        $sql = "insert into checkin (first_name, last_name, instructor, instrument) values (:first_name, :last_name, :instructor, :instrument)";
        $db = DbUtil::getConnection();
        try {
            $stmt = $db->prepare($sql);

            $stmt->bindValue("first_name", $checkin->first_name);
            $stmt->bindValue("last_name", $checkin->last_name);
            $stmt->bindValue("instructor", $checkin->instructor);
            $stmt->bindValue("instrument", $checkin->instrument);

            $stmt->execute();
            $db = null;

            return "Insert Successful";
        } catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }

}

class Checkin {
    public $checkin_id = 0;
    public $first_name = "";
    public $last_name = "";
    public $checkin_date = "";
    public $instructor = "";
    public $instrument = "";
    }

?>