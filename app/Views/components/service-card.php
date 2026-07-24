<?php
/**
 * Calm service card. Expects $item = [
 *   'title', 'description', 'image', 'imageAlt', 'href',
 * ]
 */
?>
<a href="<?= e($item['href']) ?>" class="service-card">
  <div class="service-card__media">
    <img
      src="<?= e($item['image']) ?>"
      alt="<?= e($item['imageAlt']) ?>"
      class="service-card__image"
      loading="lazy"
      decoding="async"
    >
  </div>
  <div class="service-card__body">
    <h3 class="service-card__title"><?= e($item['title']) ?></h3>
    <p class="service-card__desc"><?= e($item['description']) ?></p>
    <span class="service-card__link">
      Learn more
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </span>
  </div>
</a>
