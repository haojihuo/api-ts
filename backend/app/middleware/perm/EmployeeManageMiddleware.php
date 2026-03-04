<?php
namespace app\middleware\perm;

use app\middleware\PermissionMiddleware;

class EmployeeManageMiddleware extends PermissionMiddleware
{
    public static string $permission = 'employee.manage';
}
