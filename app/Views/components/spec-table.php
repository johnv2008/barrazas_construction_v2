<?php
/**
 * The spec table — the Datum language applied to content.
 *
 * Hairlines, burgundy condensed keys, values at medium weight so the fact
 * leads and the label recedes. Same treatment proven in homepage Chapter
 * 05, generalised here so service and project pages share one component
 * rather than three near-identical ones.
 *
 * Rows arrive pre-filtered: the controller omits any row whose value is
 * unknown rather than printing "Varies" or "TBD". An empty spec table
 * renders nothing at all.
 *
 * Params:
 *   $rows  — list of ['label' => string, 'value' => string]
 *   $tight — optional bool, reduces the label column for narrow columns
 */
$rows = $rows ?? [];

if ($rows === []) {
    return;
}
?>
<dl class="spec<?= !empty($tight) ? ' spec--tight' : '' ?>">
  <?php foreach ($rows as $row): ?>
    <div>
      <dt><?= e($row['label']) ?></dt>
      <dd><?= e($row['value']) ?></dd>
    </div>
  <?php endforeach; ?>
</dl>
