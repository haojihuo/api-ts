<?php
declare(strict_types=1);

namespace app\middleware;

use app\common\Jwt;
use app\common\Request;
use app\common\Response;

class AuthMiddleware
{
    public static function handle(): void
    {
        $token = Request::bearerToken();
        if (!$token) {
            Response::json(401, 'unauthorized', null, 401);
            exit;
        }
        $payload = Jwt::decode($token);
        if (!$payload) {
            Response::json(401, 'token invalid', null, 401);
            exit;
        }
        $GLOBALS['auth_user'] = $payload;
    }
}
