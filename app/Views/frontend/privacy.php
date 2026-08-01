<?php
/**
 * Privacy notice.
 *
 * Every statement here describes something the code in this repository
 * actually does. Nothing is aspirational and nothing is boilerplate: the
 * form fields listed are the form's real fields, the analytics behaviour
 * is what components/analytics.php really implements, and the cookie list
 * is the complete set the site sets.
 *
 * It is written as disclosure, not as a legal shield. It has not been
 * reviewed by a lawyer and it says so, because a privacy notice that
 * overstates its own authority is itself a small dishonesty.
 *
 * @var bool $analyticsEnabled
 * @var string $updated
 */
?>
<section class="ch ch--doc" id="top" data-chapter="01" data-chapter-label="Privacy">
  <div class="container doc">
    <span class="eyebrow eyebrow--accent">Privacy</span>
    <h1 class="doc__heading" data-reveal>What this website collects, and why.</h1>
    <p class="doc__lede" data-reveal>
      Short version: a contact form you choose to fill in, and — only if you agree —
      an anonymous count of page visits. Nothing is sold, and nothing is shared with
      advertisers.
    </p>

    <p class="doc__stamp">Last updated <?= e($updated) ?></p>

    <h2>When you contact us</h2>
    <p>
      The consultation form asks for your name, email address, phone number, the city
      the project is in, the type of project, a description, and optionally a photo.
      We store that so we can reply to you and so we have a record of the conversation
      if the job goes ahead. It is not used for anything else, and it is not passed to
      any third party.
    </p>
    <p>
      If you would like that record deleted, email us and we will remove it. You do not
      need to give a reason.
    </p>

    <?php if ($analyticsEnabled): ?>
      <h2>If you accept analytics</h2>
      <p>
        We use Google Analytics to count visits and see which pages people actually
        read — mostly so we know which work is worth photographing next. It records
        pages viewed, roughly where in the world the visit came from, and what kind of
        device it was on.
      </p>
      <p>
        Your IP address is shortened before it is stored, and Google's advertising
        features are switched off, so this data cannot be used to target ads at you
        anywhere.
      </p>
      <p>
        <strong>If you decline, nothing is sent to Google at all.</strong> The analytics
        script is not loaded rather than loaded-and-silenced, so declining means no
        request leaves your browser for it.
      </p>
    <?php endif; ?>

    <h2>Cookies and storage</h2>
    <p>The complete list of what this site stores in your browser:</p>
    <ul class="doc__list">
      <li>
        <strong>Your analytics choice.</strong> Stored in your browser so we do not ask
        again. It never leaves your device.
      </li>
      <li>
        <strong>A form security token.</strong> Set only while you are using the contact
        form, to stop other websites submitting it on your behalf.
      </li>
      <?php if ($analyticsEnabled): ?>
        <li>
          <strong>Google Analytics cookies.</strong> Set only if you accepted, and only
          then.
        </li>
      <?php endif; ?>
    </ul>
    <p>
      There is no advertising, no tracking pixel, no social-media embed, and no
      third-party font or script beyond the analytics above.
    </p>

    <h2>Changing your mind</h2>
    <p>
      Clearing this site's data in your browser removes your stored choice, and you will
      be asked again on your next visit. In most browsers that is under Settings →
      Privacy → clear site data. You can also email us and we will confirm what we hold.
    </p>

    <h2>Who to contact</h2>
    <p class="doc__contact">
      Barraza's Construction Inc.<br>
      16561 Jacksonville Rd, Jamestown, CA 95327
    </p>

    <p class="doc__note">
      This notice describes what the website actually does, in plain language. It is not
      legal advice and has not been reviewed by an attorney — if you are the site owner
      reading this, have one check it against your obligations before you rely on it.
    </p>

    <a href="<?= e(base_url('/')) ?>" class="link-arrow">Back to the site</a>
  </div>
</section>
