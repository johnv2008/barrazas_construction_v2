<?php
/** @var string $heading */
/** @var string $message */
?>
<div class="auth-card__brand">Barraza's <span>Construction</span></div>
<h1 style="font-size:var(--fs-h5);text-align:center;margin-top:var(--space-md)"><?= e($heading) ?></h1>
<p class="text-muted" style="text-align:center"><?= e($message) ?></p>
<p class="auth-card__footer">
  <a href="<?= e(admin_url('login')) ?>">Go to sign in</a>
</p>
