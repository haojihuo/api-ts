<?php
declare(strict_types=1);

namespace app\service;

use app\common\Db;

class AuthService
{
    public function login(string $username, string $password): ?array
    {
        $stmt = Db::pdo()->prepare('SELECT * FROM users WHERE username = :username AND status = 1 LIMIT 1');
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();
        if (!$user || !password_verify($password, $user['password'])) {
            return null;
        }
        return $user;
    }
}
