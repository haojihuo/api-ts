<?php
namespace app\middleware\perm;

use app\middleware\PermissionMiddleware;

class CourseManageMiddleware extends PermissionMiddleware
{
    public static string $permission = 'course.manage';
}
