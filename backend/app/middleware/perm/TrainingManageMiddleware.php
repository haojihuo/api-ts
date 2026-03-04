<?php
namespace app\middleware\perm;

use app\middleware\PermissionMiddleware;

class TrainingManageMiddleware extends PermissionMiddleware
{
    public static string $permission = 'training.manage';
}
