<?php
// Database Connection using PDO

class DatabaseConnection {    
    private $username = "root";
    private $password = "";
    private $connection;

    public function __construct() {
        $dsn = "mysql:host=localhost;port=3306;dbname=teste_kabum_2024";

        try {
            $this->connection = new PDO($dsn, $this->username, $this->password);                  
        } catch (PDOException $e) {
            die("Falha na conexão: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->connection;
    }

    public function closeConnection() {
        $this->connection = null;
    }
}
?>
