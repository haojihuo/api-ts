<?php
declare(strict_types=1);

spl_autoload_register(function ($class) {
    $prefix = 'app\\';
    $baseDir = __DIR__ . '/../';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relative) . '.php';
    if (is_file($file)) {
        require_once $file;
    }
});

$config = require __DIR__ . '/../../config/database.php';
\app\common\Db::init($config);
