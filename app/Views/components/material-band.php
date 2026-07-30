<?php
/**
 * THE MATERIAL BAND — the Kitchen page type's signature composition.
 *
 * Registered in DESIGN_SYSTEM.md §10.1 as "material-driven composition",
 * reserved to this page type and used nowhere else. What makes it a
 * signature rather than a grid:
 *
 *   1. Four crops at four DIFFERENT scales (lead / medium / small / small),
 *      never a row of equals. The lead crop bleeds off the right viewport
 *      edge — this page's one bleed, spent on its signature chapter.
 *   2. The crops are MACRO. Each frame shows a surface, not a room, so the
 *      chapter reads as a set of material studies rather than more
 *      photographs of kitchens.
 *   3. Every crop carries a method note. The claim is about how the thing
 *      was done, never about a brand.
 *
 * On reusing photographs: three of these crops come from images that also
 * appear elsewhere on the page as rooms. That is deliberate and honest — at
 * macro scale a quartz edge is a different subject from the kitchen it sits
 * in, and these are genuinely the materials in those rooms. Per-image focal
 * points do the work of finding the surface inside the frame.
 *
 * PHOTOGRAPHY_REQUIREMENTS.md §2 flags purpose-shot material squares as the
 * single highest-value improvement to this chapter: it would let the crops
 * go tighter without softening.
 *
 * Params: $items — list of materials_item rows (label, title, body,
 *         image_path, image_alt, focal, scale)
 */
$items = $items ?? [];

if ($items === []) {
    return;
}
?>
<div class="band">
  <?php foreach ($items as $i => $item): ?>
    <?php $scale = $item['scale'] ?? 'small'; ?>
    <figure class="band__item band__item--<?= e($scale) ?>">
      <div class="band__frame">
        <?= responsive_image($item['image_path'], [
              'alt'   => $item['image_alt'] ?? '',
              // Per-crop focal, overriding the manifest's per-file value —
              // the same photograph is cropped to its tile here and to its
              // hardware two frames along.
              'focal' => $item['focal'] ?? ['x' => 0.5, 'y' => 0.5],
              'sizes' => match ($scale) {
                  'lead'   => '(min-width: 1024px) 46vw, 92vw',
                  'medium' => '(min-width: 1024px) 26vw, 60vw',
                  default  => '(min-width: 1024px) 18vw, 44vw',
              },
        ]) ?>
      </div>
      <figcaption class="band__note">
        <span class="band__label"><?= e($item['label']) ?></span>
        <span class="band__title"><?= e($item['title']) ?></span>
        <span class="band__body"><?= e($item['body']) ?></span>
      </figcaption>
    </figure>
  <?php endforeach; ?>
</div>
