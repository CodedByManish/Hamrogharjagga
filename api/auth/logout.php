<?php

require_once "../../config/cors.php";

session_start();
session_unset();
session_destroy();

echo json_encode([
    "success" => true,
    "message" => "Logged out successfully!",
    "redirectTo" => "index.php"
]);
exit;