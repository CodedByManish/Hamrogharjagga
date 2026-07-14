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

if (!isset($input['name'], $input['email'], $input['password'], $input['confirm_password'], $input['registerRole'])) {
    echo json_encode([
        "success" => false,
        "message" => "All registration fields are required."
    ]);
    exit;
}

if ($input['password'] !== $input['confirm_password']) {
    echo json_encode([
        "success" => false,
        "message" => "Passwords Incorrect!"
    ]);
    exit;
}

$validRoles = UserRole::getAsArray();
$role = trim($input['registerRole']);

if (!isset($validRoles[$role])) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid user role selected."
    ]);
    exit;
}

$user = new UserModel([
    'name'     => trim($input['name']),
    'email'    => trim($input['email']),
    'password' => password_hash($input['password'], PASSWORD_BCRYPT),
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

    $token = AuthService::generateToken($user->id, $user->role);

    session_start();
    $_SESSION['userEmail'] = $user->email;
    $_SESSION['userName']  = $user->name;
    $_SESSION['userRole']  = $user->role;

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