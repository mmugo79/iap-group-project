<?php
header('Content-Type: application/json');
require_once '../../config/Database.php';
require_once '../../models/Vehicle.php';

$database = new Database();
$db = $database->connect();

$data = json_decode(file_get_contents("php://input"));

if (empty($data->model) || empty($data->plate_no) || empty($data->price_per_day)) {
    echo json_encode(['message' => 'Missing fields']);
    exit;
}

$query = "INSERT INTO vehicles (model, plate_no, price_per_day, status, image) VALUES (:model, :plate_no, :price_per_day, :status, :image)";
$stmt = $db->prepare($query);
$stmt->bindParam(":model", $data->model);
$stmt->bindParam(":plate_no", $data->plate_no);
$stmt->bindParam(":price_per_day", $data->price_per_day);
$stmt->bindParam(":status", $data->status);
$stmt->bindParam(":image", $data->image);
$stmt->execute();

echo json_encode(['message' => 'Vehicle created successfully']);
?>
