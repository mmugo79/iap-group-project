<?php
header('Content-Type: application/json');
require_once '../../config/Database.php';

$database = new Database();
$db = $database->connect();

$query = "SELECT r.*, v.model, v.plate_no, u.name AS user_name
          FROM reservations r
          JOIN vehicles v ON r.vehicle_id = v.id
          JOIN users u ON r.user_id = u.id
          ORDER BY r.id DESC";

$stmt = $db->prepare($query);
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['reservations' => $reservations]);
?>
