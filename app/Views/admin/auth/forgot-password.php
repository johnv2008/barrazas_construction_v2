<div class="auth-card__brand">Barraza's <span>Construction</span></div>
<p class="auth-card__subtitle">Reset your administrator password</p>

<form action="<?= e(admin_url('forgot-password')) ?>" method="post" class="form--on-dark" novalidate>
  <?= csrf_field() ?>

  <div class="form-group">
    <label class="form-label" for="email">Email address</label>
    <input
      type="email"
      id="email"
      name="email"
      class="form-control"
      value="<?= e(old('email')) ?>"
      autocomplete="username"
      required
      autofocus
    >
    <p class="form-hint" style="color:var(--color-text-on-dark-muted)">
      We'll send reset instructions if an account matches this address.
    </p>
  </div>

  <button type="submit" class="btn btn-primary btn-block">Send Reset Instructions</button>
</form>

<p class="auth-card__footer">
  <a href="<?= e(admin_url('login')) ?>">Back to sign in</a>
</p>
