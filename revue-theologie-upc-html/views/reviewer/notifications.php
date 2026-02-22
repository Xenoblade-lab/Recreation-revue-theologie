<?php
$notifications = $notifications ?? [];
$base = $base ?? '';
$readAllUrl = $base . '/reviewer/notifications/read-all';
$readOneUrl = $base . '/reviewer/notification/';

function reviewerFormatDate(?string $d): string {
    if (!$d) return '—';
    $t = strtotime($d);
    return $t ? date('j F Y', $t) : $d;
}

$hasUnread = false;
foreach ($notifications as $n) {
    if (empty($n['read_at'])) { $hasUnread = true; break; }
}
?>
<div class="dashboard-header flex flex-wrap items-center justify-between gap-4">
  <div>
    <h1>Notifications</h1>
    <p>Notifications liées à vos évaluations.</p>
  </div>
  <?php if ($hasUnread): ?>
    <form method="post" action="<?= $readAllUrl ?>" class="mb-0">
      <button type="submit" class="btn btn-outline btn-sm">Tout marquer comme lu</button>
    </form>
  <?php endif; ?>
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
      $isUnread = empty($n['read_at']);
      $id = $n['id'] ?? '';
    ?>
    <li style="padding: 1rem; border-bottom: 1px solid var(--border);<?= $isUnread ? ' background: rgba(26,51,101,0.04);' : '' ?>">
      <div class="flex flex-wrap items-start justify-between gap-2">
        <div class="flex-1">
          <p class="mb-0"><strong><?= htmlspecialchars($message) ?></strong></p>
          <p class="text-muted text-sm mt-1 mb-0">
            <?= reviewerFormatDate($n['created_at'] ?? null) ?>
            <?php if ($link): ?>
              · <a href="<?= $base ?>/<?= htmlspecialchars(ltrim($link, '/')) ?>" class="text-primary">Voir</a>
            <?php endif; ?>
          </p>
        </div>
        <?php if ($isUnread && $id !== ''): ?>
          <form method="post" action="<?= $readOneUrl . htmlspecialchars($id) ?>/read" class="mb-0">
            <button type="submit" class="btn btn-sm btn-outline">Marquer comme lu</button>
          </form>
        <?php endif; ?>
      </div>
    </li>
    <?php endforeach; ?>
  </ul>
  <?php endif; ?>
</div>
