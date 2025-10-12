<?php
namespace Models;
use PDO;

class Vehicle {
    private $conn;
    private $table = 'vehicles';
    public function __construct(PDO $db){
        $this->conn = $db;
    }

    public function create($model, $plate, $price, $status='available', $image=null){
        $sql = "INSERT INTO {$this->table} (model, plate_no, price_per_day, status, image) VALUES (:m,:p,:pr,:s,:img)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':m'=>$model, ':p'=>$plate, ':pr'=>$price, ':s'=>$status, ':img'=>$image
        ]);
    }

    public function all(){
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id){
        $sql = "SELECT * FROM {$this->table} WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id'=>$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $model, $plate, $price, $status){
        $sql = "UPDATE {$this->table} SET model=:m, plate_no=:p, price_per_day=:pr, status=:s WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':m'=>$model,':p'=>$plate,':pr'=>$price,':s'=>$status,':id'=>$id]);
    }

    public function delete($id){
        $sql = "DELETE FROM {$this->table} WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id'=>$id]);
    }
}
