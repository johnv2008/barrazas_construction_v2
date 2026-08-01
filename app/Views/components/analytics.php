<?php
/**
 * Google Analytics 4 — loaded ONLY after the visitor consents.
 *
 * This component no longer loads anything by itself. It defines a loader
 * and calls it only if a prior "granted" choice is stored. The consent
 * banner (components/consent.php) calls the same loader when someone
 * accepts, so there is exactly one code path that can start GA.
 *
 * WHY GATE RATHER THAN USE CONSENT MODE
 * -------------------------------------
 * Google's Consent Mode v2 loads gtag.js immediately and sends "cookieless
 * pings" until consent arrives. That is defensible, and it is also a
 * third-party request carrying the visitor's IP and page URL before they
 * have agreed to anything. Not loading the script at all is simpler to
 * explain, simpler to verify, and leaves nothing to argue about: decline
 * means no request to Google, ever.
 *
 * The cost is that declined visitors are invisible in analytics. That is
 * the correct trade for a residential contractor whose traffic is small
 * enough that the difference is noise.
 *
 * Rendered on PUBLIC pages only, in production, only when an ID is set.
 * See the CSP note in public/index.php for what this widens.
 */

$measurementId = trim((string) config('analytics.ga_measurement_id', ''));

if ($measurementId === '' || config('app.env') !== 'production') {
    return;
}
?>
<script nonce="<?= e(csp_nonce()) ?>">
(function () {
  var ID = <?= json_encode($measurementId, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) ?>;
  var KEY = 'bc-consent';
  var loaded = false;

  function load() {
    if (loaded) { return; }
    loaded = true;

    var s = document.createElement('script');
    s.async = true;
    s.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(ID);
    document.head.appendChild(s);

    window.dataLayer = window.dataLayer || [];
    function gtag() { dataLayer.push(arguments); }
    window.gtag = gtag;
    gtag('js', new Date());
    gtag('config', ID, {
      anonymize_ip: true,
      allow_google_signals: false
    });
  }

  // Exposed so the consent banner can start analytics on acceptance
  // without duplicating any of the above.
  window.__consent = {
    key: KEY,
    load: load,
    // localStorage rather than a cookie: the choice is only ever read by
    // this script, so there is no reason to attach it to every request.
    // Wrapped because Safari's private mode throws on access.
    get: function () {
      try { return localStorage.getItem(KEY); } catch (e) { return null; }
    },
    set: function (value) {
      try { localStorage.setItem(KEY, value); } catch (e) {}
    }
  };

  if (window.__consent.get() === 'granted') { load(); }
})();
</script>
