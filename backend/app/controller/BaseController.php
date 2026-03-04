<?php
declare(strict_types=1);

namespace app\controller;

use app\common\Request;

class BaseController
{
    protected function companyId(): int
    {
        return (int)(Request::user()['company_id'] ?? 0);
    }
}
