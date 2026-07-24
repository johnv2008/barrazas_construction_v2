<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class PasswordResetToken extends Model
{
    /**
     * Stores only a hash of the token (never the raw value) so a
     * database read alone can never be used to reset a password.
     */
    public function create(int $adminId, string $tokenHash, int $expiresInMinutes = 60): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO password_reset_tokens (admin_id, token_hash, expires_at, created_at)
             VALUES (:admin_id, :token_hash, DATE_ADD(NOW(), INTERVAL :minutes MINUTE), NOW())'
        );

        $stmt->bindValue('admin_id', $adminId, \PDO::PARAM_INT);
        $stmt->bindValue('token_hash', $tokenHash);
        $stmt->bindValue('minutes', $expiresInMinutes, \PDO::PARAM_INT);
        $stmt->execute();
    }

    public function findValidByHash(string $tokenHash): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM password_reset_tokens
             WHERE token_hash = :token_hash AND used_at IS NULL AND expires_at > NOW()
             LIMIT 1'
        );

        $stmt->execute(['token_hash' => $tokenHash]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    public function markUsed(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE password_reset_tokens SET used_at = NOW() WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
