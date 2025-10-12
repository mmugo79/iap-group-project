<?php
header('Content-Type: application/json');
require_once '../../config/Database.php';
require_once '../../models/User.php';

$database = new Database();
$db = $database->connect();

$user = new User($db);
$count = $user->countAll();

echo json_encode(['total_users' => $count]);
?>
