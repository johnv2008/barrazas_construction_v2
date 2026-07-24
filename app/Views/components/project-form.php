<?php
/**
 * Guided "Start Your Project" form. Expects:
 *   $projectTypes, $timeframes, $budgets, $contactMethods — assoc
 *   arrays of value => label (from LeadController's constants).
 *
 * Progressive enhancement: without JS both steps render visible in
 * one form and submit normally on "Submit Project Details" (there is
 * no separate step-1 submit). With JS, app.js hides step 2 and adds
 * Continue/Back controls purely as a presentation layer.
 *
 * Success/error state comes from the existing flash-message
 * architecture (SessionService::flash in LeadController), read here
 * under the 'lead_success' / 'lead_error' keys so it doesn't collide
 * with any other flash usage on the page.
 */
$allFlashes = flashes();
$leadSuccess = $allFlashes['lead_success'][0] ?? null;
$leadError = $allFlashes['lead_error'][0] ?? null;
?>
<div class="project-form" id="start-your-project">
  <?php if ($leadSuccess !== null): ?>
    <div class="project-form__success">
      <div class="project-form__success-icon" aria-hidden="true">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </div>
      <h3 style="color:var(--pure-white)">Request received</h3>
      <p class="text-muted" style="max-width:32rem;margin-inline:auto"><?= e($leadSuccess) ?></p>
    </div>
  <?php else: ?>
    <?php if ($leadError !== null): ?>
      <div class="alert alert-error" role="alert"><?= e($leadError) ?></div>
    <?php endif; ?>

    <form
      action="<?= e(base_url('start-your-project')) ?>"
      method="post"
      enctype="multipart/form-data"
      class="form--on-dark"
      data-project-form
      novalidate
    >
      <?= csrf_field() ?>

      <div class="hp-field" aria-hidden="true">
        <label for="website">Leave this field blank</label>
        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
      </div>

      <ol class="project-form__steps-indicator">
        <li data-form-indicator class="is-active">Step 1 · Your Project</li>
        <li data-form-indicator>Step 2 · Your Details</li>
      </ol>

      <div class="project-form__step" data-form-step>
        <h3>What are you planning?</h3>

        <div class="project-type-grid">
          <?php foreach ($projectTypes as $value => $label): ?>
            <div class="project-type-option">
              <input
                type="radio"
                name="project_type"
                id="pt-<?= e($value) ?>"
                value="<?= e($value) ?>"
                <?= old('project_type') === $value ? 'checked' : '' ?>
                required
              >
              <label for="pt-<?= e($value) ?>"><?= e($label) ?></label>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="form-group">
          <label class="form-label" for="city">Project city</label>
          <input class="form-control" type="text" id="city" name="city" value="<?= e(old('city')) ?>" required>
        </div>

        <div class="form-group">
          <label class="form-label" for="timeframe">Desired start timeframe</label>
          <select class="form-control" id="timeframe" name="timeframe">
            <?php foreach ($timeframes as $value => $label): ?>
              <option value="<?= e($value) ?>" <?= old('timeframe') === $value ? 'selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label" for="budget">Estimated investment range</label>
          <select class="form-control" id="budget" name="budget">
            <?php foreach ($budgets as $value => $label): ?>
              <option value="<?= e($value) ?>" <?= old('budget') === $value ? 'selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="project-form__actions">
          <span></span>
          <button type="button" class="btn btn-primary" data-step-continue>Continue</button>
        </div>
      </div>

      <div class="project-form__step" data-form-step hidden>
        <h3>Tell us about you</h3>

        <div class="form-group">
          <label class="form-label" for="name">Name</label>
          <input class="form-control" type="text" id="name" name="name" value="<?= e(old('name')) ?>" required>
        </div>

        <div class="form-group">
          <label class="form-label" for="email">Email</label>
          <input class="form-control" type="email" id="email" name="email" value="<?= e(old('email')) ?>" autocomplete="email" required>
        </div>

        <div class="form-group">
          <label class="form-label" for="phone">Phone</label>
          <input class="form-control" type="tel" id="phone" name="phone" value="<?= e(old('phone')) ?>" autocomplete="tel">
        </div>

        <fieldset class="form-group" style="border:none;padding:0;margin:0 0 var(--space-md)">
          <legend class="form-label">Preferred contact method</legend>
          <?php foreach ($contactMethods as $value => $label): ?>
            <label style="display:inline-flex;align-items:center;gap:.4em;margin-right:var(--space-md);font-weight:400">
              <input type="radio" name="preferred_contact" value="<?= e($value) ?>" <?= old('preferred_contact', 'either') === $value ? 'checked' : '' ?>>
              <?= e($label) ?>
            </label>
          <?php endforeach; ?>
        </fieldset>

        <div class="form-group">
          <label class="form-label" for="description">Project description</label>
          <textarea class="form-control" id="description" name="description" rows="4"><?= e(old('description')) ?></textarea>
        </div>

        <div class="form-group">
          <label class="form-label" for="photo">Add a photo (optional)</label>
          <input class="form-control" type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp">
          <p class="form-hint" style="color:var(--color-text-on-dark-muted)">JPG, PNG, or WebP, up to 8MB.</p>
        </div>

        <div class="form-group">
          <label style="display:flex;align-items:flex-start;gap:.6em;font-weight:400">
            <input type="checkbox" name="consent" value="1" style="margin-top:.3em" required>
            <span>I agree to be contacted by Barraza's Construction about my project.</span>
          </label>
        </div>

        <div class="project-form__actions">
          <button type="button" class="btn btn-secondary" data-step-back>Back</button>
          <button type="submit" class="btn btn-primary">Submit Project Details</button>
        </div>
      </div>
    </form>
  <?php endif; ?>
</div>
