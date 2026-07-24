<?php
/**
 * Scrolling capability strip. Expects $items: array<int, string>.
 * Duplicated once (aria-hidden) for a seamless marquee loop; the
 * duplicate is hidden outright on mobile, which falls back to a
 * static wrapped layout (see frontend.css).
 */
?>
<div class="capability-strip" aria-label="Services and coverage">
  <div class="capability-strip__track">
    <div class="capability-strip__set">
      <?php foreach ($items as $index => $item): ?>
        <span class="capability-strip__item"><?= $index === 0 ? '<strong>' . e($item) . '</strong>' : e($item) ?></span>
      <?php endforeach; ?>
    </div>
    <div class="capability-strip__set" aria-hidden="true">
      <?php foreach ($items as $index => $item): ?>
        <span class="capability-strip__item"><?= $index === 0 ? '<strong>' . e($item) . '</strong>' : e($item) ?></span>
      <?php endforeach; ?>
    </div>
  </div>
</div>
