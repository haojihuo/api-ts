<?php
declare(strict_types=1);

namespace app\common;

use PDO;

class Db
{
    private static PDO $pdo;

    public static function init(array $config): void
    {
        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $config['host'], $config['port'], $config['database'], $config['charset']);
        self::$pdo = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    public static function pdo(): PDO
    {
        return self::$pdo;
    }
}
