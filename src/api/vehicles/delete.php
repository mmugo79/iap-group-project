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

$query = "DELETE FROM vehicles WHERE id=:id";
$stmt = $db->prepare($query);
$stmt->bindParam(":id", $data->id);
$stmt->execute();

echo json_encode(['message' => 'Vehicle deleted successfully']);
?>
