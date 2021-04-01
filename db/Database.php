<?php
namespace db;
use models\ResponseModel;

class Database {
    public static function connect_db() {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "restaurant";
        $false_response = new ResponseModel(false, "Connection failed: ". mysqli_connect_error());
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        if (!$conn) {
            return $false_response;
        }
    
        return new ResponseModel(true, $conn);
    }
}
?>