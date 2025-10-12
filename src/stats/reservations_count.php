<?php
header('Content-Type: application/json');
require_once '../../config/Database.php';
require_once '../../models/Reservation.php';

$database = new Database();
$db = $database->connect();

$reservation = new Reservation($db);
$count = $reservation->countAll();

echo json_encode(['total_reservations' => $count]);
?>
