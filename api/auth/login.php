<?php
require_once "../../config/cors.php";
require_once "../../config/env.php";
require_once "../../config/db.php";
require_once "../../enums/UserRole.php";
require_once "../../models/UserModel.php";
require_once "../../services/AuthService.php";
require_once "../../exceptions/AppException.php";
require_once "../../helpers/JsonHelper.php";

use App\Enums\UserRole;
use App\Models\UserModel;
use App\Services\AuthService;
use App\Exceptions\AppException;
use App\Helpers\JsonHelper;

global $conn;

try {
    $rawInput = file_get_contents("php://input");
    $jsonData = json_decode($rawInput, true);
    $input = (!empty($jsonData) && is_array($jsonData)) ? $jsonData : $_POST;

    if (!isset($input['email'], $input['password'], $input['role'])) {
        throw new AppException("Email, password, and role selection are required.", 400);
    }

    $email = trim($input['email']);
    $password = $input['password'];
    $role = trim($input['role']);

    $validRoles = UserRole::getAsArray();
    if (!isset($validRoles[$role])) {
        throw new AppException("Invalid user role selected.", 400);
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND role=? LIMIT 1");
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows !== 1) {
        throw new AppException("User not found or role is incorrect!", 401);
    }

    $userData = $result->fetch_assoc();
    $user = new UserModel($userData);

    if (!AuthService::verifyPassword($password, $user->password)) {
        throw new AppException("Invalid password!", 401);
    }

    $token = AuthService::generateSignedToken($user->id, $user->role);

    session_start();
    $_SESSION['userEmail'] = $user->email;
    $_SESSION['userName']  = $user->name;
    $_SESSION['userRole']  = $user->role;

    $redirectPage = ($user->role === UserRole::BUYER) ? "find_property.php" : "manage_property.php";

    JsonHelper::success("Login successful!", ["token" => $token], $redirectPage);

} catch (AppException $e) {
    JsonHelper::send(false, $e->getMessage(), null, null, $e->getStatusCode());
} catch (Exception $e) {
    JsonHelper::internalError("An unexpected server failure occurred.");
}