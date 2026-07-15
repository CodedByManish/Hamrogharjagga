<?php
require_once "../../config/cors.php";
require_once "../../config/env.php";
require_once "../../config/db.php";
require_once "../../enums/UserRole.php";
require_once "../../models/UserModel.php";
require_once "../../services/AuthService.php";

use App\Enums\UserRole;
use App\Models\UserModel;
use App\Services\AuthService;

global $conn;

$input = json_decode(file_get_contents("php://input"), true) ?? $_POST;

if (!isset($input['email'], $input['password'], $input['role'])) {
    echo json_encode(["success" => false, "message" => "Email, password, and role selection are required."]);
    exit;
}

$email = trim($input['email']);
$password = $input['password'];
$role = trim($input['role']);

$validRoles = UserRole::getAsArray();
if (!isset($validRoles[$role])) {
    echo json_encode(["success" => false, "message" => "Invalid user role selected."]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND role=? LIMIT 1");
$stmt->bind_param("ss", $email, $role);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo json_encode(["success" => false, "message" => "User not found or role is incorrect!"]);
    exit;
}

$userData = $result->fetch_assoc();
$user = new UserModel($userData);

if (!AuthService::verifyPassword($password, $user->password)) {
    echo json_encode(["success" => false, "message" => "Invalid password!"]);
    exit;
}

$token = AuthService::generateSignedToken($user->id, $user->role);

session_start();
$_SESSION['userEmail'] = $user->email;
$_SESSION['userName']  = $user->name;
$_SESSION['userRole']  = $user->role;

echo json_encode([
    "success"    => true,
    "message"    => "Login successful!",
    "token"      => $token,
    "redirectTo" => ($user->role === UserRole::BUYER ? "find_property.php" : "manage_property.php")
]);
exit;