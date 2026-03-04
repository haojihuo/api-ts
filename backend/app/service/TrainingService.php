<?php
declare(strict_types=1);

namespace app\service;

class TrainingService extends BaseTenantService
{
    public function list(int $companyId): array
    {
        return $this->allByCompany('training_records', $companyId);
    }

    public function create(array $payload, int $companyId): int
    {
        return $this->createByCompany('training_records', $payload, $companyId);
    }
}
