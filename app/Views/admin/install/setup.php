<?php
/**
 * IMPORTANT: every URL in this file is RELATIVE, never base_url().
 *
 * base_url() reads APP_URL from .env — and during setup .env either does
 * not exist yet or contains whatever was left in it from another machine.
 * Posting to base_url('setup.php') therefore sends the form to the wrong
 * host and the wizard silently does nothing. Caught in testing: the local
 * .env pointed at localhost:8000 while the server ran on :8899, and the
 * submission vanished with no error at all.
 *
 * The same applies to the links on the completion screen: Config was
 * loaded at bootstrap, BEFORE this request wrote .env, so base_url() is
 * still stale even at that point.
 *
 * Setup wizard UI. Four steps plus a completion screen.
 *
 * Deliberately plain: this is seen once, by one person, before the site
 * exists. It reuses the admin auth card so it still looks like the brand,
 * but nothing here is worth designing further.
 *
 * @var int $step
 * @var array<int, string> $errors
 * @var string|null $notice
 * @var array<int, array{label:string, ok:bool, detail:string}> $requirements
 * @var bool $requirementsMet
 * @var string $guessedUrl
 * @var array $state
 */
$steps = [1 => 'Requirements', 2 => 'Database', 3 => 'Site', 4 => 'Administrator'];
?>
<div class="auth-card__brand">Barraza's <span>Construction</span></div>
<p class="auth-card__subtitle">
  <?= $step >= 5 ? 'Setup complete' : 'Setup · step ' . e((string) $step) . ' of 4' ?>
</p>

<?php if ($step < 5): ?>
  <ol class="setup-steps" aria-label="Setup progress">
    <?php foreach ($steps as $n => $label): ?>
      <li<?= $n === $step ? ' aria-current="step"' : ($n < $step ? ' data-done="true"' : '') ?>>
        <span><?= e((string) $n) ?></span><?= e($label) ?>
      </li>
    <?php endforeach; ?>
  </ol>
<?php endif; ?>

<?php if ($errors !== []): ?>
  <div class="alert alert-error" role="alert">
    <?php foreach ($errors as $error): ?>
      <div><?= e($error) ?></div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php if ($notice !== null): ?>
  <div class="alert alert-success" role="status"><?= e($notice) ?></div>
<?php endif; ?>


<?php if ($step === 1): ?>
  <ul class="setup-checks">
    <?php foreach ($requirements as $r): ?>
      <li data-ok="<?= $r['ok'] ? 'true' : 'false' ?>">
        <span class="setup-checks__mark" aria-hidden="true"><?= $r['ok'] ? '&#10003;' : '&#33;' ?></span>
        <span class="setup-checks__label"><?= e($r['label']) ?></span>
        <span class="setup-checks__detail"><?= e($r['detail']) ?></span>
      </li>
    <?php endforeach; ?>
  </ul>

  <?php if ($requirementsMet): ?>
    <form action="setup.php" method="get">
      <input type="hidden" name="step" value="2">
      <button type="submit" class="btn btn-primary btn-block">Continue</button>
    </form>
  <?php else: ?>
    <p class="form-hint">Fix the items marked above, then reload this page. Nothing has been changed yet.</p>
  <?php endif; ?>


