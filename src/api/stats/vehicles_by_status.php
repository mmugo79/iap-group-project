<?php
header('Content-Type: application/json');
require_once '../../config/Database.php';
require_once '../../models/Vehicle.php';

$database = new Database();
$db = $database->connect();

$vehicle = new Vehicle($db);
$stmt = $vehicle->countByStatus();

$data = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $data[] = $row;
}

echo json_encode(['vehicles_by_status' => $data]);
?>
