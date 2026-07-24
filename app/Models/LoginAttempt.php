<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class LoginAttempt extends Model
{
    public function record(string $email, string $ip, string $userAgent, bool $successful): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO login_attempts (email, ip_address, user_agent, successful, created_at)
             VALUES (:email, :ip_address, :user_agent, :successful, NOW())'
        );

        $stmt->execute([
            'email' => $email,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'successful' => $successful ? 1 : 0,
        ]);
    }

    public function recentFailuresFromIp(string $ip, int $windowMinutes): int
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM login_attempts
             WHERE ip_address = :ip AND successful = 0
               AND created_at >= DATE_SUB(NOW(), INTERVAL :minutes MINUTE)'
        );

        $stmt->bindValue('ip', $ip);
        $stmt->bindValue('minutes', $windowMinutes, \PDO::PARAM_INT);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }
}
