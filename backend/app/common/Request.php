<?php
declare(strict_types=1);

namespace app\common;

class Request
{
    public static function input(): array
    {
        $raw = file_get_contents('php://input');
        $json = json_decode($raw, true);
        return is_array($json) ? $json : $_POST;
    }

    public static function bearerToken(): ?string
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (preg_match('/Bearer\s+(.*)$/i', $header, $matches)) {
            return $matches[1];
        }
        return null;
    }

    public static function user(): ?array
    {
        return $GLOBALS['auth_user'] ?? null;
    }
}
