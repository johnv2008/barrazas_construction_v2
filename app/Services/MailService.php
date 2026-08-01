<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Config;
use App\Core\Logger;

/**
 * Plain-text mail delivery via PHP's mail().
 *
 * mail() is used deliberately rather than SMTP: it needs no credentials,
 * works on one.com out of the box, and the alternative was leads arriving
 * nowhere at all. The tradeoff is deliverability, which is why the From
 * address must stay on barrazasconstruction.com — see below.
 *
 * SPAM AVOIDANCE, which is the whole difficulty with mail():
 *
 *  - From is always the site's own domain. It is tempting to put the
 *    homeowner's address in From so replies work, but that is spoofing:
 *    Gmail checks whether the sending server is authorised for the From
 *    domain (SPF/DMARC) and a mismatch is scored as forgery. Replies are
 *    handled by Reply-To instead, which has no such requirement.
 *  - The envelope sender is set with -f where the host allows it, so
 *    bounces and SPF align with From. Some shared hosts refuse that
 *    parameter, so a refusal falls back to a plain send rather than
 *    dropping the message.
 *
 * HEADER INJECTION is the security concern. Values reaching these headers
 * come from a public form, and a newline inside one would let a submitter
 * append headers of their own — Bcc in particular, turning the contact
 * form into an open spam relay. Every interpolated value is therefore
 * stripped of CR, LF and NUL, and addresses must additionally survive
 * FILTER_VALIDATE_EMAIL.
 */
final class MailService
{
    /**
     * @param string $replyTo Optional address a reply should go to. Used
     *                        for lead notifications so hitting Reply in
     *                        the mail client reaches the homeowner.
     */
    public static function send(string $to, string $subject, string $body, string $replyTo = ''): bool
    {
        $to = self::address($to);

        if ($to === '') {
            Logger::warning('MailService: no valid recipient, email not sent', ['subject' => $subject]);

            return false;
        }

        $fromAddress = self::address((string) Config::get('mail.from_address', ''));

        if ($fromAddress === '') {
            Logger::warning('MailService: MAIL_FROM_ADDRESS is missing or invalid', ['to' => $to]);

            return false;
        }

        $headers = [
            'From: ' . self::mailbox((string) Config::get('mail.from_name', ''), $fromAddress),
            'MIME-Version: 1.0',
            'Content-Type: text/plain; charset=UTF-8',
            'Content-Transfer-Encoding: 8bit',
            'X-Mailer: barrazasconstruction.com',
        ];

        $replyTo = self::address($replyTo);

        if ($replyTo !== '') {
            $headers[] = 'Reply-To: ' . $replyTo;
        }

        // Outside production this logs what would have been sent instead of
        // sending it, so filling in the form on a development machine cannot
        // email the client.
        if (Config::get('app.env') !== 'production') {
            Logger::info('MailService: suppressed outside production', [
                'to' => $to,
                'subject' => $subject,
                'body' => $body,
            ]);

            return true;
        }

        $encodedSubject = self::header($subject);
        $encodedHeaders = implode("\r\n", $headers);
        $encodedBody = self::body($body);

        // -f aligns the envelope sender with From, which materially helps
        // inbox placement. Hosts that forbid the parameter make mail()
        // return false without sending, so that case retries without it
        // rather than losing the notification.
        $sent = @mail($to, $encodedSubject, $encodedBody, $encodedHeaders, '-f' . $fromAddress);

        if (!$sent) {
            $sent = @mail($to, $encodedSubject, $encodedBody, $encodedHeaders);

            if ($sent) {
                Logger::info('MailService: sent without envelope sender (-f refused by host)', ['to' => $to]);
            }
        }

        if (!$sent) {
            Logger::error('MailService: mail() failed', ['to' => $to, 'subject' => $subject]);
        }

        return $sent;
    }

    /** Validated address, or '' if it is unusable or carries a newline. */
    private static function address(string $value): string
    {
        $value = self::stripBreaks($value);

        return filter_var($value, FILTER_VALIDATE_EMAIL) === false ? '' : $value;
    }

    /** A "Display Name" <addr> mailbox, with the name quoted and encoded. */
    private static function mailbox(string $name, string $address): string
    {
        $name = self::stripBreaks($name);

        if ($name === '') {
            return $address;
        }

        return self::header($name, true) . ' <' . $address . '>';
    }

    /**
     * RFC 2047 encoding for header values that are not pure ASCII, so a
     * name like "José" does not arrive as mojibake.
     */
    private static function header(string $value, bool $quoteAscii = false): string
    {
        $value = self::stripBreaks($value);

        if (preg_match('/[^\x20-\x7E]/', $value) === 1) {
            return '=?UTF-8?B?' . base64_encode($value) . '?=';
        }

        return $quoteAscii ? '"' . str_replace(['\\', '"'], ['\\\\', '\\"'], $value) . '"' : $value;
    }

    /**
     * Normalises line endings and wraps to keep lines inside the 998-octet
     * limit in RFC 5322, which a long unbroken project description would
     * otherwise exceed.
     */
    private static function body(string $value): string
    {
        $value = str_replace(["\r\n", "\r"], "\n", $value);
        $value = wordwrap($value, 76, "\n", false);

        return str_replace("\n", "\r\n", $value);
    }

    /**
     * Removes the characters that would let submitted text terminate a
     * header and start a new one. This is the open-relay guard.
     */
    private static function stripBreaks(string $value): string
    {
        return trim(str_replace(["\r", "\n", "\0"], '', $value));
    }
}
