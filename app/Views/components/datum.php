<?php
/**
 * The Datum — the site's persistent reference line.
 *
 * In architectural drawing a datum is the line every measurement is
 * taken from: the one element on the sheet that does not move. Here it
 * is a hairline held at a fixed position in the viewport, carrying the
 * current chapter number, its label, and chapter-boundary ticks.
 *
 * Its job is structural, not decorative. Eight chapters with radically
 * different compositions risk reading as eight unrelated pages; one
 * absolutely constant element is what licenses that variance. It is
 * also the identity element that survives removing the logo.
 *
 * Purely presentational, so it is aria-hidden — the real navigation
 * landmark is the site header. Below 1024px it collapses to a top
 * hairline indicator (see .datum--rail / .datum--bar in frontend.css).
 *
 * Params: $chapters — list of ['n' => '01', 'id' => 'top', 'label' => 'Arrival']
 */
$chapters = $chapters ?? [];

if ($chapters === []) {
    return;
}
?>
<div class="datum" data-datum aria-hidden="true">
  <div class="datum__rail">
    <span class="datum__progress" data-datum-progress></span>
    <?php foreach ($chapters as $i => $chapter): ?>
      <span class="datum__tick" style="top: <?= e((string) round($i * (100 / max(1, count($chapters) - 1)), 3)) ?>%"></span>
    <?php endforeach; ?>
  </div>
  <div class="datum__marker">
    <span class="datum__dash"></span>
    <span class="datum__num" data-datum-num><?= e($chapters[0]['n']) ?></span>
    <span class="datum__label" data-datum-label><?= e($chapters[0]['label']) ?></span>
  </div>
</div>

<div class="datum-bar" data-datum-bar aria-hidden="true">
  <span class="datum-bar__num" data-datum-num><?= e($chapters[0]['n']) ?></span>
  <span class="datum-bar__label" data-datum-label><?= e($chapters[0]['label']) ?></span>
  <span class="datum-bar__progress" data-datum-progress></span>
</div>
