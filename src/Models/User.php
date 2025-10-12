<?php
class User {
    private $conn;
    private $table = "users";

    public $id;
    public $name;
    public $email;
    public $phone;
    public $password;
    public $role;
    public $verified;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Count all users
    public function countAll() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}
?>
