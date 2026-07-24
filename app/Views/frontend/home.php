<?php
/**
 * @var array $hero
 * @var array $trustStrip
 * @var array $intro
 * @var array $services
 * @var array $featuredProject
 * @var array $selectedProjects
 * @var array $whyChoose
 * @var array $process
 * @var array $planningGuide
 * @var array $consultation
 * @var array $projectTypes
 */
?>
<!-- ============ Hero ============ -->
<section class="hero-full">
  <div class="hero-full__media">
    <img src="<?= e($hero['primaryImage']) ?>" alt="<?= e($hero['primaryAlt']) ?>" fetchpriority="high">
  </div>
  <div class="hero-full__scrim" aria-hidden="true"></div>
  <div class="container">
    <div class="hero-full__content">
      <span class="eyebrow"><?= e($hero['eyebrow']) ?></span>
      <h1><?= e($hero['heading']) ?></h1>
      <p class="hero-full__lead"><?= e($hero['lead']) ?></p>
      <div class="hero-full__actions">
        <a href="<?= e($hero['primaryCta']['href']) ?>" class="btn btn-primary"><?= e($hero['primaryCta']['label']) ?></a>
        <a href="<?= e($hero['secondaryCta']['href']) ?>" class="btn btn-secondary"><?= e($hero['secondaryCta']['label']) ?></a>
      </div>
      <p class="hero-full__trust">
        <?php foreach ($hero['trust'] as $line): ?>
          <span><?= e($line) ?></span>
        <?php endforeach; ?>
      </p>
    </div>
  </div>
</section>

<!-- ============ Trust strip ============ -->
<div class="trust-strip">
  <ul class="trust-strip__list">
    <?php foreach ($trustStrip as $item): ?>
      <li class="trust-strip__item"><?= e($item) ?></li>
    <?php endforeach; ?>
  </ul>
</div>

<!-- ============ Company introduction ============ -->
<section class="section" id="about">
  <div class="container intro">
    <div>
      <h2><?= e($intro['heading']) ?></h2>
      <span class="accent-line"></span>
      <p class="intro__body"><?= e($intro['body']) ?></p>
    </div>
    <img class="intro__image" src="<?= e($intro['image']) ?>" alt="<?= e($intro['imageAlt']) ?>" loading="lazy" decoding="async">
  </div>
</section>

<!-- ============ Services ============ -->
<section class="section section--tight section--soft" id="services">
  <div class="container">
    <div class="section-heading">
      <span class="eyebrow">What We Do</span>
      <h2>Services</h2>
    </div>
    <div class="card-grid card-grid--4" data-reveal-group>
      <?php foreach ($services as $service): ?>
        <div data-reveal>
          <?php \App\Core\View::component('service-card', ['item' => $service]); ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ Featured project ============ -->
<section class="section">
  <div class="container">
    <div class="section-heading">
      <span class="eyebrow"><?= e($featuredProject['eyebrow']) ?></span>
      <h2><?= e($featuredProject['heading']) ?></h2>
    </div>
    <div class="featured-project">
      <div>
        <img class="featured-project__main" src="<?= e($featuredProject['mainImage']) ?>" alt="<?= e($featuredProject['mainAlt']) ?>" loading="lazy" decoding="async">
        <?php if (!empty($featuredProject['thumbs'])): ?>
          <div class="featured-project__thumbs">
            <?php foreach ($featuredProject['thumbs'] as $thumb): ?>
              <img src="<?= e($thumb['image']) ?>" alt="<?= e($thumb['alt']) ?>" loading="lazy" decoding="async">
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
      <div>
        <p class="featured-project__meta"><?= e($featuredProject['category']) ?> &middot; <?= e($featuredProject['city']) ?></p>
        <p><?= e($featuredProject['summary']) ?></p>
        <a href="<?= e($featuredProject['href']) ?>" class="featured-project__link">View project &rarr;</a>
      </div>
    </div>
  </div>
</section>

<!-- ============ Selected projects ============ -->
<section class="section section--tight section--soft" id="projects">
  <div class="container">
    <div class="section-heading">
      <span class="eyebrow">Recent Work</span>
      <h2>Selected projects</h2>
    </div>
    <div class="card-grid card-grid--2" data-reveal-group>
      <?php foreach ($selectedProjects as $project): ?>
        <div data-reveal>
          <?php \App\Core\View::component('project-card', ['item' => $project]); ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ Why choose Barraza's ============ -->
<section class="section">
  <div class="container">
    <div class="section-heading">
      <span class="eyebrow">Why Barraza's</span>
      <h2><?= e($whyChoose['heading']) ?></h2>
    </div>
    <div class="benefits-grid">
      <?php foreach ($whyChoose['items'] as $item): ?>
        <div class="benefit">
          <h3><?= e($item['title']) ?></h3>
          <p><?= e($item['body']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ Process ============ -->
<section class="section section--tight section--soft" id="process">
  <div class="container">
    <div class="section-heading">
      <span class="eyebrow">How We Work</span>
      <h2>Our process</h2>
    </div>
    <div class="process-compact">
      <?php foreach ($process as $index => $step): ?>
        <div class="process-compact__step">
          <span class="process-compact__number"><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
          <h3><?= e($step['title']) ?></h3>
          <p><?= e($step['description']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ Planning guidance ============ -->
<section class="section">
  <div class="container">
    <div class="section-heading">
      <span class="eyebrow">Getting Started</span>
      <h2><?= e($planningGuide['heading']) ?></h2>
    </div>
    <div class="planning-grid">
      <?php foreach ($planningGuide['items'] as $item): ?>
        <div class="planning-grid__item">
          <h3><?= e($item['heading']) ?></h3>
          <p><?= e($item['body']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ Consultation ============ -->
<section class="section section--tight section--soft" id="contact">
  <div class="container consultation">
    <div>
      <span class="eyebrow">Get Started</span>
      <h2><?= e($consultation['heading']) ?></h2>
      <p class="consultation__body"><?= e($consultation['body']) ?></p>
    </div>
    <?php \App\Core\View::component('project-form', ['projectTypes' => $projectTypes]); ?>
  </div>
</section>
