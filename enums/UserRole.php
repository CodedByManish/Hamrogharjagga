<?php

namespace App\Enums;

class UserRole {
    public const string BUYER = 'buyer';
    public const string SELLER = 'seller';

    public static function getAsArray(): array {
        return [
            self::BUYER => self::BUYER,
            self::SELLER => self::SELLER,
        ];
    }
}