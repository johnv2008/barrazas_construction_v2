<?php
/**
 * Primary navigation.
 *
 * WHY THESE ARE NOT ALL FRAGMENTS ANY MORE
 * ----------------------------------------
 * The nav used to be six homepage fragments. That was correct when the
 * site was one page and silently wrong the moment it was not:
 *
 *   - On a service page `#services` and `#about` do not exist, so the
 *     links did nothing at all.
 *   - Worse, `#projects` and `#process` DO exist on service pages, so
 *     they scrolled to that service's own related-work strip. A dead link
 *     is a broken promise; a link that quietly goes somewhere plausible
 *     but wrong is a broken mental model. A homeowner who clicked
 *     "Projects" and landed on three cards concluded the portfolio was
 *     three projects.
 *
 * Fragment links are therefore resolved against the homepage from
 * anywhere else, so "Process" always means the homepage's process
 * chapter no matter where it is clicked.
 *
 * `services` are real pages now and are listed as such. Entries whose
 * destination does not exist yet are simply absent rather than pointing
 * at a fragment that happens to resolve — see the Projects note below.
 */
$isHome = rtrim(current_path(), '/') === '';

// A fragment on the homepage; the homepage + fragment everywhere else.
$home = static fn (string $fragment): string => $isHome
    ? $fragment
    : base_url('/') . $fragment;

$navItems = [
    ['label' => 'Home', 'href' => base_url('/'), 'match' => ''],
    ['label' => 'Kitchens', 'href' => base_url('services/kitchen-remodeling'), 'match' => 'services/kitchen-remodeling'],
    ['label' => 'Bathrooms', 'href' => base_url('services/bathroom-remodeling'), 'match' => 'services/bathroom-remodeling'],
    ['label' => 'Whole Home', 'href' => base_url('services/whole-home-renovation'), 'match' => 'services/whole-home-renovation'],
    // "Projects" is deliberately omitted until /projects exists. It used to
    // point at a fragment that resolved to a three-card strip, which
    // actively misinformed. Nothing is better than that.
    ['label' => 'Process', 'href' => $home('#process'), 'match' => null],
    ['label' => 'About', 'href' => $home('#trust'), 'match' => null],
    ['label' => 'Contact', 'href' => $home('#contact'), 'match' => null],
];

$currentPath = trim(current_path(), '/');
?>
<header class="site-header" data-site-header>
  <div class="container site-header__inner">
    <a href="<?= e(base_url('/')) ?>" aria-label="Barraza's Construction — home">
      <?php \App\Core\View::component('brand-mark'); ?>
    </a>

    <nav class="site-nav" aria-label="Primary">
      <ul class="site-nav__list">
        <?php foreach ($navItems as $item): ?>
          <?php
            // aria-current marks the page you are ON. It used to be
            // hardcoded to "Home", so every service and project page
            // announced itself as the homepage to a screen reader.
            $isCurrent = $item['match'] !== null && $item['match'] === $currentPath;
          ?>
          <li><a href="<?= e($item['href']) ?>"<?= $isCurrent ? ' aria-current="page"' : '' ?>><?= e($item['label']) ?></a></li>
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
    <p class="mobile-nav__contact">16561 Jacksonville Rd, Jamestown, CA 95327</p>
  </div>
</div>
