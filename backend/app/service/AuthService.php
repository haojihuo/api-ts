<?php
declare(strict_types=1);

namespace app\service;

use app\common\Db;

class AuthService
{
    public function login(string $username, string $password): ?array
    {
        $sql = 'SELECT u.*, r.role_level
                FROM users u
                JOIN roles r ON r.role_id = u.role_id
                WHERE u.username = :username AND u.status = 1
                LIMIT 1';
        $stmt = Db::pdo()->prepare($sql);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();
        if (!$user || !password_verify($password, $user['password'])) {
            return null;
        }
        return $user;
    }
}
