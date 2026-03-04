<?php
declare(strict_types=1);

namespace app\controller;

use app\common\Jwt;
use app\common\Request;
use app\common\Response;
use app\service\AuthService;

class AuthController extends BaseController
{
    public function login(): void
    {
        $input = Request::input();
        $user = (new AuthService())->login($input['username'] ?? '', $input['password'] ?? '');
        if (!$user) {
            Response::json(1, '用户名或密码错误');
            return;
        }

        $token = Jwt::encode([
            'user_id' => (int)$user['user_id'],
            'company_id' => (int)$user['company_id'],
            'role_id' => (int)$user['role_id'],
            'dept_id' => (int)$user['dept_id'],
            'role_level' => (int)$user['role_level'],
            'username' => $user['username'],
        ]);

        Response::json(0, 'success', ['token' => $token]);
    }

    public function profile(): void
    {
        Response::json(0, 'success', Request::user());
    }
}
