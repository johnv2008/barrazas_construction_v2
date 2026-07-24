<?php
$navItems = [
    ['label' => 'Home', 'href' => base_url('/')],
    ['label' => 'Services', 'href' => '#services'],
    ['label' => 'Projects', 'href' => '#projects'],
    ['label' => 'Process', 'href' => '#process'],
    ['label' => 'About', 'href' => '#about'],
    ['label' => 'Contact', 'href' => '#contact'],
];
?>
<header class="site-header" data-site-header>
  <div class="container site-header__inner">
    <a href="<?= e(base_url('/')) ?>" aria-label="Barraza's Construction — home">
      <?php \App\Core\View::component('brand-mark'); ?>
    </a>

    <nav class="site-nav" aria-label="Primary">
      <ul class="site-nav__list">
        <?php foreach ($navItems as $item): ?>
          <li><a href="<?= e($item['href']) ?>"<?= $item['label'] === 'Home' ? ' aria-current="page"' : '' ?>><?= e($item['label']) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <div class="site-header__actions">
      <a href="#contact" class="btn btn-primary site-header__cta">Schedule a Consultation</a>
      <button type="button" class="nav-toggle" data-nav-toggle aria-label="Open menu" aria-haspopup="true">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M3 6h18M3 12h18M3 18h18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
      </button>
    </div>
  </div>
</header>

<div class="mobile-nav" data-mobile-nav data-open="false" role="dialog" aria-modal="true" aria-label="Site navigation">
  <div class="mobile-nav__header">
    <?php \App\Core\View::component('brand-mark', ['large' => true]); ?>
    <button type="button" class="mobile-nav__close" data-nav-close aria-label="Close menu">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
      </svg>
    </button>
  </div>
  <nav aria-label="Mobile">
    <ul class="mobile-nav__list">
      <?php foreach ($navItems as $item): ?>
        <li>
          <a href="<?= e($item['href']) ?>"><?= e($item['label']) ?></a>
        </li>
      <?php endforeach; ?>
    </ul>
  </nav>
  <div class="mobile-nav__footer">
    <a href="#contact" class="btn btn-primary btn-block">Schedule a Consultation</a>
    <p class="mobile-nav__contact">San Francisco Bay Area, CA</p>
  </div>
</div>
