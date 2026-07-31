<?php
/**
 * Favicon and app icons. Included by every layout so the mark is the same
 * on the public site, the admin, and error pages.
 *
 * THE MARK
 * --------
 * The company logo is a circular badge carrying a wordmark, a house and
 * crossed tools. At 16px none of that survives — it renders as a maroon
 * smudge. So the favicon is not the logo shrunk; it is the one element of
 * the logo that still reads at that size: the gable roofline, in the brand
 * burgundy on warm white.
 *
 * The SVG is the primary icon and covers every modern browser at every
 * size from one 486-byte file. The PNGs exist for Safari and for older
 * engines that ignore SVG favicons. All are generated from a single set of
 * coordinates, so they are the same mark rather than three drawings that
 * happen to resemble each other.
 *
 * No .ico. It is only needed for IE and for a bare /favicon.ico request,
 * and every browser that matters honours these declarations instead.
 */
?>
<link rel="icon" href="<?= e(asset('images/brand/favicon.svg')) ?>" type="image/svg+xml">
<link rel="icon" href="<?= e(asset('images/brand/favicon-32.png')) ?>" sizes="32x32" type="image/png">
<link rel="icon" href="<?= e(asset('images/brand/favicon-16.png')) ?>" sizes="16x16" type="image/png">
<link rel="apple-touch-icon" href="<?= e(asset('images/brand/apple-touch-icon.png')) ?>">
<link rel="manifest" href="<?= e(asset('site.webmanifest')) ?>">
<meta name="theme-color" content="#800020">
