<?php
/** Admin sidebar navigation. Highlights the current section by path prefix. */
$path = current_path();
$items = [
    'dashboard' => 'Dashboard',
    'pages' => 'Pages',
    'services' => 'Services',
    'projects' => 'Projects',
    'testimonials' => 'Testimonials',
    'service-areas' => 'Service Areas',
    'leads' => 'Leads',
    'seo' => 'SEO',
    'settings' => 'Site Settings',
    'activity-log' => 'Activity Log',
    'administrators' => 'Administrators',
];
?>
<aside class="admin-sidebar" data-admin-sidebar data-open="false">
  <button type="button" class="admin-sidebar__close" data-sidebar-close aria-label="Close menu">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
      <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
    </svg>
  </button>

  <div class="admin-sidebar__brand">Barraza's <span>Admin</span></div>

  <nav class="admin-nav" aria-label="Admin">
    <?php foreach ($items as $slug => $label): ?>
      <?php $href = admin_url($slug); $isCurrent = str_ends_with(rtrim($path, '/'), '/' . $slug); ?>
      <a href="<?= e($href) ?>" <?= $isCurrent ? 'aria-current="page"' : '' ?>><?= e($label) ?></a>
    <?php endforeach; ?>
  </nav>
</aside>
