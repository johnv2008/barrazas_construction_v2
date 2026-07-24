<div class="auth-card__brand">Barraza's <span>Construction</span></div>
<p class="auth-card__subtitle">Sign in to manage your website</p>

<form action="<?= e(admin_url('login')) ?>" method="post" class="form--on-dark" novalidate>
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
  </div>

  <div class="form-group">
    <label class="form-label" for="password">Password</label>
    <input
      type="password"
      id="password"
      name="password"
      class="form-control"
      autocomplete="current-password"
      required
    >
  </div>

  <button type="submit" class="btn btn-primary btn-block">Sign In</button>
</form>

<p class="auth-card__footer">
  <a href="<?= e(admin_url('forgot-password')) ?>">Forgot your password?</a>
</p>
