<?php
/**
 * @var array{pages:int,services:int,projects:int,testimonials:int,leads_new:int,leads_total:int} $summary
 * @var array $recentActivity
 * @var string|null $adminName
 */
?>
<div class="admin-page-header">
  <div>
    <h1 style="font-size:var(--fs-h4);margin-bottom:var(--space-3xs)">Welcome back<?= $adminName ? ', ' . e($adminName) : '' ?></h1>
    <p class="text-muted" style="margin:0">Here's a snapshot of your site content.</p>
  </div>
</div>

<div class="admin-card-grid">
  <div class="admin-card">
    <div class="admin-card__label">Pages</div>
    <div class="admin-card__value"><?= (int) $summary['pages'] ?></div>
    <a class="admin-card__link" href="<?= e(admin_url('pages')) ?>">Manage pages &rarr;</a>
  </div>
  <div class="admin-card">
    <div class="admin-card__label">Services</div>
    <div class="admin-card__value"><?= (int) $summary['services'] ?></div>
    <a class="admin-card__link" href="<?= e(admin_url('services')) ?>">Manage services &rarr;</a>
  </div>
  <div class="admin-card">
    <div class="admin-card__label">Projects</div>
    <div class="admin-card__value"><?= (int) $summary['projects'] ?></div>
    <a class="admin-card__link" href="<?= e(admin_url('projects')) ?>">Manage projects &rarr;</a>
  </div>
  <div class="admin-card">
    <div class="admin-card__label">New Leads</div>
    <div class="admin-card__value"><?= (int) $summary['leads_new'] ?></div>
    <a class="admin-card__link" href="<?= e(admin_url('leads')) ?>">Review leads &rarr;</a>
  </div>
</div>

<div class="admin-panel">
  <h2 class="admin-panel__title">Quick Actions</h2>
  <div style="display:flex;flex-wrap:wrap;gap:var(--space-sm)">
    <a class="btn btn-primary" href="<?= e(admin_url('projects')) ?>" style="color:var(--color-charcoal-deep)">Add a Project</a>
    <a class="btn btn-secondary" style="border-color:var(--color-border-on-light);color:var(--color-text-on-light)" href="<?= e(admin_url('services')) ?>">Edit Services</a>
    <a class="btn btn-secondary" style="border-color:var(--color-border-on-light);color:var(--color-text-on-light)" href="<?= e(admin_url('settings')) ?>">Site Settings</a>
  </div>
</div>

<div class="admin-panel">
  <h2 class="admin-panel__title">Recent Activity</h2>

  <?php if ($recentActivity === []): ?>
    <div class="admin-empty-state">
      <div class="admin-empty-state__icon" aria-hidden="true">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M12 8v4l3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5"/></svg>
      </div>
      <p>No activity has been recorded yet.</p>
    </div>
  <?php else: ?>
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th scope="col">When</th>
            <th scope="col">Administrator</th>
            <th scope="col">Action</th>
            <th scope="col">Details</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($recentActivity as $entry): ?>
            <tr>
              <td><?= e((string) $entry['created_at']) ?></td>
              <td><?= e((string) ($entry['admin_name'] ?? 'System')) ?></td>
              <td><?= e((string) $entry['action']) ?></td>
              <td><?= e((string) ($entry['description'] ?? '')) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
