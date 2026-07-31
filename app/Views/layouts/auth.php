<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title ?? 'Sign In') ?> · Barraza's Construction Admin</title>
  <meta name="robots" content="noindex, nofollow">

  <?php \App\Core\View::component('head-icons'); ?>

  <link rel="stylesheet" href="<?= e(asset('css/variables.css')) ?>">
  <link rel="stylesheet" href="<?= e(asset('css/base.css')) ?>">
  <link rel="stylesheet" href="<?= e(asset('css/components.css')) ?>">
  <link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
</head>
<body>
  <a href="#auth-main-content" class="skip-link">Skip to content</a>
  <main id="auth-main-content" class="auth-screen">
    <div class="auth-card">
      <?php \App\Core\View::component('flash-messages'); ?>
      <?= $content ?>
    </div>
  </main>
</body>
</html>
