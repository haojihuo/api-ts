<?php
declare(strict_types=1);

namespace app\service;

class DepartmentService extends BaseTenantService
{
    public function list(int $companyId): array
    {
        return $this->allByCompany('departments', $companyId);
    }

    public function create(array $payload, int $companyId): int
    {
        return $this->createByCompany('departments', $payload, $companyId);
    }
}
