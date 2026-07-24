<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class AdminUser extends Model
{
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM admin_users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);

        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM admin_users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    public function countAdmins(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM admin_users')->fetchColumn();
    }

    public function create(string $name, string $email, string $passwordHash, string $role = 'administrator'): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO admin_users (name, email, password_hash, role, is_active, created_at, updated_at)
             VALUES (:name, :email, :password_hash, :role, 1, NOW(), NOW())'
        );

        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password_hash' => $passwordHash,
            'role' => $role,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function recordSuccessfulLogin(int $id): void
    {
        $stmt = $this->db->prepare(
            'UPDATE admin_users
             SET failed_login_count = 0, locked_until = NULL, last_login_at = NOW(), updated_at = NOW()
             WHERE id = :id'
        );

        $stmt->execute(['id' => $id]);
    }

    public function recordFailedLogin(int $id, int $maxAttempts, int $lockoutMinutes): void
    {
        $stmt = $this->db->prepare(
            'UPDATE admin_users
             SET failed_login_count = failed_login_count + 1,
                 locked_until = CASE
                     WHEN failed_login_count + 1 >= :max_attempts
                     THEN DATE_ADD(NOW(), INTERVAL :lockout_minutes MINUTE)
                     ELSE locked_until
                 END,
                 updated_at = NOW()
             WHERE id = :id'
        );

        $stmt->execute([
            'id' => $id,
            'max_attempts' => $maxAttempts,
            'lockout_minutes' => $lockoutMinutes,
        ]);
    }

    public function isLocked(array $admin): bool
    {
        if (empty($admin['locked_until'])) {
            return false;
        }

        return strtotime((string) $admin['locked_until']) > time();
    }
}
