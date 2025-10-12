<?php
header('Content-Type: application/json');
require_once '../../config/Database.php';

$database = new Database();
$db = $database->connect();

$data = json_decode(file_get_contents("php://input"));

if (empty($data->user_id) || empty($data->vehicle_id) || empty($data->start_date) || empty($data->end_date)) {
    echo json_encode(['message' => 'Missing required fields']);
    exit;
}

$days = (strtotime($data->end_date) - strtotime($data->start_date)) / (60 * 60 * 24);
$queryPrice = $db->prepare("SELECT price_per_day FROM vehicles WHERE id = :id");
$queryPrice->bindParam(":id", $data->vehicle_id);
$queryPrice->execute();
$price = $queryPrice->fetchColumn();
$total_cost = $price * $days;

$query = "INSERT INTO reservations (user_id, vehicle_id, start_date, end_date, total_cost, status) VALUES (:user_id, :vehicle_id, :start_date, :end_date, :total_cost, 'pending')";
$stmt = $db->prepare($query);
$stmt->bindParam(":user_id", $data->user_id);
$stmt->bindParam(":vehicle_id", $data->vehicle_id);
$stmt->bindParam(":start_date", $data->start_date);
$stmt->bindParam(":end_date", $data->end_date);
$stmt->bindParam(":total_cost", $total_cost);
$stmt->execute();

echo json_encode(['message' => 'Reservation created successfully']);
?>
