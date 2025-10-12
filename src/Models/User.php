<?php
namespace Models;
use PDO;

class User {
    private $conn;
    private $table = 'users';

    public function __construct(PDO $db){
        $this->conn = $db;
    }

    public function create($name, $email, $hashedPassword, $phone = null, $role = 'user'){
        $sql = "INSERT INTO {$this->table} (name, email, password, phone, role) VALUES (:name,:email,:password,:phone,:role)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name'=>$name,
            ':email'=>$email,
            ':password'=>$hashedPassword,
            ':phone'=>$phone,
            ':role'=>$role
        ]);
    }

    public function findByEmail($email){
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email'=>$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($id){
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id'=>$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function markVerified($id){
        $sql = "UPDATE {$this->table} SET verified = 1 WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id'=>$id]);
    }
}