<?php elseif ($step === 2): ?>
  <p class="setup-lede">
    Enter the database you created in your hosting control panel. The wizard will
    test the connection and import the tables before writing anything.
  </p>

  <form action="setup.php" method="post" class="form--on-dark" novalidate>
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="database">

    <div class="form-group">
      <label class="form-label" for="db_name">Database name</label>
      <input type="text" id="db_name" name="db_name" class="form-control" required autofocus
             value="<?= e($state['db']['name'] ?? '') ?>" autocomplete="off">
      <p class="form-hint">Including the account prefix, e.g. <code>account_sitedb</code>.</p>
    </div>

    <div class="form-group">
      <label class="form-label" for="db_user">Database user</label>
      <input type="text" id="db_user" name="db_user" class="form-control" required
             value="<?= e($state['db']['user'] ?? '') ?>" autocomplete="off">
    </div>

    <div class="form-group">
      <label class="form-label" for="db_password">Database password</label>
      <input type="password" id="db_password" name="db_password" class="form-control" autocomplete="off">
    </div>

    <div class="form-group">
      <label class="form-label" for="db_host">Host</label>
      <input type="text" id="db_host" name="db_host" class="form-control"
             value="<?= e($state['db']['host'] ?? 'localhost') ?>" autocomplete="off">
      <p class="form-hint">Almost always <code>localhost</code> on shared hosting.</p>
    </div>

    <button type="submit" class="btn btn-primary btn-block">Test connection &amp; import</button>
  </form>


<?php elseif ($step === 3): ?>
  <p class="setup-lede">
    The site address is written into links, the sitemap and structured data,
    so it must match how the site actually resolves.
  </p>

  <form action="setup.php" method="post" class="form--on-dark" novalidate>
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="site">

    <div class="form-group">
      <label class="form-label" for="app_url">Site address</label>
      <input type="url" id="app_url" name="app_url" class="form-control" required autofocus
             value="<?= e($guessedUrl) ?>">
      <p class="form-hint">Include https:// and match your www / non-www choice.</p>
    </div>

    <div class="form-group">
      <label class="form-label" for="app_timezone">Timezone</label>
      <input type="text" id="app_timezone" name="app_timezone" class="form-control"
             value="America/Los_Angeles">
    </div>

    <div class="form-group">
      <label class="form-label" for="license_number">CSLB licence number</label>
      <input type="text" id="license_number" name="license_number" class="form-control"
             inputmode="numeric" autocomplete="off" placeholder="Digits only">
      <p class="form-hint">
        California B&amp;P Code 7030.5 requires this in advertising. Leave blank to
        add later — the licence line is hidden entirely until it is set.
      </p>
    </div>

    <button type="submit" class="btn btn-primary btn-block">Save configuration</button>
  </form>


<?php elseif ($step === 4): ?>
  <p class="setup-lede">Last step. This account signs in to the admin area.</p>

  <form action="setup.php" method="post" class="form--on-dark" novalidate>
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="admin">

    <div class="form-group">
      <label class="form-label" for="name">Full name</label>
      <input type="text" id="name" name="name" class="form-control" required autofocus>
    </div>

    <div class="form-group">
      <label class="form-label" for="email">Email address</label>
      <input type="email" id="email" name="email" class="form-control" required autocomplete="username">
    </div>

    <div class="form-group">
      <label class="form-label" for="password">Password</label>
      <input type="password" id="password" name="password" class="form-control" required
             minlength="12" autocomplete="new-password">
      <p class="form-hint">At least 12 characters.</p>
    </div>

    <div class="form-group">
      <label class="form-label" for="password_confirmation">Confirm password</label>
      <input type="password" id="password_confirmation" name="password_confirmation"
             class="form-control" required minlength="12" autocomplete="new-password">
    </div>

    <button type="submit" class="btn btn-primary btn-block">Create account &amp; finish</button>
  </form>


<?php else: ?>
  <div class="alert alert-error" role="alert">
    <strong>Delete these two files now.</strong>
    <div>public_html/setup.php</div>
    <div>public_html/install.php</div>
    <div style="margin-top:.5rem">
      Both are locked and will refuse to run again, but a configuration wizard
      should not remain in a public web root.
    </div>
  </div>

  <p class="setup-lede">
    Everything is configured. Visit the site to check it renders, then submit the
    consultation form once — that is the only test that proves the database write
    path works end to end.
  </p>

  <a href="./" class="btn btn-primary btn-block">View the site</a>
  <a href="admin/login" class="btn btn-link btn-block" style="margin-top:.75rem">Sign in to the admin</a>
<?php endif; ?>
