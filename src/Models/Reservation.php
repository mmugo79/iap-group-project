<?php
class Reservation {
    private $conn;
    private $table = "reservations";

    public $id;
    public $user_id;
    public $vehicle_id;
    public $start_date;
    public $end_date;
    public $total_cost;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Count all reservations
    public function countAll() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}
?>
