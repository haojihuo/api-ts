<?php
declare(strict_types=1);

namespace app\controller;

use app\common\Request;
use app\common\Response;
use app\service\DepartmentService;

class DepartmentController extends BaseController
{
    public function index(): void
    {
        $list = (new DepartmentService())->list($this->companyId());
        Response::json(0, 'success', $list);
    }

    public function tree(): void
    {
        $tree = (new DepartmentService())->tree($this->companyId());
        Response::json(0, 'success', $tree);
    }

    public function store(): void
    {
        $input = Request::input();
        $id = (new DepartmentService())->create([
            'dept_name' => strip_tags((string)($input['dept_name'] ?? '')),
            'parent_id' => (int)($input['parent_id'] ?? 0),
            'sort' => (int)($input['sort'] ?? 0),
        ], $this->companyId());
        Response::json(0, 'success', ['dept_id' => $id]);
    }
}
