<?php
namespace Models;
use PDO;

class Reservation {
    private $conn;
    private $table = 'reservations';

    public function __construct(PDO $db){
        $this->conn = $db;
    }

    public function create($user_id, $vehicle_id, $start_date, $end_date, $total_cost){
        $sql = "INSERT INTO {$this->table} (user_id, vehicle_id, start_date, end_date, total_cost, status) VALUES (:uid,:vid,:s,:e,:cost,'confirmed')";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':uid'=>$user_id, ':vid'=>$vehicle_id, ':s'=>$start_date, ':e'=>$end_date, ':cost'=>$total_cost
        ]);
    }

    public function listByUser($user_id){
        $sql = "SELECT r.*, v.model, v.plate_no FROM {$this->table} r JOIN vehicles v ON r.vehicle_id = v.id WHERE r.user_id=:uid ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':uid'=>$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cancel($id, $user_id){
        $sql = "UPDATE {$this->table} SET status='cancelled' WHERE id=:id AND user_id=:uid";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id'=>$id, ':uid'=>$user_id]);
    }
}
