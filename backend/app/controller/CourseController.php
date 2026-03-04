<?php
declare(strict_types=1);

namespace app\controller;

use app\common\Request;
use app\common\Response;
use app\service\CourseService;

class CourseController extends BaseController
{
    public function index(): void
    {
        Response::json(0, 'success', (new CourseService())->list($this->companyId()));
    }

    public function store(): void
    {
        $input = Request::input();
        $id = (new CourseService())->create([
            'course_name' => strip_tags((string)($input['course_name'] ?? '')),
            'course_type' => strip_tags((string)($input['course_type'] ?? '')),
            'content' => strip_tags((string)($input['content'] ?? '')),
        ], $this->companyId());
        Response::json(0, 'success', ['course_id' => $id]);
    }
}
