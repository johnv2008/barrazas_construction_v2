<?php
/**
 * A project as an editorial entry, not a gallery tile.
 *
 * Deliberately NOT the homepage's contact-sheet frame and NOT a uniform
 * card: the contact-sheet editorial spread is reserved to the Portfolio
 * page type (DESIGN_SYSTEM.md §10.1), so service and project pages present
 * related work at unequal sizes with the caption written beneath on a
 * hairline.
 *
 * The whole card is one link. Titles and metadata sit outside the
 * photograph, always visible — never a hover-only gradient overlay, which
 * hides the caption from anyone who does not hover and was removed from the
 * homepage for exactly that reason.
 *
 * Params:
 *   $project — a projects row (slug, title, project_type, city, images)
 *   $size    — 'lead' | 'support'
 */
$project = $project ?? [];
$size = $size ?? 'support';

$image = \App\Content\Catalog::imageByRole($project, 'hero', 'during', 'detail', 'context');

if ($image === null) {
    return;
}
?>
<article class="pcard pcard--<?= e($size) ?>">
  <a class="pcard__link" href="<?= e('/projects/' . $project['slug']) ?>">
    <div class="pcard__frame">
      <?= responsive_image($image['image_path'], [
            'alt'   => $image['alt_text'] ?? '',
            'sizes' => $size === 'lead'
                ? '(min-width: 1024px) 40vw, 92vw'
                : '(min-width: 1024px) 22vw, 44vw',
      ]) ?>
    </div>
    <div class="pcard__caption">
      <h3 class="pcard__title"><?= e($project['title']) ?></h3>
      <span class="pcard__meta">
        <?= e($project['project_type']) ?><?= !empty($project['city']) ? ' · ' . e($project['city']) : '' ?>
      </span>
    </div>
  </a>
</article>
