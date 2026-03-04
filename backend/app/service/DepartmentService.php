<?php
declare(strict_types=1);

namespace app\service;

use app\common\Db;

class DepartmentService extends BaseTenantService
{
    public function list(int $companyId): array
    {
        $stmt = Db::pdo()->prepare('SELECT * FROM departments WHERE company_id = :company_id ORDER BY sort ASC, dept_id ASC');
        $stmt->execute(['company_id' => $companyId]);
        return $stmt->fetchAll();
    }

    public function tree(int $companyId): array
    {
        $rows = $this->list($companyId);
        return $this->buildTree($rows, 0);
    }

    public function create(array $payload, int $companyId): int
    {
        $parentId = (int)($payload['parent_id'] ?? 0);
        $parentPath = '';
        $level = 1;

        if ($parentId > 0) {
            $stmt = Db::pdo()->prepare('SELECT path, level FROM departments WHERE dept_id = :dept_id AND company_id = :company_id LIMIT 1');
            $stmt->execute(['dept_id' => $parentId, 'company_id' => $companyId]);
            $parent = $stmt->fetch();
            if ($parent) {
                $parentPath = (string)$parent['path'];
                $level = (int)$parent['level'] + 1;
            }
        }

        $id = $this->createByCompany('departments', [
            'dept_name' => $payload['dept_name'] ?? '',
            'parent_id' => $parentId,
            'sort' => (int)($payload['sort'] ?? 0),
            'level' => $level,
            'path' => '',
        ], $companyId);

        $path = $parentPath ? ($parentPath . '-' . $id) : (string)$id;
        $stmt = Db::pdo()->prepare('UPDATE departments SET path = :path WHERE dept_id = :dept_id AND company_id = :company_id');
        $stmt->execute(['path' => $path, 'dept_id' => $id, 'company_id' => $companyId]);

        return $id;
    }

    public function subtreeIds(int $companyId, int $deptId): array
    {
        $stmt = Db::pdo()->prepare('SELECT path FROM departments WHERE dept_id = :dept_id AND company_id = :company_id LIMIT 1');
        $stmt->execute(['dept_id' => $deptId, 'company_id' => $companyId]);
        $row = $stmt->fetch();
        if (!$row) {
            return [$deptId];
        }

        $stmt = Db::pdo()->prepare('SELECT dept_id FROM departments WHERE company_id = :company_id AND (dept_id = :dept_id OR path LIKE :path_like)');
        $stmt->execute([
            'company_id' => $companyId,
            'dept_id' => $deptId,
            'path_like' => $row['path'] . '-%'
        ]);

        return array_map('intval', array_column($stmt->fetchAll(), 'dept_id'));
    }

    private function buildTree(array $rows, int $parentId): array
    {
        $nodes = [];
        foreach ($rows as $row) {
            if ((int)$row['parent_id'] === $parentId) {
                $row['children'] = $this->buildTree($rows, (int)$row['dept_id']);
                $nodes[] = $row;
            }
        }
        return $nodes;
    }
}
