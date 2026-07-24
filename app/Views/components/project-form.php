<?php
/**
 * Consultation form. Expects $projectTypes (assoc value => label, from
 * HomeController — must stay in sync with LeadController's constants,
 * which perform the actual server-side validation).
 *
 * Single-step form posting to the existing /start-your-project route
 * (LeadController::store — unchanged). Three fields the backend's
 * message composer expects (timeframe, budget, preferred_contact) are
 * no longer shown to the visitor — sent as hidden defaults instead, so
 * the simplified UI doesn't require any backend change.
 *
 * Success/error state comes from the existing flash-message
 * architecture (SessionService::flash in LeadController), read here
 * under the 'lead_success' / 'lead_error' keys.
 */
$allFlashes = flashes();
$leadSuccess = $allFlashes['lead_success'][0] ?? null;
$leadError = $allFlashes['lead_error'][0] ?? null;
?>
<div class="consultation-form">
  <?php if ($leadSuccess !== null): ?>
    <div class="consultation-form__success">
      <div class="consultation-form__success-icon" aria-hidden="true">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </div>
      <h3>Request received</h3>
      <p class="text-muted"><?= e($leadSuccess) ?></p>
    </div>
  <?php else: ?>
    <?php if ($leadError !== null): ?>
      <div class="alert alert-error" role="alert"><?= e($leadError) ?></div>
    <?php endif; ?>

    <form action="<?= e(base_url('start-your-project')) ?>" method="post" enctype="multipart/form-data" novalidate>
      <?= csrf_field() ?>
      <input type="hidden" name="timeframe" value="exploring">
      <input type="hidden" name="budget" value="not-sure">
      <input type="hidden" name="preferred_contact" value="either">

      <div class="hp-field" aria-hidden="true">
        <label for="website">Leave this field blank</label>
        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
      </div>

      <div class="form-group">
        <label class="form-label" for="name">Name</label>
        <input class="form-control" type="text" id="name" name="name" value="<?= e(old('name')) ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label" for="phone">Phone</label>
        <input class="form-control" type="tel" id="phone" name="phone" value="<?= e(old('phone')) ?>" autocomplete="tel">
      </div>

      <div class="form-group">
        <label class="form-label" for="email">Email</label>
        <input class="form-control" type="email" id="email" name="email" value="<?= e(old('email')) ?>" autocomplete="email" required>
      </div>

      <div class="form-group">
        <label class="form-label" for="city">Project city</label>
        <input class="form-control" type="text" id="city" name="city" value="<?= e(old('city')) ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label" for="project_type">Project type</label>
        <select class="form-control" id="project_type" name="project_type" required>
          <option value="">Select one</option>
          <?php foreach ($projectTypes as $value => $label): ?>
            <option value="<?= e($value) ?>" <?= old('project_type') === $value ? 'selected' : '' ?>><?= e($label) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label" for="description">Project description</label>
        <textarea class="form-control" id="description" name="description" rows="4"><?= e(old('description')) ?></textarea>
      </div>

      <div class="form-group">
        <label class="form-label" for="photo">Add a photo (optional)</label>
        <input class="form-control" type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp">
        <p class="form-hint">JPG, PNG, or WebP, up to 8MB.</p>
      </div>

      <div class="form-group">
        <label style="display:flex;align-items:flex-start;gap:.6em;font-weight:400">
          <input type="checkbox" name="consent" value="1" style="margin-top:.3em" required>
          <span>I agree to be contacted by Barraza's Construction about my project.</span>
        </label>
      </div>

      <button type="submit" class="btn btn-primary btn-block">Send Request</button>
    </form>
  <?php endif; ?>
</div>
