<?php
declare(strict_types=1);

namespace app\controller;

use app\common\Request;
use app\common\Response;
use app\service\TrainingService;

class TrainingController extends BaseController
{
    public function index(): void
    {
        Response::json(0, 'success', (new TrainingService())->list($this->companyId()));
    }

    public function store(): void
    {
        $input = Request::input();
        $id = (new TrainingService())->create([
            'employee_id' => (int)($input['employee_id'] ?? 0),
            'course_id' => (int)($input['course_id'] ?? 0),
            'status' => strip_tags((string)($input['status'] ?? 'pending')),
            'completed_at' => $input['completed_at'] ?? null,
        ], $this->companyId());
        Response::json(0, 'success', ['training_id' => $id]);
    }
}
