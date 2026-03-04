<?php
declare(strict_types=1);

namespace app\service;

class EmployeeService extends BaseTenantService
{
    public function list(int $companyId): array
    {
        return $this->allByCompany('employees', $companyId);
    }

    public function create(array $payload, int $companyId): int
    {
        return $this->createByCompany('employees', $payload, $companyId);
    }
}
