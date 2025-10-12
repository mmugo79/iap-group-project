<?php
require_once __DIR__ . '/../../config/bootstrap.php';
use Models\User;

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if(!$email || !$password){ http_response_code(400); echo json_encode(['error'=>'missing']); exit; }

$userModel = new User($db);
$user = $userModel->findByEmail($email);
if(!$user){ http_response_code(401); echo json_encode(['error'=>'invalid']); exit; }

if(!password_verify($password, $user['password'])){ http_response_code(401); echo json_encode(['error'=>'invalid']); exit; }

if(!$user['verified']){
    http_response_code(403); echo json_encode(['error'=>'not_verified']); exit;
}

// create session or return a simple token (for demo we'll use PHP session)
session_start();
$_SESSION['user_id'] = $user['id'];
$_SESSION['name'] = $user['name'];
$_SESSION['role'] = $user['role'];

echo json_encode(['success'=>true, 'user'=>['id'=>$user['id'],'name'=>$user['name'],'email'=>$user['email']]]);
