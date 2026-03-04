<?php
declare(strict_types=1);

namespace app\service;

use app\common\Db;

class EmployeeService extends BaseTenantService
{
    public function list(int $companyId, array $user): array
    {
        $roleLevel = (int)($user['role_level'] ?? 10);
        if ($roleLevel <= 2) {
            return $this->allByCompany('employees', $companyId);
        }

        $deptId = (int)($user['dept_id'] ?? 0);
        if ($deptId <= 0) {
            return [];
        }

        $deptIds = (new DepartmentService())->subtreeIds($companyId, $deptId);
        if (!$deptIds) {
            return [];
        }

        $in = implode(',', array_fill(0, count($deptIds), '?'));
        $sql = "SELECT * FROM employees WHERE company_id = ? AND dept_id IN ({$in}) ORDER BY created_at DESC";
        $stmt = Db::pdo()->prepare($sql);
        $params = array_merge([$companyId], $deptIds);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function create(array $payload, int $companyId): int
    {
        return $this->createByCompany('employees', $payload, $companyId);
    }
}
