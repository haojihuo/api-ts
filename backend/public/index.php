<?php
declare(strict_types=1);

require_once __DIR__ . '/../app/common/bootstrap.php';

use app\common\Router;

$router = require __DIR__ . '/../routes/api.php';
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
