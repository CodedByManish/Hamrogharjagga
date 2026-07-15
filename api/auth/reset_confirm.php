<?php
require_once "../../config/cors.php";
require_once "../../config/env.php";
require_once "../../config/db.php";
require_once "../../services/AuthService.php";
require_once "../../exceptions/AppException.php";
require_once "../../helpers/JsonHelper.php";

use App\Services\AuthService;
use App\Exceptions\AppException;
use App\Helpers\JsonHelper;

global $conn;

try {
    $rawInput = file_get_contents("php://input");
    $jsonData = json_decode($rawInput, true);
    $input = (!empty($jsonData) && is_array($jsonData)) ? $jsonData : $_POST;

    if (empty($input['email']) || empty($input['code']) || empty($input['new_password']) || empty($input['confirm_password'])) {
        throw new AppException("All validation fields are required.", 400);
    }

    $email = trim($input['email']);
    $code = trim($input['code']);
    $newPassword = $input['new_password'];
    $confirmPassword = $input['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        throw new AppException("Passwords do not match!", 400);
    }

    $isValid = AuthService::verifyOTP($email, $code, 'password_reset', $conn);
    if (!$isValid) {
        throw new AppException("Invalid or expired OTP code.", 400);
    }

    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashedPassword, $email);
    $stmt->execute();

    JsonHelper::success("Password reset completed successfully!", null, "login_register.php");

} catch (AppException $e) {
    JsonHelper::send(false, $e->getMessage(), null, null, $e->getStatusCode());
} catch (Exception $e) {
    JsonHelper::internalError("Internal execution failure during password migration.");
}