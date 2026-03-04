<?php
declare(strict_types=1);

namespace app\common;

class Response
{
    public static function json(int $code, string $msg, $data = null, int $httpCode = 200): void
    {
        http_response_code($httpCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['code' => $code, 'msg' => $msg, 'data' => $data], JSON_UNESCAPED_UNICODE);
    }
}
