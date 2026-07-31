<?php
/**
 * Google Analytics 4.
 *
 * Rendered on PUBLIC pages only. The admin layout deliberately does not
 * include this: there is nothing to learn from tracking the one person who
 * runs the site, and doing so would send admin URLs to a third party.
 *
 * TWO CONDITIONS, BOTH REQUIRED
 * -----------------------------
 *   1. A measurement ID is configured.
 *   2. APP_ENV is production.
 *
 * The second matters more than it looks. Without it, every local page load
 * and every staging click lands in the same property as real visitors, and
 * the data is quietly wrong from day one in a way nobody notices for months.
 *
 * CSP
 * ---
 * This is the only third-party script on the site, and it is the reason
 * script-src is no longer a clean 'self' + nonce. gtag.js cannot be
 * nonce-gated because Google's loader injects further scripts at runtime,
 * so the googletagmanager origin has to be allowed wholesale. The widening
 * is enumerated in public/index.php next to the header itself.
 *
 * PRIVACY
 * -------
 * GA4 sets cookies and is a third-party transfer. Two settings below reduce
 * the exposure without changing what the business actually needs to know:
 *
 *   anonymize_ip      truncates the visitor address before storage
 *   allow_google_signals=false  disables cross-device advertising signals,
 *                     which is the part that makes GA an advertising
 *                     product rather than a measurement one
 *
 * These do not substitute for consent where consent is required. A
 * California business serving California residents has CCPA/CPRA
 * obligations, and there is currently no consent mechanism on this site —
 * flagged rather than silently assumed to be fine.
 */

$measurementId = trim((string) config('analytics.ga_measurement_id', ''));

if ($measurementId === '' || config('app.env') !== 'production') {
    return;
}
?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= e(rawurlencode($measurementId)) ?>"></script>
<script nonce="<?= e(csp_nonce()) ?>">
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', <?= json_encode($measurementId, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) ?>, {
    anonymize_ip: true,
    allow_google_signals: false
  });
</script>
