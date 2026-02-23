<?php
$volumes = $volumes ?? [];
$revuesByVolume = $revuesByVolume ?? [];
$base = $base ?? '';

function adminFormatDate(?string $d): string {
    if (!$d) return '—';
    $t = strtotime($d);
    return $t ? date('j M. Y', $t) : $d;
}
?>
<div class="dashboard-header">
  <h1><?= htmlspecialchars(__('admin.volumes')) ?></h1>
  <p><?= htmlspecialchars(__('admin.volumes_intro')) ?></p>
</div>
<div class="dashboard-card">
  <?php if (empty($volumes)): ?>
    <p class="text-muted"><?= htmlspecialchars(__('admin.no_volume')) ?></p>
  <?php else: ?>
    <?php foreach ($volumes as $vol): ?>
      <div class="mb-4" style="margin-bottom: 1.5rem;">
        <h2 class="h3 mb-2"><?= htmlspecialchars(__('common.volume')) ?> <?= (int) ($vol['id']) ?> — <?= htmlspecialchars($vol['annee'] ?? '') ?> <?= htmlspecialchars($vol['numero_volume'] ?? '') ?></h2>
        <?php if (!empty($vol['description'])): ?>
          <p class="text-sm text-muted mb-2"><?= nl2br(htmlspecialchars(mb_substr($vol['description'], 0, 300))) ?><?= mb_strlen($vol['description']) > 300 ? '…' : '' ?></p>
        <?php endif; ?>
        <?php if (!empty($vol['redacteur_chef'])): ?>
          <p class="text-sm"><strong><?= htmlspecialchars(__('admin.chief_editor')) ?></strong> <?= htmlspecialchars($vol['redacteur_chef']) ?></p>
        <?php endif; ?>
        <?php
        $revues = $revuesByVolume[(int) $vol['id']] ?? [];
        if (!empty($revues)):
        ?>
          <h3 class="h4 mt-3 mb-2"><?= htmlspecialchars(__('admin.numeros')) ?></h3>
          <div class="overflow-auto">
            <table class="dashboard-table">
              <thead>
                <tr>
                  <th><?= htmlspecialchars(__('admin.th_issue')) ?></th>
                  <th><?= htmlspecialchars(__('author.th_title')) ?></th>
                  <th><?= htmlspecialchars(__('admin.th_pub_date')) ?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($revues as $r): ?>
                  <tr>
                    <td><?= htmlspecialchars($r['numero'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['titre'] ?? '') ?></td>
                    <td><?= adminFormatDate($r['date_publication'] ?? null) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <p class="text-sm text-muted"><?= htmlspecialchars(__('admin.no_issue_volume')) ?></p>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
