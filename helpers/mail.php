<?php
// Turn on all error reporti
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔧 PHPMailer Debug Test</h2>";
echo "<div style='font-family: monospace; background: #f5f5f5; padding: 20px;'>";

// Include PHPMailer
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$mail = new PHPMailer(true);

try {
    echo "1. 📦 PHPMailer instance created... ✅<br>";
    
    // Enable verbose debug output
    $mail->SMTPDebug = SMTP::DEBUG_SERVER; // This shows detailed debug info
    $mail->Debugoutput = function($str, $level) {
        echo "🔍 DEBUG: $str<br>";
    };

    // Server settings
    $mail->isSMTP();
    echo "2. 📡 SMTP mode enabled... ✅<br>";
    
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'mugomoses506@gmail.com';
    $mail->Password = 'xuen rclr uprp qehw';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    
    echo "3. ⚙️ SMTP configured (smtp.gmail.com:587)... ✅<br>";
    
    // Recipients
    $mail->setFrom('mugomoses506@gmail.com', 'Vehicle Pro Portal');
    $mail->addAddress('mugomoses506@gmail.com', 'Moses');
    
    echo "4. 📧 Recipients set... ✅<br>";
    
    // Content
    $mail->isHTML(true);
    $mail->Subject = 'PHPMailer Test - Vehicle Pro Portal';
    $mail->Body = '<h1>Test Email</h1><p>This is a test from PHPMailer.</p>';
    $mail->AltBody = 'This is a test from PHPMailer.';
    
    echo "5. 📝 Content prepared... ✅<br>";
    echo "6. 🚀 Attempting to send email...<br>";
    echo "<hr>";

    // Send email
    if($mail->send()) {
        echo "<hr>";
        echo "<div style='color: green; font-weight: bold; padding: 10px; background: #d4ffd4;'>";
        echo "🎉 SUCCESS: Email sent successfully!";
        echo "</div>";
        echo "<p>Check your email: mugomoses506@gmail.com</p>";
    } else {
        echo "<hr>";
        echo "<div style='color: red; font-weight: bold; padding: 10px; background: #ffd4d4;'>";
        echo "❌ FAILED: Send method returned false";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<hr>";
    echo "<div style='color: red; font-weight: bold; padding: 10px; background: #ffd4d4;'>";
    echo "💥 EXCEPTION: " . $e->getMessage();
    echo "</div>";
    echo "<p><strong>Error Info:</strong> " . $mail->ErrorInfo . "</p>";
}

echo "</div>";

// Additional debug info
echo "<h3>📋 System Information:</h3>";
echo "<ul>";
echo "<li>PHP Version: " . phpversion() . "</li>";
echo "<li>PHPMailer Version: " . PHPMailer::VERSION . "</li>";
echo "<li>OpenSSL: " . (extension_loaded('openssl') ? '✅ Enabled' : '❌ Disabled') . "</li>";
echo "<li>Sockets: " . (extension_loaded('sockets') ? '✅ Enabled' : '❌ Disabled') . "</li>";
echo "</ul>";
?>
