<?php
class Database {
    public static function connect() {
        $con = new mysqli('localhost', 'root', 'admin', 'academy_db');
        if ($con->connect_error) {
            die("Connection failed: " . $con->connect_error);
        }
        return $con;
    }
}
