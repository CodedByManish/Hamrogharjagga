<?php

namespace App\Config;

class Env {
    private static array $data = [];

    public static function load(string $path): void {
        if (!file_exists($path)) {
            return;
        }
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            list($key, $value) = explode('=', $line, 2);
            self::$data[trim($key)] = trim($value);
        }
    }

    public static function get(string $key, string $default = ''): string {
        return self::$data[$key] ?? $default;
    }
}

Env::load(__DIR__ . '/../.env');