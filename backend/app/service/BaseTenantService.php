<?php
declare(strict_types=1);

namespace app\service;

use app\common\Db;

class BaseTenantService
{
    protected function allByCompany(string $table, int $companyId): array
    {
        $stmt = Db::pdo()->prepare("SELECT * FROM {$table} WHERE company_id = :company_id ORDER BY created_at DESC");
        $stmt->execute(['company_id' => $companyId]);
        return $stmt->fetchAll();
    }

    protected function createByCompany(string $table, array $payload, int $companyId): int
    {
        $payload['company_id'] = $companyId;
        $keys = array_keys($payload);
        $fields = implode(',', $keys);
        $binds = implode(',', array_map(fn($k) => ':' . $k, $keys));
        $stmt = Db::pdo()->prepare("INSERT INTO {$table} ({$fields}) VALUES ({$binds})");
        $stmt->execute($payload);
        return (int)Db::pdo()->lastInsertId();
    }
}
