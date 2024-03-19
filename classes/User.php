<?php
    // Functie: classdefinitie User
    // Auteur: Van der Wiel


namespace LoginOpdracht;

use PDO;

class User extends Database
{

    public $username;
    public $email;
    private $password;

    function SetPassword($password)
    {
        $this->password = $password;
    }

    function GetPassword(){
        return $this->password;
    }

    public function ShowUser()
    {
        echo "<br>Username: $this->username<br>";
        echo "<br>Password: $this->password<br>";
        echo "<br>Email: $this->email<br>";
    }

    public function RegisterUser()
    {
        $errors = $this->ValidateUser();
        if (!empty($errors)) {
            return $errors;
        }

        $sanitizedUsername = $this->conn->quote($this->username);
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        // Check if the user already exists
        $checkUserQuery = "SELECT * FROM `gegevens` WHERE `username` = $sanitizedUsername";
        $result = self::$conn->query($checkUserQuery);

        if ($result && $result->rowCount() > 0) {
            array_push($errors, "Username bestaat al.");
        } else {
            // If the user doesn't exist, insert into the database
            $insertQuery = "INSERT INTO `gegevens` (`username`, `password`) VALUES ($sanitizedUsername, '$hashedPassword')";

            try {
                self::$conn->exec($insertQuery);
            } catch (PDOException $e) {
                array_push($errors, "Error registering user: " . $e->getMessage());
            }
        }

        return $errors;
    }

    function ValidateUser()
    {
        $errors = [];

        if (empty($this->username)) {
            array_push($errors, "Invalid username");
        } elseif (empty($this->password)) {
            array_push($errors, "Invalid password");
        } elseif (strlen($this->username) < 3 || strlen($this->username) > 50) {
            array_push($errors, "Username must be between 3 and 50 characters");
        }

        return $errors;
    }

    public function LoginUser()
    {
        $sanitizedUsername = self::$conn->quote($this->username);

        // Search for the user in the database
        $selectQuery = "SELECT * FROM `gegevens` WHERE `username` = $sanitizedUsername";
        $result = self::$conn->query($selectQuery);

        if ($result && $result->rowCount() > 0) {
            $userData = $result->fetch(PDO::FETCH_ASSOC);

            if (password_verify($this->password, $userData['password'])) {
                // Start session
                session_start();
                $_SESSION['username'] = $this->username;
                header('location: index.php');
                exit();
            } else {
                echo "Incorrect password";
            }
        } else {
            echo "User not found";
        }

        return false;
    }

    public function IsLoggedin()
    {
        return isset($_SESSION['username']);
    }

    public function GetUser($username)
    {
        $sanitizedUsername = self::$conn->quote($username);

        // Search for the user in the database
        $selectQuery = "SELECT * FROM `gegevens` WHERE `username` = $sanitizedUsername";
        $result = self::$conn->query($selectQuery);

        if ($result && $result->rowCount() > 0) {
            $userData = $result->fetch(PDO::FETCH_ASSOC);
            $this->username = $userData['username'];
            return $this;
        } else {
            return NULL;
        }
    }

    public function Logout()
    {
        session_start();

        session_unset();

        session_destroy();
        exit();
    }
}

// Initialize the session
session_start();
?>