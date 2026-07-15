<?php
require_once "../../config/cors.php";
require_once "../../config/env.php";
require_once "../../config/db.php";
require_once "../../services/AuthService.php";
require_once "../../services/EmailService.php";
require_once "../../exceptions/AppException.php";
require_once "../../helpers/JsonHelper.php";

use App\Services\AuthService;
use App\Services\EmailService;
use App\Exceptions\AppException;
use App\Helpers\JsonHelper;

global $conn;

try {
    $rawInput = file_get_contents("php://input");
    $jsonData = json_decode($rawInput, true);
    $input = (!empty($jsonData) && is_array($jsonData)) ? $jsonData : $_POST;

    if (empty($input['email'])) {
        throw new AppException("Email address is required.", 400);
    }

    $email = trim($input['email']);

    $stmt = $conn->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows !== 1) {
        JsonHelper::success("If this account exists, an OTP code has been dispatched.");
    }

    $otpCode = AuthService::createOTP($email, 'password_reset', $conn);
    EmailService::sendOTP($email, $otpCode, 'password_reset');

    JsonHelper::success("OTP code sent successfully.", ["email" => $email]);

} catch (AppException $e) {
    JsonHelper::send(false, $e->getMessage(), null, null, $e->getStatusCode());
} catch (Exception $e) {
    JsonHelper::internalError("Failed to trigger reset flow.");
}