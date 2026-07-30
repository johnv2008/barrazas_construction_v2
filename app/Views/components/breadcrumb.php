<?php
/**
 * Breadcrumb, set in the 11px annotation register.
 *
 * Rendered as a real <nav> landmark with an ordered list, because that is
 * what it is — the Datum rail beside it is aria-hidden and decorative, so
 * this carries the actual navigational semantics.
 *
 * A crumb with href === null renders as text, not a dead link. That is how
 * levels whose index page does not exist yet are handled: a breadcrumb
 * link to a 404 is worse than a breadcrumb without a link.
 *
 * Params: $breadcrumb — list of ['label' => string, 'href' => ?string]
 */
$breadcrumb = $breadcrumb ?? [];

if ($breadcrumb === []) {
    return;
}
?>
<nav class="crumbs" aria-label="Breadcrumb">
  <ol>
    <?php foreach ($breadcrumb as $i => $crumb): ?>
      <li>
        <?php if (!empty($crumb['href'])): ?>
          <a href="<?= e($crumb['href']) ?>"><?= e($crumb['label']) ?></a>
        <?php else: ?>
          <span<?= $i === count($breadcrumb) - 1 ? ' aria-current="page"' : '' ?>><?= e($crumb['label']) ?></span>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ol>
</nav>
