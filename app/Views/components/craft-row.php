<?php
/**
 * A single service in chapter 04.
 *
 * Deliberately NOT a card. Five layouts share this component, and each
 * row gets a different image ROLE rather than a different side of the
 * page — varying only the side while every photograph stays the same
 * 4:3 rectangle still reads as a list of cards. Descending weight:
 *
 *   dominant — one large lead image, the anchor of the chapter
 *   detail   — a single tight square crop. Craftsmanship, not the room
 *   stack    — two images at deliberately unequal size, offset
 *   plate    — one small wide plate, the quietest image row
 *   plain    — typographic only, no image
 *
 * No two adjacent rows share a role, and only "dominant" carries a large
 * image, so the chapter always has one clear focal point.
 *
 * "plain" exists because there is no ADU photography in the library
 * yet. Rather than padding the row with an unrelated photo, it becomes
 * the quiet one — which the composition wants anyway after four image
 * rows. When ADU photography exists, switch its `layout` in
 * HomeController::homeData() and it picks up an image treatment with
 * no change here.
 *
 * Params: $item — one entry from $craft['items']
 */
$item = $item ?? [];
$layout = $item['layout'] ?? 'plate';
$hasImage = !empty($item['image']);

// Only the stacked role uses a second photograph. The others hold their
// hierarchy by having exactly one, so the support image is withheld
// rather than removed from the data — it stays available if a row's
// role changes later.
$showSupport = $layout === 'stack' && !empty($item['support']);
?>
<article class="craft-row craft-row--<?= e($layout) ?>" data-reveal>
  <div class="craft-row__head">
    <span class="craft-row__num"><?= e($item['n']) ?></span>
    <h3 class="craft-row__title"><?= e($item['title']) ?></h3>
  </div>

  <div class="craft-row__body">
    <p class="craft-row__benefit"><?= e($item['benefit']) ?></p>
    <?php
      // The homepage passes no ctaLabel and keeps its original wording
      // byte-for-byte. Service pages supply their own, because "Ask about
      // an ADU" is a homepage-Chapter-04 sentence, not a component default.
      $ctaLabel = $item['ctaLabel'] ?? ($hasImage ? 'See this work' : 'Ask about an ADU');
    ?>
    <a href="<?= e($item['href']) ?>" class="link-arrow link-arrow--sm">
      <?= e($ctaLabel) ?>
    </a>
  </div>

  <?php if ($hasImage): ?>
    <div class="craft-row__media">
      <figure class="craft-row__shot">
        <?= responsive_image($item['image'], [
              'alt'   => $item['imageAlt'] ?? '',
              'sizes' => match ($layout) {
                  'dominant' => '(min-width: 1024px) 46vw, 92vw',
                  'detail'   => '(min-width: 1024px) 20vw, 60vw',
                  'stack'    => '(min-width: 1024px) 34vw, 88vw',
                  default    => '(min-width: 1024px) 26vw, 80vw',
              },
        ]) ?>
      </figure>
      <?php if ($showSupport): ?>
        <figure class="craft-row__shot craft-row__shot--support">
          <?= responsive_image($item['support'], [
                'alt'   => $item['supportAlt'] ?? '',
                'sizes' => '(min-width: 1024px) 18vw, 44vw',
          ]) ?>
        </figure>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</article>
