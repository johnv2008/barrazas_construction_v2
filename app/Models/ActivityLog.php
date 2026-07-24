<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class ActivityLog extends Model
{
    public function recent(int $limit = 10): array
    {
        $stmt = $this->db->prepare(
            'SELECT al.*, au.name AS admin_name
             FROM activity_logs al
             LEFT JOIN admin_users au ON au.id = al.admin_id
             ORDER BY al.created_at DESC
             LIMIT :limit'
        );

        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
