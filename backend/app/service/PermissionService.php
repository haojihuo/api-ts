<?php
declare(strict_types=1);

namespace app\service;

use app\common\Db;

class PermissionService
{
    public function userPermissions(int $userId, int $roleId): array
    {
        $stmt = Db::pdo()->prepare('SELECT permission_key FROM user_permissions WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        $keys = array_column($stmt->fetchAll(), 'permission_key');
        if ($keys) {
            return $keys;
        }

        $sql = 'SELECT p.permission_key FROM role_permissions rp
                JOIN permissions p ON p.permission_id = rp.permission_id
                WHERE rp.role_id = :role_id';
        $stmt = Db::pdo()->prepare($sql);
        $stmt->execute(['role_id' => $roleId]);
        $keys = array_column($stmt->fetchAll(), 'permission_key');

        if ($keys) {
            $insert = Db::pdo()->prepare('INSERT IGNORE INTO user_permissions(user_id, permission_key) VALUES(:user_id, :permission_key)');
            foreach ($keys as $key) {
                $insert->execute(['user_id' => $userId, 'permission_key' => $key]);
            }
        }

        return $keys;
    }
}
