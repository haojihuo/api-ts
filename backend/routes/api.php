<?php
declare(strict_types=1);

use app\common\Router;
use app\controller\AuthController;
use app\controller\CourseController;
use app\controller\DepartmentController;
use app\controller\EmployeeController;
use app\controller\InstallController;
use app\controller\TrainingController;
use app\middleware\AuthMiddleware;
use app\middleware\perm\CourseManageMiddleware;
use app\middleware\perm\DepartmentManageMiddleware;
use app\middleware\perm\EmployeeManageMiddleware;
use app\middleware\perm\TrainingManageMiddleware;

$router = new Router();

$router->add('GET', '/install', [InstallController::class, 'run']);
$router->add('POST', '/api/login', [AuthController::class, 'login']);
$router->add('GET', '/api/profile', [AuthController::class, 'profile'], [AuthMiddleware::class]);

$router->add('GET', '/api/departments', [DepartmentController::class, 'index'], [AuthMiddleware::class, DepartmentManageMiddleware::class]);
$router->add('POST', '/api/departments', [DepartmentController::class, 'store'], [AuthMiddleware::class, DepartmentManageMiddleware::class]);

$router->add('GET', '/api/employees', [EmployeeController::class, 'index'], [AuthMiddleware::class, EmployeeManageMiddleware::class]);
$router->add('POST', '/api/employees', [EmployeeController::class, 'store'], [AuthMiddleware::class, EmployeeManageMiddleware::class]);

$router->add('GET', '/api/courses', [CourseController::class, 'index'], [AuthMiddleware::class, CourseManageMiddleware::class]);
$router->add('POST', '/api/courses', [CourseController::class, 'store'], [AuthMiddleware::class, CourseManageMiddleware::class]);

$router->add('GET', '/api/training-records', [TrainingController::class, 'index'], [AuthMiddleware::class, TrainingManageMiddleware::class]);
$router->add('POST', '/api/training-records', [TrainingController::class, 'store'], [AuthMiddleware::class, TrainingManageMiddleware::class]);

return $router;
