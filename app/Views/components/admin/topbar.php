<?php
/** @var string $title */
$adminName = \App\Services\SessionService::get('admin_name', 'Administrator');
?>
<header class="admin-topbar">
  <div style="display:flex;align-items:center;gap:var(--space-sm)">
    <button type="button" class="admin-topbar__menu-btn" data-sidebar-toggle aria-label="Open menu">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M3 6h18M3 12h18M3 18h18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
      </svg>
    </button>
    <strong><?= e($title ?? 'Dashboard') ?></strong>
  </div>

  <div class="admin-user-menu">
    <button type="button" class="admin-user-menu__button" data-user-menu-button aria-haspopup="true" aria-expanded="false">
      <?= e($adminName) ?>
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>
    <div class="admin-user-menu__panel" data-user-menu-panel data-open="false">
      <form action="<?= e(admin_url('logout')) ?>" method="post">
        <?= csrf_field() ?>
        <button type="submit">Sign out</button>
      </form>
    </div>
  </div>
</header>
