<?php
header('Content-Type: application/json');
require_once '../../config/Database.php';

$database = new Database();
$db = $database->connect();

$query = "SELECT * FROM vehicles ORDER BY id DESC";
$stmt = $db->prepare($query);
$stmt->execute();

$vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['vehicles' => $vehicles]);
?>
