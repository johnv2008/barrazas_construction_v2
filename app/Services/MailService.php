<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Config;
use App\Core\Logger;

/**
 * Minimal mail sender used for password-reset delivery. Phase 1 ships
 * the architecture only: SMTP credentials are read from config, but
 * actual delivery is stubbed and logged rather than sent, since no
 * mail credentials are provisioned yet. Swap sendViaSmtp()'s body for
 * a real SMTP call (or PHP mail()) once MAIL_* is configured.
 */
final class MailService
{
    public static function send(string $to, string $subject, string $body): bool
    {
        if ((string) Config::get('mail.host', '') === '') {
            Logger::warning('MailService: no MAIL_HOST configured, email not sent', [
                'to' => $to,
                'subject' => $subject,
            ]);

            return false;
        }

        return self::sendViaSmtp($to, $subject, $body);
    }

    private static function sendViaSmtp(string $to, string $subject, string $body): bool
    {
        // Intentionally not implemented in Phase 1. Wire up a real SMTP
        // client (or PHP mail()) here in a later phase once mail
        // credentials for barrazasconstruction.com are available.
        Logger::info('MailService: SMTP send requested', ['to' => $to, 'subject' => $subject]);

        return false;
    }
}
