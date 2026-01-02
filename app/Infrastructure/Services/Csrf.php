<?php

namespace App\Infrastructure\Services;

class Csrf
{
    private const TOKEN_KEY = '_csrf_token';

    public static function generate(): string
    {
        if (empty($_SESSION[self::TOKEN_KEY])) {
            $_SESSION[self::TOKEN_KEY] = bin2hex(random_bytes(32));
        }
        return $_SESSION[self::TOKEN_KEY];
    }

    public static function tokenField(): string
    {
        $token = self::generate();
        return sprintf('<input type="hidden" name="%s" value="%s">', self::TOKEN_KEY, htmlspecialchars($token, ENT_QUOTES, 'UTF-8'));
    }

    public static function validate(?string $token): bool
    {
        if (empty($_SESSION[self::TOKEN_KEY]) || empty($token)) {
            return false;
        }
        return hash_equals($_SESSION[self::TOKEN_KEY], $token);
    }
}
