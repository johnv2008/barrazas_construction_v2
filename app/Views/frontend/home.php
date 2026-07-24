<?php
/**
 * @var array $hero
 * @var array $capabilities
 * @var array $transformation
 * @var array $manifesto
 * @var array $services
 * @var array $contactSheet
 * @var array $narrative
 * @var array $trustManifesto
 * @var array $blueprintSteps
 * @var array $planningGuide
 * @var array $projectTypes
 * @var array $timeframes
 * @var array $budgets
 * @var array $contactMethods
 */
?>
<!-- ============ 1. Split hero ============ -->
<section class="hero-split bg-grid">
  <div class="container hero-split__grid">
    <div class="hero-split__content">
      <span class="eyebrow"><?= e($hero['eyebrow']) ?></span>
      <h1>
        <?php foreach ($hero['headingLines'] as $i => $line): ?>
          <?= $i > 0 ? '<br>' : '' ?><?= e($line) ?>
        <?php endforeach; ?>
      </h1>
      <p class="hero-split__lead"><?= e($hero['lead']) ?></p>
      <div class="hero-split__actions">
        <a href="<?= e($hero['primaryCta']['href']) ?>" class="btn btn-primary"><?= e($hero['primaryCta']['label']) ?></a>
        <a href="<?= e($hero['secondaryCta']['href']) ?>" class="btn btn-secondary"><?= e($hero['secondaryCta']['label']) ?></a>
      </div>
      <ul class="hero-split__trust">
        <?php foreach ($hero['trust'] as $line): ?>
          <li><?= e($line) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="hero-split__media">
      <span class="hero-split__index">01 / Bay Area</span>
      <div class="hero-split__frame hero-split__frame--primary">
        <img src="<?= e($hero['primaryImage']) ?>" alt="<?= e($hero['primaryAlt']) ?>" fetchpriority="high">
        <span class="hero-split__tag"><?= e($hero['tag']) ?></span>
        <span class="hero-split__accent" aria-hidden="true"></span>
      </div>
      <div class="hero-split__frame hero-split__frame--inset">
        <img src="<?= e($hero['insetImage']) ?>" alt="<?= e($hero['insetAlt']) ?>" loading="lazy" decoding="async">
      </div>
    </div>
  </div>
</section>

<!-- ============ 2. Capability strip ============ -->
<?php \App\Core\View::component('capability-strip', ['items' => $capabilities]); ?>

<!-- ============ 3. Transformation feature ============ -->
<section class="section section--dark" id="projects">
  <div class="container">
    <div class="section-heading section-heading--center">
      <span class="eyebrow">Progress to Completion</span>
      <h2>The Result Matters.<br>So Does Everything Behind It.</h2>
    </div>
    <?php \App\Core\View::component('before-after', ['item' => $transformation]); ?>
  </div>
</section>

<!-- ============ 4. Manifesto moment ============ -->
<section class="manifesto">
  <p class="manifesto__text">
    <?= e($manifesto['line1']) ?><br>
    <span class="text-accent"><?= e($manifesto['line2']) ?></span>
  </p>
</section>

<!-- ============ 5. Sticky services ============ -->
<section class="section section--tight" id="services" data-sticky-services>
  <div class="container">
    <div class="section-heading">
      <span class="eyebrow">What We Build</span>
      <h2>Services, By the Layer</h2>
    </div>
  </div>
  <div class="container container--wide">
    <?php \App\Core\View::component('sticky-services', ['services' => $services]); ?>
  </div>
</section>

<!-- ============ 6. Project contact sheet ============ -->
<section class="section section--soft-dark" id="work">
  <div class="container">
    <div class="section-heading">
      <span class="eyebrow">Recent Work</span>
      <h2>Project Contact Sheet</h2>
    </div>
    <div class="contact-sheet">
      <?php foreach ($contactSheet as $index => $item): ?>
        <a href="#start-your-project" class="contact-sheet__item contact-sheet__item--<?= e($item['size']) ?>">
          <img src="<?= e($item['image']) ?>" alt="<?= e($item['alt']) ?>" loading="lazy" decoding="async">
          <span class="contact-sheet__overlay">
            <span class="contact-sheet__number"><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
            <span class="contact-sheet__title"><?= e($item['title']) ?></span>
            <span class="contact-sheet__meta-line"><?= e($item['category']) ?> &middot; <?= e($item['city']) ?></span>
            <span class="contact-sheet__meta-line"><?= e($item['scope']) ?></span>
          </span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ 7. Featured project narrative ============ -->
