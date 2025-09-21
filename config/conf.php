<?php
// conf.php
declare(strict_types=1);
session_start();

// === DB config
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');   // put your group’s MySQL password
define('DB_NAME', 'vehiclepro_db');
define('DB_PORT', 3306);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
$conn->set_charset('utf8mb4');
