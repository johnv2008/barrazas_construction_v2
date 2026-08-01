<?php
/**
 * Reusable brand lockup — the real company logo (see
 * public/assets/images/brand/logo-full.png, sourced from
 * project_files/logo.png with its background made transparent). The
 * emblem already contains the "BARRAZA'S CONSTRUCTION INC." wordmark,
 * so it's used as-is rather than paired with separate text.
 *
 * The header and mobile nav are both light surfaces, so this uses the
 * original black-line-art logo-full.png. The footer stays dark and
 * references logo-full-dark.png (white line art) directly rather than
 * through this component.
 *
 * Params: $large (bool, optional) — use the bigger variant (e.g. the
 * mobile navigation drawer, which has more room than the site header).
 */
$large = $large ?? false;
?>
<span class="brand-mark<?= $large ? ' brand-mark--large' : '' ?>">
  <?= responsive_image('images/brand/logo-full.png', [
        'alt'   => "Barraza's Construction",
        'class' => 'brand-mark__logo',
        // Rendered small in the header, a little larger in the mobile
        // drawer. Never near full width, so the 480w WebP is the one
        // that should win at every breakpoint.
        'sizes' => $large ? '220px' : '150px',
        // Above the fold on every page, so it must not be lazy — a
        // lazy-loaded logo is discovered only after layout and the brand
        // mark arrives visibly late. Deliberately NOT fetchpriority high:
        // that would make it compete with the hero photograph, which is
        // the LCP element and should win.
        'loading' => 'eager',
  ]) ?>
</span>
