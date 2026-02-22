<?php
$notifications = $notifications ?? [];
$base = $base ?? '';

function authorFormatDate(?string $d): string {
    if (!$d) return '—';
    $t = strtotime($d);
    return $t ? date('j F Y', $t) : $d;
}
?>
<div class="dashboard-header">
  <h1>Notifications</h1>
  <p>Historique des notifications liées à vos articles.</p>
</div>
<div class="dashboard-card">
  <?php if (empty($notifications)): ?>
  <p class="text-muted">Aucune notification.</p>
  <?php else: ?>
  <ul style="list-style: none; padding: 0; margin: 0;">
    <?php foreach ($notifications as $n): ?>
    <?php
      $data = [];
      if (!empty($n['data'])) {
        $decoded = json_decode($n['data'], true);
        $data = is_array($decoded) ? $decoded : [];
      }
      $message = $data['message'] ?? $n['type'] ?? 'Notification';
      $link = $data['link'] ?? $data['url'] ?? null;
    ?>
    <li style="padding: 1rem; border-bottom: 1px solid var(--border);">
      <p class="mb-0"><strong><?= htmlspecialchars($message) ?></strong></p>
      <p class="text-muted text-sm mt-1 mb-0">
        <?= authorFormatDate($n['created_at'] ?? null) ?>
        <?php if ($link): ?>
          · <a href="<?= $base ?>/<?= htmlspecialchars(ltrim($link, '/')) ?>" class="text-primary">Voir</a>
        <?php endif; ?>
      </p>
    </li>
    <?php endforeach; ?>
  </ul>
  <?php endif; ?>
</div>
