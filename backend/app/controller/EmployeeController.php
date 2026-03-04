<?php
declare(strict_types=1);

namespace app\controller;

use app\common\Request;
use app\common\Response;
use app\service\EmployeeService;

class EmployeeController extends BaseController
{
    public function index(): void
    {
        Response::json(0, 'success', (new EmployeeService())->list($this->companyId(), Request::user() ?? []));
    }

    public function store(): void
    {
        $input = Request::input();
        $id = (new EmployeeService())->create([
            'name' => strip_tags((string)($input['name'] ?? '')),
            'phone' => (string)($input['phone'] ?? ''),
            'dept_id' => (int)($input['dept_id'] ?? 0),
            'job_title' => strip_tags((string)($input['job_title'] ?? '')),
            'status' => (int)($input['status'] ?? 1),
        ], $this->companyId());
        Response::json(0, 'success', ['employee_id' => $id]);
    }
}
