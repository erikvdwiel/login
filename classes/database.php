<?php
class Login
{

// Database connection properties
    private static $dbHost = 'localhost';
    private static $dbUser = 'root';
    private static $dbPassword = '';
    private static $dbName = 'login';
    private static $conn; // PDO connection object

    public function __construct()
    {
        $this->initializeDatabase();
    }

    private function initializeDatabase()
    {
        if (!isset(self::$conn)) {
            $dsn = "mysql:host=" . self::$dbHost . ";dbname=" . self::$dbName;

            try {
                self::$conn = new PDO($dsn, self::$dbUser, self::$dbPassword);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
    }
}
?>