<?php
require_once __DIR__ . '/../../config/bootstrap.php';
use Models\User;
use Models\OTP;

$data = json_decode(file_get_contents('php://input'), true);
$user_id = $data['user_id'] ?? null;
$code = $data['code'] ?? null;

if(!$user_id || !$code){ http_response_code(400); echo json_encode(['error'=>'missing']); exit; }

$otp = new OTP($db);
$found = $otp->validate($user_id, $code, 'verify');
if(!$found){ http_response_code(400); echo json_encode(['error'=>'invalid or expired']); exit; }

$otp->markUsed($found['id']);
$userModel = new User($db);
$userModel->markVerified($user_id);

echo json_encode(['success'=>true]);
