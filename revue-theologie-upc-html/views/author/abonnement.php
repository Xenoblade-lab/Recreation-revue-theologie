<?php
$abonnements = $abonnements ?? [];
$paiements = $paiements ?? [];
$abonnementActif = $abonnementActif ?? null;
$base = $base ?? '';

function authorFormatDate(?string $d): string {
    if (!$d) return '—';
    $t = strtotime($d);
    return $t ? date('j M. Y', $t) : $d;
}
?>
<div class="dashboard-header">
  <h1><?= htmlspecialchars(__('author.my_subscription')) ?></h1>
  <p><?= htmlspecialchars(__('author.subscription_intro')) ?></p>
</div>
<div class="dashboard-card">
  <h2><?= htmlspecialchars(__('author.current_status')) ?></h2>
  <?php if ($abonnementActif): ?>
    <p><?= __('author.subscription_active_until') ?> <?= authorFormatDate($abonnementActif['date_fin'] ?? null) ?>.</p>
  <?php else: ?>
    <p><?= htmlspecialchars(__('author.no_subscription')) ?></p>
  <?php endif; ?>
  <p class="mt-4 mb-0"><a href="<?= $base ?>/author" class="btn btn-outline-primary"><?= htmlspecialchars(__('author.back_dashboard')) ?></a></p>
</div>
<div class="dashboard-card">
  <h2><?= htmlspecialchars(__('author.payment_history')) ?></h2>
  <table class="dashboard-table">
    <thead>
      <tr><th><?= htmlspecialchars(__('author.th_date')) ?></th><th><?= htmlspecialchars(__('author.th_amount')) ?></th><th><?= htmlspecialchars(__('author.th_method')) ?></th><th><?= htmlspecialchars(__('author.th_status')) ?></th></tr>
    </thead>
    <tbody>
      <?php if (empty($paiements)): ?>
        <tr><td colspan="4" class="text-muted"><?= htmlspecialchars(__('author.no_payments')) ?></td></tr>
      <?php else: ?>
        <?php foreach ($paiements as $p): ?>
        <tr>
          <td><?= authorFormatDate($p['date_paiement'] ?? $p['created_at'] ?? null) ?></td>
          <td><?= htmlspecialchars($p['montant'] ?? '—') ?></td>
          <td><?= htmlspecialchars($p['moyen'] ?? '—') ?></td>
          <td><span class="badge" style="background: rgba(34,197,94,0.1); color: #059669;"><?= htmlspecialchars($p['statut'] ?? 'Valide') ?></span></td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
