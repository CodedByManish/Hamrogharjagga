<?php

require_once __DIR__ . '/env.php';

use App\Config\Env;

$host = Env::get('DB_HOST', 'localhost');
$user = Env::get('DB_USER', 'root');
$pass = Env::get('DB_PASS', '');
$db = Env::get('DB_NAME', 'HamroGharJagg');

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB Connection failed."]);
    exit;
}