<?php
$paiements = $paiements ?? [];
$base = $base ?? '';

function adminPaymentStatusLabel(string $statut): string {
    $map = [
        'en_attente' => 'admin.status_pending',
        'valide' => 'admin.status_valid',
        'refuse' => 'admin.status_refused',
    ];
    return function_exists('__') ? __($map[$statut] ?? 'admin.status_pending') : $statut;
}

function adminFormatDate(?string $d): string {
    if (!$d) return '—';
    $t = strtotime($d);
    return $t ? date('d/m/Y H:i', $t) : $d;
}
?>
<div class="dashboard-header">
  <h1><?= htmlspecialchars(__('admin.paiements')) ?></h1>
  <p><?= htmlspecialchars(__('admin.paiements_intro')) ?></p>
</div>
<div class="dashboard-card">
  <div class="overflow-auto">
    <table class="dashboard-table">
      <thead>
        <tr>
          <th><?= htmlspecialchars(__('admin.th_id')) ?></th>
          <th><?= htmlspecialchars(__('admin.th_user')) ?></th>
          <th><?= htmlspecialchars(__('admin.th_amount')) ?></th>
          <th><?= htmlspecialchars(__('admin.th_method')) ?></th>
          <th><?= htmlspecialchars(__('admin.th_status')) ?></th>
          <th><?= htmlspecialchars(__('admin.th_date')) ?></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($paiements)): ?>
          <tr><td colspan="6" class="text-muted"><?= htmlspecialchars(__('admin.no_paiements')) ?></td></tr>
        <?php else: ?>
          <?php foreach ($paiements as $p): ?>
            <?php $st = $p['statut'] ?? 'en_attente'; ?>
            <tr>
              <td><?= (int) ($p['id'] ?? 0) ?></td>
              <td><?= htmlspecialchars(trim(($p['user_prenom'] ?? '') . ' ' . ($p['user_nom'] ?? ''))) ?><br><span class="text-sm text-muted"><?= htmlspecialchars($p['user_email'] ?? '') ?></span></td>
              <td><?= number_format((float) ($p['montant'] ?? 0), 2, ',', ' ') ?> $</td>
              <td><?= htmlspecialchars($p['moyen'] ?? '') ?></td>
              <td><span class="badge <?= $st === 'valide' ? 'badge green' : ($st === 'refuse' ? 'badge-accent' : 'badge-primary') ?>"><?= htmlspecialchars(adminPaymentStatusLabel($st)) ?></span></td>
              <td><?= adminFormatDate($p['date_paiement'] ?? $p['created_at'] ?? null) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
