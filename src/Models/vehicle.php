<?php
class Vehicle {
    private $conn;
    private $table = "vehicles";

    public $id;
    public $model;
    public $plate_no;
    public $status;
    public $price_per_day;
    public $image;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Count all vehicles
    //This is the Vehicle model
    public function countAll() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Vehicles grouped by status
    public function countByStatus() {
        $query = "SELECT status, COUNT(*) as count FROM " . $this->table . " GROUP BY status";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
