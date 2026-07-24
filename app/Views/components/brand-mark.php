<?php
/**
 * Reusable brand lockup — the real company logo (see
 * public/assets/images/brand/logo-full.png, sourced from
 * project_files/logo.png with its background made transparent). The
 * emblem already contains the "BARRAZA'S CONSTRUCTION INC." wordmark,
 * so it's used as-is rather than paired with separate text.
 *
 * This component is only ever placed on dark surfaces (site header,
 * mobile nav, footer), so it uses logo-full-dark.png — the same
 * artwork with its black line-art recolored to warm white (burgundy
 * and the white lettering are untouched). The original black-on-white
 * logo-full.png is kept for any future light-background placement.
 *
 * Params: $large (bool, optional) — use the bigger variant (e.g. the
 * mobile navigation drawer, which has more room than the site header).
 */
$large = $large ?? false;
?>
<span class="brand-mark<?= $large ? ' brand-mark--large' : '' ?>">
  <img
    src="<?= e(asset('images/brand/logo-full-dark.png')) ?>"
    alt="Barraza's Construction"
    class="brand-mark__logo"
  >
</span>
