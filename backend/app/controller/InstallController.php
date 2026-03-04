<?php
declare(strict_types=1);

namespace app\controller;

use app\common\Db;
use app\common\Response;

class InstallController extends BaseController
{
    public function run(): void
    {
        $lockFile = __DIR__ . '/../../runtime/install.lock';
        if (is_file($lockFile)) {
            Response::json(1, 'already installed');
            return;
        }

        $sql = file_get_contents(__DIR__ . '/../../scripts/init.sql');
        if (!$sql) {
            Response::json(1, 'init.sql missing');
            return;
        }
        foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) {
            Db::pdo()->exec($statement);
        }

        $pwd = password_hash('Admin@123456', PASSWORD_BCRYPT);
        Db::pdo()->exec("INSERT IGNORE INTO roles(role_id, role_name, company_id) VALUES(1, '超级管理员', 10001)");
        Db::pdo()->exec("INSERT IGNORE INTO users(user_id, username, password, company_id, role_id) VALUES(1, 'admin', '{$pwd}', 10001, 1)");
        Db::pdo()->exec("INSERT IGNORE INTO role_permissions(role_id, permission_id) SELECT 1, permission_id FROM permissions");

        @mkdir(dirname($lockFile), 0777, true);
        file_put_contents($lockFile, date('c'));
        Response::json(0, 'installed', ['username' => 'admin', 'password' => 'Admin@123456']);
    }
}
