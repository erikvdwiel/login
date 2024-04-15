<?php
// auteur:van der Wiel
// file niet van toepassing

namespace LoginOpdracht;

use PDO;

class Database{

    private $username = "root";
    private $password = "";
    private $dbname = "login";
    private $hostname = "localhost";

    //Connect Database

    public function __construct(){
        try {
            $conn = new PDO("mysql:host=$this->hostname;dbname=$this->dbname", $this->username, $this->password);
            // Set the PDO Error Mode to Exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            //echo "Connected successfully";
            return $conn;
        }
        catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}

?>