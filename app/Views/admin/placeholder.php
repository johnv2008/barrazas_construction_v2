<?php
/** @var string $title */
/** @var string $description */
?>
<div class="admin-page-header">
  <h1 style="font-size:var(--fs-h4);margin:0"><?= e($title) ?></h1>
</div>

<div class="admin-empty-state">
  <div class="admin-empty-state__icon" aria-hidden="true">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
  </div>
  <h2 style="font-size:var(--fs-h6);margin-bottom:var(--space-2xs)"><?= e($title) ?> management is coming in a later phase</h2>
  <p style="max-width:32rem;margin-inline:auto"><?= e($description) ?></p>
</div>
