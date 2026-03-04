<?php
declare(strict_types=1);

namespace app\middleware;

use app\common\Db;
use app\common\Request;
use app\common\Response;

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

        $sql = 'SELECT p.permission_key FROM role_permissions rp
                JOIN permissions p ON p.permission_id = rp.permission_id
                WHERE rp.role_id = :role_id';
        $stmt = Db::pdo()->prepare($sql);
        $stmt->execute(['role_id' => $user['role_id']]);
        $keys = array_column($stmt->fetchAll(), 'permission_key');
        if (!in_array(static::$permission, $keys, true)) {
            Response::json(403, 'forbidden', null, 403);
            exit;
        }
    }
}
