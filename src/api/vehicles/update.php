<?php
header('Content-Type: application/json');
require_once '../../config/Database.php';

$database = new Database();
$db = $database->connect();

$data = json_decode(file_get_contents("php://input"));

if (empty($data->id)) {
    echo json_encode(['message' => 'Missing vehicle ID']);
    exit;
}

$query = "UPDATE vehicles SET model=:model, plate_no=:plate_no, price_per_day=:price_per_day, status=:status WHERE id=:id";
$stmt = $db->prepare($query);
$stmt->bindParam(":model", $data->model);
$stmt->bindParam(":plate_no", $data->plate_no);
$stmt->bindParam(":price_per_day", $data->price_per_day);
$stmt->bindParam(":status", $data->status);
$stmt->bindParam(":id", $data->id);
$stmt->execute();

echo json_encode(['message' => 'Vehicle updated successfully']);
?>
