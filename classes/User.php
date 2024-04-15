<?php

namespace LoginOpdracht;

use PDO;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";

class User{
    // Eigenschappen user
    public $username;

    private $password;
    private $db;

    function SetPassword($password){
        $this->password = $password;
    }

    function GetPassword(){
        return $this->password;
    }

    public function ShowUser() {
        echo "<br>Username: $this->username<br>";
        echo "<br>Password: $this->password<br>";

    }
    public function __construct() {
        $this->db = new PDO("mysql:host=localhost;dbname=login", "root", "");
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    public function RegisterUser()
    {
        $status = false;
        $errors = [];


        // Check of de gebruiker al bestaat in de database
        if ($this->UserExists($this->username)) {
            array_push($errors, "Username bestaat al.");
        } else {
            // Gebruiker toevoegen aan de database
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

            try {
                // Voorbereid de SQL-query
                $stmt = $this->db->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
                $stmt->bindParam(':username', $this->username);
                $stmt->bindParam(':password', $hashedPassword);
                // Voer de query uit
                if ($stmt->execute()) {
                    $status = true;
                } else {
                    array_push($errors, "Fout bij het toevoegen van de gebruiker.");
                }
            } catch (PDOException $e) {
                // Handle fouten indien nodig
                echo "Error: " . $e->getMessage();
            }
        }

        return $errors;
    }

    private function UserExists($username)
    {
        // Controleer of de gebruiker al bestaat in de tabel 'users'
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            return $user ? true : false;
        } catch (PDOException $e) {
            // Handle fouten indien nodig
            echo "Error: " . $e->getMessage();
        }
    }

    function ValidateUser(){
        $errors=[];

        if (empty($this->username)){
            array_push($errors, "Invalid username");

        } else if (strlen($this->username) < 3 || strlen($this->username) > 50) {
            array_push($errors, "Username moet > 3 en < 50 tekens zijn.");

        } if (empty($this->password)){
            array_push($errors, "Invalid password");
        }

        // Test username foutmelding Username moet > 3 en < 50 tekens zijn.

        return $errors;
    }

    public function LoginUser() {
        try {
            // Prepare the SQL query
            $stmt = $this->db->prepare("SELECT * FROM gegevens WHERE username = :username");
            $stmt->bindParam(':username', $this->username);
            // Execute the query
            $stmt->execute();

            // Fetch the user
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if user exists and password is correct
            if ($user && password_verify($this->GetPassword(), $user['password'])) {
                // Set session variables
                $_SESSION['username'] = $user['username'];

                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            // Handle errors if necessary
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Check if the user is already logged in
    public function IsLoggedin() {
        // Check if user session has been set
        return isset($_SESSION['username']);
    }

    public function GetUser($username)
    {
        try {
            // Voorbereid de SQL-query
            $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);

            // Voer de query uit
            $stmt->execute();

            // Haal de gebruiker op
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Controleer of de gebruiker is gevonden
            if ($user) {
                // Vullen eigenschappen met waarden uit de SELECT
                $this->username = $user['username'];

                // Je kunt andere eigenschappen ook toevoegen op basis van je databasestructuur
            } else {
                return NULL; // Gebruiker niet gevonden
            }

        } catch (PDOException $e) {
            // Handle fouten indien nodig
            echo "Error: " . $e->getMessage();
        }
    }


    public function Logout(){
        session_start();
        // remove all session variables
        session_unset();

        // destroy the session
        session_destroy();

        header('location: index.php');
    }
}



?>