<?php
declare(strict_types=1);

namespace app\middleware;

use app\common\Request;
use app\common\Response;
use app\service\PermissionService;

class PermissionMiddleware
{
    public static string $permission = '';

    public static function handle(): void
    {
        $user = Request::user();
        if (!$user) {
            Response::json(401, 'unauthorized', null, 401);
            exit;
        }

        $keys = (new PermissionService())->userPermissions((int)$user['user_id'], (int)$user['role_id']);
        if (!in_array(static::$permission, $keys, true)) {
            Response::json(403, 'forbidden', null, 403);
            exit;
        }
    }
}
