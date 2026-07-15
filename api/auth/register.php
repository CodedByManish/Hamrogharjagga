<?php
require_once "../../config/cors.php";
require_once "../../config/env.php";
require_once "../../config/db.php";
require_once "../../enums/UserRole.php";
require_once "../../models/UserModel.php";
require_once "../../services/AuthService.php";
require_once "../../services/EmailService.php";
require_once "../../exceptions/AppException.php";
require_once "../../helpers/JsonHelper.php";

use App\Enums\UserRole;
use App\Models\UserModel;
use App\Services\AuthService;
use App\Services\EmailService;
use App\Exceptions\AppException;
use App\Helpers\JsonHelper;

global $conn;

try {
    $rawInput = file_get_contents("php://input");
    $jsonData = json_decode($rawInput, true);
    $input = (!empty($jsonData) && is_array($jsonData)) ? $jsonData : $_POST;

    $name            = isset($input['name']) ? trim($input['name']) : '';
    $email           = isset($input['email']) ? trim($input['email']) : '';
    $password        = isset($input['password']) ? $input['password'] : '';
    $confirmPassword = isset($input['confirm_password']) ? $input['confirm_password'] : '';
    $role            = isset($input['role']) ? trim($input['role']) : '';

    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword) || empty($role)) {
        throw new AppException("All registration fields are required.", 400);
    }

    if ($password !== $confirmPassword) {
        throw new AppException("Passwords do not match!", 400);
    }

    $validRoles = UserRole::getAsArray();
    if (!isset($validRoles[$role])) {
        throw new AppException("Invalid user role selected.", 400);
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
        throw new AppException("Email already exists!", 409);
    }

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $user->name, $user->email, $user->password, $user->role);

    if (!$stmt->execute()) {
        throw new AppException("Database execution failure. Try again!", 500);
    }

    $otpCode = AuthService::createOTP($user->email, 'email_verification', $conn);
    EmailService::sendOTP($user->email, $otpCode, 'email_verification');

    JsonHelper::created("Registration successful! Please verify your email.", ["email" => $user->email], "verify_otp.php");

} catch (AppException $e) {
    JsonHelper::send(false, $e->getMessage(), null, null, $e->getStatusCode());
} catch (Exception $e) {
    JsonHelper::internalError("An unexpected error occurred: " . $e->getMessage());
}