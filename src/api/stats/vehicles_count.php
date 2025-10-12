<?php
header('Content-Type: application/json');
require_once '../../config/Database.php';
require_once '../../models/Vehicle.php';

$database = new Database();
$db = $database->connect();

$vehicle = new Vehicle($db);
$count = $vehicle->countAll();

echo json_encode(['total_vehicles' => $count]);
?>
