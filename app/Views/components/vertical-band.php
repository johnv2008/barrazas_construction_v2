<?php
/**
 * THE VERTICAL BAND — the Bathroom page type's signature composition.
 *
 * Registered in DESIGN_SYSTEM.md §10.1 as "vertical craftsmanship
 * composition", reserved to this page type. What makes it a signature
 * rather than a column of pictures, and what makes it NOT the Kitchen
 * band wearing a different hat:
 *
 *   1. It reads TOP TO BOTTOM. Kitchen's band reads across, with one wide
 *      lead crop bleeding off the right edge. This one descends: three
 *      tall frames stepping down and across on a broken baseline, so the
 *      eye travels the way the page scrolls.
 *   2. The frames are PORTRAIT and deliberately unequal in height. Twenty
 *      of the twenty-one photographs in the library are phone-shot
 *      portraits, so this composition uses the source material the way it
 *      was actually taken instead of cropping against it.
 *   3. No edge-bleed. Kitchen spends its one bleed here; Bathroom spends
 *      nothing, because the tallest frame already exceeds the fold — you
 *      cannot see all of it at once, which is the vertical equivalent and
 *      does not borrow another page type's gesture. (§10.1 rule 3 allows
 *      at most one bleed per page type; it does not require one.)
 *   4. The order is an argument, not a gallery: the stage nobody
 *      photographs, then the material decision, then the ordinary
 *      bathroom that received the same care.
 *
 * Params: $items — materials_item rows (label, title, body, image_path,
 *         image_alt, focal, scale ∈ lead|tall|medium)
 */
$items = $items ?? [];

if ($items === []) {
    return;
}
?>
<div class="vband">
  <?php foreach ($items as $i => $item): ?>
    <?php $scale = $item['scale'] ?? 'medium'; ?>
    <figure class="vband__item vband__item--<?= e($scale) ?>">
      <div class="vband__frame">
        <?= responsive_image($item['image_path'], [
              'alt'   => $item['image_alt'] ?? '',
              'focal' => $item['focal'] ?? ['x' => 0.5, 'y' => 0.5],
              'sizes' => match ($scale) {
                  'lead'   => '(min-width: 1024px) 34vw, 88vw',
                  'tall'   => '(min-width: 1024px) 24vw, 62vw',
                  default  => '(min-width: 1024px) 28vw, 74vw',
              },
        ]) ?>
      </div>
      <figcaption class="vband__note">
        <span class="vband__step" aria-hidden="true"><?= e(sprintf('%02d', $i + 1)) ?></span>
        <span class="vband__label"><?= e($item['label']) ?></span>
        <span class="vband__title"><?= e($item['title']) ?></span>
        <span class="vband__body"><?= e($item['body']) ?></span>
      </figcaption>
    </figure>
  <?php endforeach; ?>
</div>
