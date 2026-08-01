<?php
/**
 * Analytics consent notice.
 *
 * A cookie banner is the single most "you are using a website" element it
 * is possible to put on a page, which puts it directly at odds with
 * everything else here. It is therefore built to the same rules as the
 * rest of the site rather than dropped in from a vendor:
 *
 *   - the annotation register for the label, not a shouty headline
 *   - a hairline and the burgundy tick, the same devices as every caption
 *   - one sentence, in the site's voice
 *   - fixed to the bottom, so it cannot shift layout (the site measures
 *     CLS 0 and a banner that pushes content would destroy that)
 *
 * ACCEPT AND DECLINE ARE EQUALLY PROMINENT.
 * Same size, same weight, same position, adjacent. Making "Decline" small,
 * grey or buried is a dark pattern, it is what regulators specifically
 * look for, and it would contradict the one thing this brand actually
 * claims about itself. There is no "manage preferences" maze either —
 * there is one thing to decide, so it is one decision.
 *
 * Renders only when there is something to consent TO: no measurement ID or
 * a non-production environment means no banner, because a consent request
 * for nothing is worse than no consent request.
 *
 * The banner ships hidden and is revealed by script only when no prior
 * choice is stored. With JavaScript disabled it never appears — and
 * neither does analytics, which is the correct pairing.
 */

$measurementId = trim((string) config('analytics.ga_measurement_id', ''));

if ($measurementId === '' || config('app.env') !== 'production') {
    return;
}
?>
<div class="consent" id="consent" role="region" aria-label="Analytics consent" hidden>
  <div class="consent__inner">
    <div class="consent__copy">
      <span class="consent__label">Before you look around</span>
      <p class="consent__text">
        We would like to count visits to this site so we know which work people
        actually want to see. It is anonymous, it is never used for advertising,
        and nothing is sent unless you say yes.
        <a href="<?= e(base_url('privacy')) ?>">What we collect</a>
      </p>
    </div>

    <div class="consent__actions">
      <button type="button" class="btn btn-secondary--on-light consent__btn" data-consent="denied">Decline</button>
      <button type="button" class="btn btn-primary consent__btn" data-consent="granted">Accept</button>
    </div>
  </div>
</div>

<script nonce="<?= e(csp_nonce()) ?>">
(function () {
  var api = window.__consent;
  var el = document.getElementById('consent');

  // No analytics component on the page means nothing to consent to.
  if (!api || !el) { return; }

  // A decision already exists — respect it silently and never ask again.
  if (api.get() !== null) { return; }

  el.hidden = false;

  el.addEventListener('click', function (event) {
    var button = event.target.closest('[data-consent]');
    if (!button) { return; }

    var choice = button.getAttribute('data-consent');
    api.set(choice);

    if (choice === 'granted') { api.load(); }

    el.hidden = true;

    // Return focus to the document rather than leaving it on a removed
    // control, so keyboard and screen-reader users are not stranded.
    var main = document.getElementById('main-content');
    if (main) { main.focus({ preventScroll: true }); }
  });
})();
</script>
