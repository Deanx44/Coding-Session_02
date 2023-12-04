<?php
class Database {
    private $host = 'localhost';
    private $username = 'root';
    private $password = 'KristelMae07';
    private $database = 'school_db';
    private $connection;

    public function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->database}";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }


    public function getConnection() {
        return $this->connection;
    }
}


?>