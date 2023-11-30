<?php
namespace model;

class DBConnection {
    public $connection = null;

    public function __construct()
    {
        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $db   = $_ENV['DB_DATABASE'];
        $username = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];
        try {

            $this->connection = new \PDO(
                "mysql:host=$host;port=$port;charset=utf8mb4;dbname=$db",
                $username,
                $password
            );
            // echo "Database was connected successfully";        
        } catch (\PDOException $e) {
            
            var_dump($e->getMessage());
            // Add log here;
            throw new \ErrorException($e->getMessage());
        }
    }

    public function connection() {
        return $this->connection;
    }
}



?>
