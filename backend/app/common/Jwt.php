<?php
declare(strict_types=1);

namespace app\common;

class Jwt
{
    public static function encode(array $payload): string
    {
        $config = require __DIR__ . '/../../config/app.php';
        $header = self::base64UrlEncode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload['exp'] = time() + $config['jwt_exp'];
        $body = self::base64UrlEncode(json_encode($payload));
        $signature = self::base64UrlEncode(hash_hmac('sha256', $header . '.' . $body, $config['jwt_secret'], true));

        return $header . '.' . $body . '.' . $signature;
    }

    public static function decode(string $token): ?array
    {
        $config = require __DIR__ . '/../../config/app.php';
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }
        [$header, $payload, $sign] = $parts;
        $expected = self::base64UrlEncode(hash_hmac('sha256', $header . '.' . $payload, $config['jwt_secret'], true));
        if (!hash_equals($expected, $sign)) {
            return null;
        }
        $decoded = json_decode(self::base64UrlDecode($payload), true);
        if (!is_array($decoded) || (($decoded['exp'] ?? 0) < time())) {
            return null;
        }

        return $decoded;
    }

    private static function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private static function base64UrlDecode(string $value): string
    {
        return base64_decode(strtr($value, '-_', '+/'));
    }
}
