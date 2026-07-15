<?php

namespace App\Services;

use App\Config\Env;

class AuthService {
    public static function generateSignedToken(int $userId, string $role): string {
        $secret = Env::get('JWT_SECRET', 'fallback_secret');
        $payload = json_encode([
            'user_id' => $userId,
            'role'    => $role,
            'exp'     => time() + (3600 * 24)
        ]);

        $encodedPayload = rtrim(strtr(base64_encode($payload), '+/', '-_'), '=');
        $signature = hash_hmac('sha256', $encodedPayload, $secret);

        return $encodedPayload . '.' . $signature;
    }
    public static function verifyPassword(string $inputPassword, string $hashedPassword): bool {
        return password_verify($inputPassword, $hashedPassword);
    }

    public static function createOTP(string $email, string $purpose, \mysqli $conn): string {
        $code = (string)random_int(100000, 999999);
        $expiresAt = date('Y-m-d H:i:s', time() + 120);

        $clearStmt = $conn->prepare("DELETE FROM otps WHERE email = ? AND purpose = ?");
        $clearStmt->bind_param("ss", $email, $purpose);
        $clearStmt->execute();

        $stmt = $conn->prepare("INSERT INTO otps (email, code, purpose, expires_at) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $email, $code, $purpose, $expiresAt);
        $stmt->execute();

        return $code;
    }

    public static function verifyOTP(string $email, string $code, string $purpose, mysqli $conn): bool {
        $stmt = $conn->prepare("SELECT id FROM otps WHERE email = ? AND code = ? AND purpose = ? AND expires_at > NOW() LIMIT 1");
        $stmt->bind_param("sss", $email, $code, $purpose);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $deleteStmt = $conn->prepare("DELETE FROM otps WHERE email = ? AND purpose = ?");
            $deleteStmt->bind_param("ss", $email, $purpose);
            $deleteStmt->execute();
            return true;
        }

        return false;
    }
}