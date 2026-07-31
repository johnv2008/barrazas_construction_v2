<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php \App\Core\View::component('head-icons'); ?>
  <title><?= e($title ?? 'Error') ?> · Barraza's Construction</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="stylesheet" href="<?= e(asset('css/variables.css')) ?>">
  <link rel="stylesheet" href="<?= e(asset('css/base.css')) ?>">
  <link rel="stylesheet" href="<?= e(asset('css/components.css')) ?>">
</head>
<body style="background:var(--color-charcoal-deep);color:var(--color-ivory);">
  <main style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:var(--space-md);text-align:center;">
    <div style="max-width:32rem;">
      <?= $content ?>
      <p style="margin-top:var(--space-lg);">
        <a href="<?= e(base_url('/')) ?>" class="btn btn-secondary">Return Home</a>
      </p>
    </div>
  </main>
</body>
</html>
