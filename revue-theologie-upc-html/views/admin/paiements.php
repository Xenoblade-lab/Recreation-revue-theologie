<?php
$paiements = $paiements ?? [];
$error = $error ?? null;
$success = $success ?? null;
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
<?php if ($error): ?>
<div class="alert alert-error mb-4"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if (!empty($success) && $success === 'paiement_refuse'): ?>
<div class="alert mb-4" style="background: #d1fae5; color: #065f46; padding: 0.75rem 1rem; border-radius: 6px;"><?= htmlspecialchars(function_exists('__') ? __('admin.payment_refused_success') : 'Paiement refusé. L\'utilisateur a été notifié.') ?></div>
<?php endif; ?>
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
          <th><?= htmlspecialchars(__('admin.actions')) ?></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($paiements)): ?>
          <tr><td colspan="7" class="text-muted"><?= htmlspecialchars(__('admin.no_paiements')) ?></td></tr>
        <?php else: ?>
          <?php foreach ($paiements as $p): ?>
            <?php $st = $p['statut'] ?? 'en_attente'; $pid = (int)($p['id'] ?? 0); ?>
            <tr>
              <td><?= $pid ?></td>
              <td><?= htmlspecialchars(trim(($p['user_prenom'] ?? '') . ' ' . ($p['user_nom'] ?? ''))) ?><br><span class="text-sm text-muted"><?= htmlspecialchars($p['user_email'] ?? '') ?></span></td>
              <td><?= number_format((float) ($p['montant'] ?? 0), 2, ',', ' ') ?> $</td>
              <td><?= htmlspecialchars($p['moyen'] ?? '') ?></td>
              <td><span class="badge <?= $st === 'valide' ? 'badge green' : ($st === 'refuse' ? 'badge-accent' : 'badge-primary') ?>"><?= htmlspecialchars(adminPaymentStatusLabel($st)) ?></span></td>
              <td><?= adminFormatDate($p['date_paiement'] ?? $p['created_at'] ?? null) ?></td>
              <td>
                <?php if ($st === 'en_attente'): ?>
                  <?php
                  $baseUrl = rtrim($base ?? '', '/');
                  if ($baseUrl === '' && defined('BASE_URL')) {
                      $baseUrl = rtrim(BASE_URL, '/');
                  }
                  $urlStatut = $baseUrl . '/admin/paiement/' . $pid . '/statut';
                  ?>
                  <form method="post" action="<?= htmlspecialchars($urlStatut) ?>" style="display: inline; margin-right: 0.25rem;">
                    <?= csrf_field() ?>
                    <input type="hidden" name="statut" value="valide">
                    <button type="submit" class="btn btn-sm btn-outline"><?= htmlspecialchars(__('admin.validate')) ?></button>
                  </form>
                  <form method="post" action="<?= htmlspecialchars($urlStatut) ?>" style="display: inline;">
                    <?= csrf_field() ?>
                    <input type="hidden" name="statut" value="refuse">
                    <button type="submit" class="btn btn-sm btn-outline btn-accent"><?= htmlspecialchars(__('admin.refuse')) ?></button>
                  </form>
                <?php else: ?>
                  —
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
