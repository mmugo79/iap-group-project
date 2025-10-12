<?php
require_once __DIR__ . '/../../config/bootstrap.php';
use Models\Vehicle;

$vehicleModel = new Vehicle($db);
$all = $vehicleModel->all();
header('Content-Type: application/json');
echo json_encode($all);
