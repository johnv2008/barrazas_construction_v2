<?php
/**
 * Before/after (progress -> complete) comparison slider.
 *
 * Expects $item = [
 *   'progressImage' => string, 'progressAlt' => string,
 *   'completeImage' => string, 'completeAlt' => string,
 *   'category' => string, 'title' => string, 'city' => string,
 *   'summary' => string, 'href' => string,
 * ]
 *
 * Honesty note: labeled "In Progress" / "Completed" rather than
 * "Before" / "After" because these two photos are not confirmed to be
 * the exact same room — they represent Barraza's construction process
 * and finished craftsmanship generally, not a single continuous
 * transformation. See $item['note'] below, rendered to the visitor.
 */
?>
<div class="ba-slider" data-ba-slider>
  <div class="ba-slider__frame">
    <img class="ba-slider__img ba-slider__img--after" src="<?= e($item['completeImage']) ?>" alt="<?= e($item['completeAlt']) ?>">
    <img class="ba-slider__img ba-slider__img--before" src="<?= e($item['progressImage']) ?>" alt="<?= e($item['progressAlt']) ?>">
    <span class="ba-slider__label ba-slider__label--before">In Progress</span>
    <span class="ba-slider__label ba-slider__label--after">Completed</span>
    <div class="ba-slider__divider" aria-hidden="true"></div>
    <div class="ba-slider__knob" aria-hidden="true">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M8 7l-5 5 5 5M16 7l5 5-5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </div>
    <input
      type="range"
      class="ba-slider__range"
      min="0"
      max="100"
      value="50"
      step="1"
      aria-label="Drag to compare construction in progress with the completed result"
    >
  </div>

  <div class="ba-slider__meta">
    <div>
      <span class="ba-slider__meta-category"><?= e($item['category']) ?></span>
      <h3 class="ba-slider__meta-title"><?= e($item['title']) ?></h3>
      <p class="ba-slider__meta-summary"><?= e($item['city']) ?> &middot; <?= e($item['summary']) ?></p>
    </div>
    <a href="<?= e($item['href']) ?>" class="btn btn-secondary">View Project</a>
  </div>
  <p class="ba-slider__note"><?= e($item['note']) ?></p>
</div>
