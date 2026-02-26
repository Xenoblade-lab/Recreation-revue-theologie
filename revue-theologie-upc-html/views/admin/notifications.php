<?php
$notifications = $notifications ?? [];
$error = $error ?? null;
$base = $base ?? '';

function adminNotifFormatDate(?string $d): string {
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
    <h1><?= htmlspecialchars(__('admin.notifications')) ?></h1>
    <p><?= htmlspecialchars(__('admin.notifications_intro')) ?></p>
  </div>
  <?php if ($hasUnread): ?>
    <form method="post" action="<?= $base ?>/admin/notifications/read-all" class="mb-0">
      <?= csrf_field() ?>
      <button type="submit" class="btn btn-outline btn-sm"><?= htmlspecialchars(__('admin.mark_all_read')) ?></button>
    </form>
  <?php endif; ?>
</div>
<?php if ($error): ?>
<p class="text-accent mb-4"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<div class="dashboard-card">
  <?php if (empty($notifications)): ?>
  <p class="text-muted"><?= htmlspecialchars(__('admin.no_notifications')) ?></p>
  <?php else: ?>
  <ul style="list-style: none; padding: 0; margin: 0;">
    <?php foreach ($notifications as $n): ?>
    <?php
      $data = [];
      if (!empty($n['data'])) {
        $decoded = json_decode($n['data'], true);
        $data = is_array($decoded) ? $decoded : [];
      }
      $message = $data['message'] ?? $n['type'] ?? __('admin.notifications');
      $link = $data['link'] ?? $data['url'] ?? null;
      $isUnread = empty($n['read_at']);
      $id = $n['id'] ?? '';
    ?>
    <li style="padding: 1rem; border-bottom: 1px solid var(--border);<?= $isUnread ? ' background: rgba(26,51,101,0.04);' : '' ?>">
      <div class="flex flex-wrap items-start justify-between gap-2">
        <div class="flex-1">
          <p class="mb-0"><strong><?= htmlspecialchars($message) ?></strong></p>
          <p class="text-muted text-sm mt-1 mb-0">
            <?= adminNotifFormatDate($n['created_at'] ?? null) ?>
            <?php if ($link): ?>
              <?php $readUrl = (strpos($link, 'http') === 0) ? $link : $base . (strpos($link, '/') === 0 ? $link : '/' . $link); ?>
              · <a href="<?= htmlspecialchars($readUrl) ?>" class="text-primary"><?= htmlspecialchars(__('common.read')) ?></a>
            <?php endif; ?>
          </p>
        </div>
        <?php if ($isUnread && $id !== ''): ?>
          <form method="post" action="<?= $base ?>/admin/notification/<?= htmlspecialchars($id) ?>/read" class="mb-0">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-sm btn-outline"><?= htmlspecialchars(__('admin.mark_read')) ?></button>
          </form>
        <?php endif; ?>
      </div>
    </li>
    <?php endforeach; ?>
  </ul>
  <?php endif; ?>
</div>
