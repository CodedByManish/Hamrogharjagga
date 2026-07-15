<?php

namespace App\Helpers;

class JsonHelper {

    public static function send(bool $success, string $message, $data = null, ?string $redirectTo = null, int $statusCode = 200): void {
        http_response_code($statusCode);
        
        echo json_encode([
            "success"    => $success,
            "message"    => $message,
            "data"       => $data,
            "redirectTo" => $redirectTo
        ]);
        exit;
    }

    public static function success(string $message, $data = null, ?string $redirectTo = null): void {
        self::send(true, $message, $data, $redirectTo, 200);
    }

    public static function created(string $message, $data = null, ?string $redirectTo = null): void {
        self::send(true, $message, $data, $redirectTo, 201);
    }

    public static function badRequest(string $message): void {
        self::send(false, $message, null, null, 400);
    }

    public static function unauthorized(string $message): void {
        self::send(false, $message, null, null, 401);
    }

    public static function forbidden(string $message): void {
        self::send(false, $message, null, null, 403);
    }

    public static function notFound(string $message): void {
        self::send(false, $message, null, null, 404);
    }

    public static function internalError(string $message): void {
        self::send(false, $message, null, null, 500);
    }
}