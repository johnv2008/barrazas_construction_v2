<?php
/**
 * Questions homeowners ask.
 *
 * Native <details>/<summary>. Deliberately no JavaScript:
 *
 *   - keyboard accessible and screen-reader announced for free
 *   - the answer text is in the DOM whether open or closed, so it is
 *     indexable — a JS accordion that injects content on click is not
 *   - works with scripting disabled, which the site's reveal layer
 *     already treats as a first-class path
 *
 * The first item is open so the chapter never reads as an empty list of
 * closed bars. The <summary> carries an h3 so the document outline is
 * correct; the marker is replaced with a burgundy chevron in CSS rather
 * than removed, so the affordance survives forced-colors mode.
 *
 * Params: $faqs — list of ['question' => string, 'answer' => string]
 */
$faqs = $faqs ?? [];

if ($faqs === []) {
    return;
}
?>
<div class="qa">
  <?php foreach ($faqs as $i => $faq): ?>
    <details class="qa__item"<?= $i === 0 ? ' open' : '' ?>>
      <summary class="qa__q">
        <h3><?= e($faq['question']) ?></h3>
        <span class="qa__marker" aria-hidden="true"></span>
      </summary>
      <div class="qa__a">
        <p><?= e($faq['answer']) ?></p>
      </div>
    </details>
  <?php endforeach; ?>
</div>
