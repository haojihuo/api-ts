<?php
declare(strict_types=1);

namespace app\controller;

use app\common\Request;
use app\common\Response;
use app\service\PermissionService;

class PermissionController extends BaseController
{
    public function mine(): void
    {
        $user = Request::user();
        if (!$user) {
            Response::json(401, 'unauthorized', null, 401);
            return;
        }
        $keys = (new PermissionService())->userPermissions((int)$user['user_id'], (int)$user['role_id']);
        Response::json(0, 'success', $keys);
    }
}
