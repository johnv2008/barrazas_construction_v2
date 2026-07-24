<?php
/** @var array<string, array<int, string>> $flashMessages */
$flashMessages = flashes();
?>
<?php foreach (($flashMessages['success'] ?? []) as $message): ?>
  <div class="alert alert-success" role="status"><?= e($message) ?></div>
<?php endforeach; ?>
<?php foreach (($flashMessages['error'] ?? []) as $message): ?>
  <div class="alert alert-error" role="alert"><?= e($message) ?></div>
<?php endforeach; ?>
