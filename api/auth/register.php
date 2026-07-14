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

$rawInput = file_get_contents("php://input");
$jsonData = json_decode($rawInput, true);

$input = (!empty($jsonData) && is_array($jsonData)) ? $jsonData : $_POST;

$name            = isset($input['name']) ? trim($input['name']) : '';
$email           = isset($input['email']) ? trim($input['email']) : '';
$password        = isset($input['password']) ? $input['password'] : '';
$confirmPassword = isset($input['confirm_password']) ? $input['confirm_password'] : '';
$role            = isset($input['role']) ? trim($input['role']) : '';

if (empty($name) || empty($email) || empty($password) || empty($confirmPassword) || empty($role)) {
    echo json_encode([
        "success" => false,
        "message" => "All registration fields are required."
    ]);
    exit;
}

if ($password !== $confirmPassword) {
    echo json_encode([
        "success" => false,
        "message" => "Passwords do not match!"
    ]);
    exit;
}

$validRoles = UserRole::getAsArray();
if (!isset($validRoles[$role])) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid user role selected."
    ]);
    exit;
}

$user = new UserModel([
    'name'     => $name,
    'email'    => $email,
    'password' => password_hash($password, PASSWORD_BCRYPT),
    'role'     => $role
]);

$stmt = $conn->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
$stmt->bind_param("s", $user->email);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    echo json_encode([
        "success" => false,
        "message" => "Email already exists!"
    ]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
$stmt->bind_param("ssss", $user->name, $user->email, $user->password, $user->role);

if ($stmt->execute()) {
    $user->id = $conn->insert_id;

    $token = AuthService::generateSignedToken($user->id, $user->role);

    session_start();
    $_SESSION['userEmail'] = $user->email;
    $_SESSION['userName']  = $user->name;
    $_SESSION['role']  = $user->role;

    echo json_encode([
        "success" => true,
        "message" => "Registration successful!",
        "token"   => $token,
        "redirectTo" => ($user->role === UserRole::BUYER ? "find_property.php" : "manage_property.php")
    ]);
} else {
    echo json_encode(["
        success" => false,
        "message" => "Database execution failure. Try again!"
    ]);
}
exit;