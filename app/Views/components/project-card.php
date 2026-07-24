<?php
/**
 * Calm project card. Expects $item = [
 *   'image', 'imageAlt', 'title', 'category', 'city', 'href',
 * ]
 */
?>
<a href="<?= e($item['href']) ?>" class="project-card">
  <div class="project-card__media">
    <img
      src="<?= e($item['image']) ?>"
      alt="<?= e($item['imageAlt']) ?>"
      class="project-card__image"
      loading="lazy"
      decoding="async"
    >
  </div>
  <div class="project-card__body">
    <span class="project-card__category"><?= e($item['category']) ?></span>
    <h3 class="project-card__title"><?= e($item['title']) ?></h3>
    <p class="project-card__meta"><?= e($item['city']) ?></p>
    <span class="project-card__link">
      View project
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </span>
  </div>
</a>
