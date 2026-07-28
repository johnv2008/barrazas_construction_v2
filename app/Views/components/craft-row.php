<?php
/**
 * A single service in chapter 04.
 *
 * Deliberately NOT a card. Three layouts share this component so the
 * five services never resolve into a matching grid:
 *
 *   wide     — large lead image plus a supporting image, copy left
 *   standard — single image, copy right, reversed reading direction
 *   plain    — typographic only, no image
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
$layout = $item['layout'] ?? 'standard';
$hasImage = !empty($item['image']);
?>
<article class="craft-row craft-row--<?= e($layout) ?>" data-reveal>
  <div class="craft-row__head">
    <span class="craft-row__num"><?= e($item['n']) ?></span>
    <h3 class="craft-row__title"><?= e($item['title']) ?></h3>
  </div>

  <div class="craft-row__body">
    <p class="craft-row__benefit"><?= e($item['benefit']) ?></p>
    <a href="<?= e($item['href']) ?>" class="link-arrow link-arrow--sm">
      <?= $hasImage ? 'See this work' : 'Ask about an ADU' ?>
    </a>
  </div>

  <?php if ($hasImage): ?>
    <div class="craft-row__media">
      <figure class="craft-row__shot">
        <?= responsive_image($item['image'], [
              'alt'   => $item['imageAlt'] ?? '',
              'sizes' => $layout === 'wide'
                  ? '(min-width: 1024px) 40vw, 92vw'
                  : '(min-width: 1024px) 30vw, 88vw',
        ]) ?>
      </figure>
      <?php if (!empty($item['support'])): ?>
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
