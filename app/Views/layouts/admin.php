<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title ?? 'Admin') ?> · Barraza's Construction Admin</title>
  <meta name="robots" content="noindex, nofollow">

  <?php \App\Core\View::component('head-icons'); ?>

  <link rel="stylesheet" href="<?= e(asset('css/variables.css')) ?>">
  <link rel="stylesheet" href="<?= e(asset('css/base.css')) ?>">
  <link rel="stylesheet" href="<?= e(asset('css/components.css')) ?>">
  <link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
</head>
<body class="admin-body">
  <a href="#admin-main-content" class="skip-link">Skip to content</a>

  <div class="admin-shell">
    <?php \App\Core\View::component('admin/sidebar'); ?>

    <div class="admin-main" style="flex:1;min-width:0;">
      <?php \App\Core\View::component('admin/topbar', ['title' => $title ?? 'Dashboard']); ?>

      <main id="admin-main-content" class="admin-content">
        <?php \App\Core\View::component('flash-messages'); ?>
        <?= $content ?>
      </main>
    </div>
  </div>

  <?php \App\Core\View::component('admin/confirm-modal'); ?>

  <script src="<?= e(asset('js/admin.js')) ?>" defer></script>
</body>
</html>
