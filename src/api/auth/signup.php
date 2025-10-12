<?php
require_once __DIR__ . '/../../config/bootstrap.php';

use Models\User;
use Models\OTP;

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) { http_response_code(400); echo json_encode(['error'=>'Invalid input']); exit; }

$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

if (!$name || !$email || !$password){
    http_response_code(400); echo json_encode(['error'=>'missing fields']); exit;
}

// check existing
$userModel = new User($db);
$existing = $userModel->findByEmail($email);
if($existing){
    http_response_code(409); echo json_encode(['error'=>'email exists']); exit;
}

// create user
$hash = password_hash($password, PASSWORD_BCRYPT);
$userModel->create($name, $email, $hash);

// fetch created user id
$created = $userModel->findByEmail($email);

// generate OTP
$code = rand(100000,999999);
$expires = date('Y-m-d H:i:s', strtotime('+15 minutes'));
$otpModel = new OTP($db);
$otpModel->create($created['id'], $code, 'verify', $expires);

// send email (use sendMail function)
if(sendMail($email, "Your VehiclePro verification code", "Your code: $code")) {
    echo json_encode(['success'=>true, 'user_id'=>$created['id']]);
} else {
    http_response_code(500); echo json_encode(['error'=>'could not send email']);
}
