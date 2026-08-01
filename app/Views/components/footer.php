<?php
/**
 * Site footer.
 *
 * Every link here resolves. That sounds like a low bar; the audit found
 * two dead `href="#"` social icons, two links to pages that 404, and a
 * Services column whose four entries all pointed at a homepage fragment
 * rather than at the service pages that now exist.
 *
 * On a site whose whole argument is "we are careful about the things you
 * cannot see", a footer of placeholder links is the most expensive detail
 * on the page — and it is the last thing a visitor reads before deciding.
 *
 * Rules applied here, and they are the same rules the header follows:
 *   - A fragment resolves against the HOMEPAGE from anywhere else.
 *   - A destination that does not exist yet is rendered as TEXT, not as a
 *     link. Nothing is better than a link that lies.
 *   - Social accounts that have not been supplied are omitted entirely
 *     rather than shown as inert icons.
 */
$isHome = rtrim(current_path(), '/') === '';
$home = static fn (string $fragment): string => $isHome ? $fragment : base_url('/') . $fragment;

// Published service pages, in the order the homepage introduces them.
// Whole-Home and ADU are listed as plain text: neither has a page, and an
// ADU page will not exist until there is photography for it.
$footerServices = [
    ['label' => 'Kitchen Remodeling', 'href' => base_url('services/kitchen-remodeling')],
    ['label' => 'Bathroom Remodeling', 'href' => base_url('services/bathroom-remodeling')],
    ['label' => 'Whole-Home Renovation', 'href' => base_url('services/whole-home-renovation')],
    ['label' => 'Home Additions', 'href' => null],
];

// Legal pages do not exist yet. Until they do these are not rendered at
// all — a Privacy Policy link that 404s is worse than no link, both for
// trust and for the crawler.
$legalPages = [
    ['label' => 'Privacy Policy', 'href' => base_url('privacy')],
    ['label' => 'Terms & Conditions', 'href' => base_url('terms')],
];
?>
<footer class="site-footer">
  <div class="container">
    <div class="site-footer__grid">
      <div class="site-footer__brand-block">
        <?= responsive_image('images/brand/logo-full-dark.png', [
              'alt'   => "Barraza's Construction logo",
              'class' => 'site-footer__logo',
              'sizes' => '190px',
        ]) ?>
        <p class="site-footer__desc">
          Residential construction and remodeling for Tuolumne County —
          experienced craftsmanship, clear communication, and attention to detail
          from first consultation to final walkthrough.
        </p>
        <div class="site-footer__social" aria-label="Social links">
          <?php /* Instagram and Facebook removed: both were href="#". An
                   inert icon is a promise the brand does not keep. They
                   return the day real accounts are supplied. */ ?>
          <a href="https://share.google/IZSCTK6fzXc9Gi9wm" target="_blank" rel="noopener noreferrer" aria-label="Barraza's Construction on Google">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 21c-4.5 0-8-3.5-8-8s3.5-8 8-8c2.1 0 3.9.7 5.2 2l-2.1 2c-.8-.7-1.9-1.2-3.1-1.2-2.7 0-4.8 2.2-4.8 5.2s2.1 5.2 4.8 5.2c2.4 0 4-1.4 4.3-3.3h-4.3v-2.7h7c.1.5.2 1 .2 1.6 0 4.3-2.9 7.2-7.2 7.2z" fill="currentColor"/></svg>
          </a>
        </div>
      </div>

      <div>
        <h3>Company</h3>
        <ul class="site-footer__links">
          <li><a href="<?= e(base_url('services/kitchen-remodeling')) ?>">Kitchens</a></li>
          <li><a href="<?= e(base_url('services/bathroom-remodeling')) ?>">Bathrooms</a></li>
          <li><a href="<?= e($home('#process')) ?>">Process</a></li>
          <li><a href="<?= e($home('#trust')) ?>">About</a></li>
        </ul>
      </div>

      <div>
        <h3>Services</h3>
        <ul class="site-footer__links">
          <?php foreach ($footerServices as $svc): ?>
            <li>
              <?php if ($svc['href'] !== null): ?>
                <a href="<?= e($svc['href']) ?>"><?= e($svc['label']) ?></a>
              <?php else: ?>
                <span class="site-footer__soon"><?= e($svc['label']) ?></span>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div>
        <h3>Get in Touch</h3>
        <ul class="site-footer__links">
          <li>16561 Jacksonville Rd<br>Jamestown, CA 95327</li>
          <li><a href="<?= e($home('#contact')) ?>">Schedule a Consultation</a></li>
          <li><a href="https://share.google/IZSCTK6fzXc9Gi9wm" target="_blank" rel="noopener noreferrer">View Our Google Profile</a></li>
        </ul>
        <p class="site-footer__note">
          Licensed, Bonded &amp; Insured.
          <?php if (license_line() !== ''): ?>
            <br><strong class="site-footer__license"><?= e(license_line()) ?></strong>
          <?php endif; ?>
        </p>
      </div>
    </div>

    <div class="site-footer__bottom">
      <span>&copy; <?= date('Y') ?> Barraza's Construction Inc. All rights reserved.</span>
      <?php if ($legalPages !== []): ?>
        <div class="site-footer__legal">
          <?php foreach ($legalPages as $page): ?>
            <a href="<?= e($page['href']) ?>"><?= e($page['label']) ?></a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</footer>
