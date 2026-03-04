<?php
namespace app\middleware\perm;

use app\middleware\PermissionMiddleware;

class DepartmentManageMiddleware extends PermissionMiddleware
{
    public static string $permission = 'department.manage';
}
