<?php
namespace Config;

use PDO;
use PDOException;

class Database {
    private $host = '127.0.0.1';
    private $db_name = 'vehicle_pro_portal';
    private $username = 'root';
    private $password =  'fatma123';
    public $conn;

    public function getConnection(){
        $this->conn = null;
        try{
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        }catch(PDOException $e){
            echo "DB Connection error: " . $e->getMessage();
            exit;
        }
        return $this->conn;
    }
}

