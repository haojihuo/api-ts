<?php
declare(strict_types=1);

namespace app\service;

class CourseService extends BaseTenantService
{
    public function list(int $companyId): array
    {
        return $this->allByCompany('courses', $companyId);
    }

    public function create(array $payload, int $companyId): int
    {
        return $this->createByCompany('courses', $payload, $companyId);
    }
}
