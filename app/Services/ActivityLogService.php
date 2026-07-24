<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Request;

/**
 * Persists a durable, queryable audit trail of admin actions to the
 * activity_logs table (distinct from the flat-file app/error log).
 */
final class ActivityLogService
{
    public static function record(?int $adminId, string $action, ?string $description, Request $request): void
    {
        $stmt = DatabaseService::connection()->prepare(
            'INSERT INTO activity_logs (admin_id, action, description, ip_address, user_agent, created_at)
             VALUES (:admin_id, :action, :description, :ip_address, :user_agent, NOW())'
        );

        $stmt->execute([
            'admin_id' => $adminId,
            'action' => $action,
            'description' => $description,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }
}
