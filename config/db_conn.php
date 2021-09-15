<?php

class DbUtil {

    static function getConnection() {
        $dbhost = Constants::$DATABASE_HOST;
        $dbuser = Constants::$DATABASE_USERNAME;
        $dbpass = Constants::$DATABASE_PASSWORD;
        $dbname = Constants::$DATABASE_NAME;
        $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbh;
    }

}

?>
