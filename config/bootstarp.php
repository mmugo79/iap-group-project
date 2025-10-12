<?php
require_once __DIR__ . '/Database.php';

spl_autoload_register(function($class){
    $file = __DIR__ . '/../' . str_replace('\\','/',$class) . '.php';
    if(file_exists($file)){
        require $file;
    }
});

use Config\Database;

$db = (new Database())->getConnection();

// helper: sendMail using PHPMailer
function sendMail($to, $subject, $body){
    // simple wrapper - adjust SMTP settings below
    require_once __DIR__ . '/../../vendor/autoload.php'; // PHPMailer via composer
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com';     // e.g. smtp.gmail.com
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@example.com';
        $mail->Password = 'your-email-password';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('no-reply@vehiclepro.local', 'VehiclePro');
        $mail->addAddress($to);

        // Content
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('Mail error: ' . $mail->ErrorInfo);
        return false;
    }
}
