<?php
/**
 * ARCHITECTURAL WHITESPACE — the Whole Home page type's signature.
 *
 * Registered in DESIGN_SYSTEM.md §10.1 and reserved to this page type.
 * The other signatures express scale by making something large: Kitchen
 * bleeds a wide crop off the edge, Bathroom runs a frame past the fold,
 * the homepage cuts type into a photograph. This one expresses scale by
 * making something SMALL and leaving the room around it empty.
 *
 * That is not a lesser version of the same idea — it is the opposite
 * idea, and it is the right one for this subject. A whole-home
 * renovation is the largest thing the company does and the hardest to
 * photograph: there is no single frame that contains it. So the chapter
 * stops trying. One modest photograph, held small, inside the largest
 * empty measure on the site.
 *
 * It also suits the evidence honestly. Whole Home is Tier B — four
 * unique photographs, not the six or seven the filenames implied — and a
 * composition built on void needs exactly one image to work.
 *
 * No edge-bleed. Spending one here would fill the very space the
 * composition is made of.
 *
 * Params: $items — materials_item rows; only the first is rendered,
 *         because a "whitespace" composition with two subjects is not a
 *         whitespace composition.
 */
$items = $items ?? [];

if ($items === []) {
    return;
}

$item = $items[0];
?>
<div class="wband">
  <figure class="wband__item">
    <div class="wband__frame">
      <?= responsive_image($item['image_path'], [
            'alt'   => $item['image_alt'] ?? '',
            'focal' => $item['focal'] ?? ['x' => 0.5, 'y' => 0.5],
            'sizes' => '(min-width: 1024px) 22vw, 62vw',
      ]) ?>
    </div>
  </figure>

  <figcaption class="wband__note">
    <span class="wband__label"><?= e($item['label']) ?></span>
    <span class="wband__title"><?= e($item['title']) ?></span>
    <span class="wband__body"><?= e($item['body']) ?></span>
  </figcaption>
</div>
