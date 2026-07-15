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

    if (empty($input['email']) || empty($input['code'])) {
        throw new AppException("Email and OTP code are required.", 400);
    }

    $email = trim($input['email']);
    $code = trim($input['code']);

    $isValid = AuthService::verifyOTP($email, $code, 'email_verification', $conn);
    if (!$isValid) {
        throw new AppException("Invalid or expired OTP code.", 400);
    }

    $stmt = $conn->prepare("UPDATE users SET email_verified_at = NOW() WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = new UserModel($stmt->get_result()->fetch_assoc());

    $token = AuthService::generateSignedToken($user->id, $user->role);

    session_start();
    $_SESSION['userEmail'] = $user->email;
    $_SESSION['userName']  = $user->name;
    $_SESSION['userRole']  = $user->role;

    $redirectPage = ($user->role === UserRole::BUYER) ? "find_property.php" : "manage_property.php";

    JsonHelper::success("Email verified successfully!", ["token" => $token], $redirectPage);

} catch (AppException $e) {
    JsonHelper::send(false, $e->getMessage(), null, null, $e->getStatusCode());
} catch (Exception $e) {
    JsonHelper::internalError("Unexpected verification error.");
}