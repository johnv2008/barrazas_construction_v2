<?php
/** @var array<string, array<int, string>> $errors */
/** @var string $name */
/** @var string $email */
?>
<div class="auth-card__brand">Barraza's <span>Construction</span></div>
<p class="auth-card__subtitle">Create the first administrator account</p>

<?php if ($errors !== []): ?>
  <div class="alert alert-error" role="alert">
    <?php foreach ($errors as $fieldErrors): ?>
      <?php foreach ($fieldErrors as $error): ?>
        <div><?= e($error) ?></div>
      <?php endforeach; ?>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<form action="<?= e(base_url('install.php')) ?>" method="post" class="form--on-dark" novalidate>
  <?= csrf_field() ?>

  <div class="form-group">
    <label class="form-label" for="name">Full name</label>
    <input type="text" id="name" name="name" class="form-control" value="<?= e($name) ?>" required autofocus>
  </div>

  <div class="form-group">
    <label class="form-label" for="email">Email address</label>
    <input type="email" id="email" name="email" class="form-control" value="<?= e($email) ?>" autocomplete="username" required>
  </div>

  <div class="form-group">
    <label class="form-label" for="password">Password</label>
    <input type="password" id="password" name="password" class="form-control" autocomplete="new-password" minlength="12" required>
    <p class="form-hint" style="color:var(--color-text-on-dark-muted)">At least 12 characters.</p>
  </div>

  <div class="form-group">
    <label class="form-label" for="password_confirmation">Confirm password</label>
    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" autocomplete="new-password" minlength="12" required>
  </div>

  <button type="submit" class="btn btn-primary btn-block">Create Administrator</button>
</form>

<p class="auth-card__footer">
  Remember to delete <code>public/install.php</code> immediately after this succeeds.
</p>
