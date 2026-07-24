<?php
/**
 * Reusable project card. Expects $item = [
 *   'category' => string,
 *   'title' => string,
 *   'city' => string,
 *   'scope' => string,
 *   'image' => string,
 *   'alt' => string,
 *   'href' => string,
 *   'featured' => bool (optional),
 * ]
 */
$featured = $item['featured'] ?? false;
?>
<a href="<?= e($item['href']) ?>" class="project-card<?= $featured ? ' project-card--featured' : '' ?>">
  <img
    src="<?= e($item['image']) ?>"
    alt="<?= e($item['alt']) ?>"
    class="project-card__image"
    loading="lazy"
    decoding="async"
  >
  <div class="project-card__overlay">
    <span class="project-card__category"><?= e($item['category']) ?></span>
    <h3 class="project-card__title"><?= e($item['title']) ?></h3>
    <p class="project-card__meta"><?= e($item['city']) ?> &middot; <?= e($item['scope']) ?></p>
    <span class="project-card__link">
      View Project
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </span>
  </div>
</a>
