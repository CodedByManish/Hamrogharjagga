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
}