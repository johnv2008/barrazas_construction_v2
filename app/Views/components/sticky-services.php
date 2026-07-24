<?php
/**
 * Sticky scrolling services experience. Expects $services: array of [
 *   'title','description','scope' => array<string>,
 *   'primaryImage','primaryAlt','detailImage','detailAlt',
 *   'tag','serviceHref','consultHref',
 * ].
 *
 * Desktop: left column steps drive which right-column image
 * composition is active (see data-service-step / data-service-frame
 * in app.js — a plain IntersectionObserver, never scroll-jacked).
 * Mobile: the right column is hidden by CSS and each step shows its
 * own images inline instead — a fully accessible vertical sequence.
 */
?>
<div class="sticky-services__grid">
  <div class="sticky-services__steps">
    <?php foreach ($services as $index => $service): ?>
      <div class="sticky-services__step<?= $index === 0 ? ' is-active' : '' ?>" data-service-step data-index="<?= $index ?>">
        <div class="sticky-services__step-inner">
          <span class="sticky-services__number"><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
          <h3><?= e($service['title']) ?></h3>
          <p class="text-muted"><?= e($service['description']) ?></p>
          <ul class="sticky-services__scope">
            <?php foreach ($service['scope'] as $scopeItem): ?>
              <li><?= e($scopeItem) ?></li>
            <?php endforeach; ?>
          </ul>
          <div class="sticky-services__links">
            <a href="<?= e($service['serviceHref']) ?>">Learn More</a>
            <a href="<?= e($service['consultHref']) ?>">Request a Consultation</a>
          </div>
          <div class="sticky-services__mobile-media">
            <img src="<?= e($service['primaryImage']) ?>" alt="<?= e($service['primaryAlt']) ?>" loading="lazy" decoding="async">
            <img src="<?= e($service['detailImage']) ?>" alt="<?= e($service['detailAlt']) ?>" loading="lazy" decoding="async">
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="sticky-services__visual">
    <div class="sticky-services__visual-inner">
      <?php foreach ($services as $index => $service): ?>
        <div class="sticky-services__frame<?= $index === 0 ? ' is-active' : '' ?>" data-service-frame data-index="<?= $index ?>">
          <img class="sticky-services__frame-primary" src="<?= e($service['primaryImage']) ?>" alt="<?= e($service['primaryAlt']) ?>" loading="lazy" decoding="async">
          <img class="sticky-services__frame-detail" src="<?= e($service['detailImage']) ?>" alt="<?= e($service['detailAlt']) ?>" loading="lazy" decoding="async">
          <span class="sticky-services__tag"><?= e($service['tag']) ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