<section class="section" id="about">
  <div class="container">
    <div class="narrative">
      <div class="narrative__media">
        <img class="narrative__result" src="<?= e($narrative['resultImage']) ?>" alt="<?= e($narrative['resultAlt']) ?>" loading="lazy" decoding="async">
        <div class="narrative__detail-stack narrative__detail-stack--single">
          <img src="<?= e($narrative['progressImage']) ?>" alt="<?= e($narrative['progressAlt']) ?>" loading="lazy" decoding="async">
        </div>
        <p class="narrative__caption">Roof replacement in progress on the same hillside property</p>
      </div>
      <div class="narrative__copy">
        <span class="eyebrow"><?= e($narrative['eyebrow']) ?></span>
        <h2><?= e($narrative['heading']) ?></h2>
        <dl class="narrative__facts">
          <div>
            <dt>Goal</dt>
            <dd><?= e($narrative['goal']) ?></dd>
          </div>
          <div>
            <dt>Work Completed</dt>
            <dd><?= e($narrative['workCompleted']) ?></dd>
          </div>
          <div>
            <dt>Result</dt>
            <dd><?= e($narrative['result']) ?></dd>
          </div>
        </dl>
        <a href="<?= e($narrative['relatedHref']) ?>" class="narrative__link"><?= e($narrative['relatedLabel']) ?> &rarr;</a>
      </div>
    </div>
  </div>
</section>

<!-- ============ 8. Trust manifesto ============ -->
<section class="section section--tight">
  <div class="container">
    <span class="eyebrow">Why Barraza's</span>
    <h2 class="trust-manifesto__headline">
      <?= e($trustManifesto['headline1']) ?><br>
      <?= e($trustManifesto['headline2']) ?><br>
      <?= e($trustManifesto['headline3']) ?>
    </h2>
    <ul class="trust-manifesto__list">
      <?php foreach ($trustManifesto['items'] as $item): ?>
        <li><?= e($item) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<!-- ============ 9. Process as blueprint ============ -->
<section class="section blueprint-process" id="process">
  <div class="container">
    <div class="section-heading section-heading--center">
      <span class="eyebrow">How We Work</span>
      <h2>Our Process, Step by Step</h2>
    </div>
  </div>
  <div class="container container--wide">
    <div class="blueprint-steps">
      <?php foreach ($blueprintSteps as $index => $step): ?>
        <details class="blueprint-step"<?= $index === 0 ? ' open' : '' ?>>
          <summary>
            <span>
              <span class="blueprint-step__number"><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
              <h3><?= e($step['title']) ?></h3>
            </span>
            <span class="blueprint-step__toggle" aria-hidden="true">+</span>
          </summary>
          <div class="blueprint-step__body">
            <p><?= e($step['description']) ?></p>
            <?php if (isset($step['detailImage'])): ?>
              <img class="blueprint-step__detail-img" src="<?= e($step['detailImage']) ?>" alt="<?= e($step['detailAlt']) ?>" loading="lazy" decoding="async">
            <?php endif; ?>
          </div>
        </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ 10. Planning guide (SEO content) ============ -->
<section class="section">
  <div class="container">
    <div class="section-heading">
      <span class="eyebrow">Getting Started</span>
      <h2>Planning a Residential Remodel in the Bay Area</h2>
      <p class="text-muted" style="max-width:56ch">
        A few things worth thinking through before your first consultation for a kitchen remodel,
        bathroom renovation, whole-home project, or ADU.
      </p>
    </div>
    <div class="planning-guide__grid">
      <?php foreach ($planningGuide as $item): ?>
        <article class="planning-guide__item">
          <h3><?= e($item['heading']) ?></h3>
          <p><?= e($item['body']) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ 11. Start your project (guided form) ============ -->
<section class="section section--dark bg-grid">
  <div class="container container--narrow">
    <div class="section-heading section-heading--center">
      <span class="eyebrow">Get Started</span>
      <h2>Let's Start With Your Vision.</h2>
      <p class="text-muted">
        Tell us what you're planning. We'll review the details and contact you to discuss the next step.
      </p>
    </div>
    <?php \App\Core\View::component('project-form', [
        'projectTypes' => $projectTypes,
        'timeframes' => $timeframes,
        'budgets' => $budgets,
        'contactMethods' => $contactMethods,
    ]); ?>
  </div>
</section>
