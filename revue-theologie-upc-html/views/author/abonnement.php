<?php
$abonnements = $abonnements ?? [];
$paiements = $paiements ?? [];
$abonnementActif = $abonnementActif ?? null;
$error = $error ?? null;
$success = $success ?? false;
$successSubscribe = $success === 'subscribe';
$successSubscribePending = $success === 'subscribe_pending';
$successCancel = $success === true;
$successPaymentCancelled = $success === 'payment_cancelled';
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
<?php if ($error): ?>
<p class="text-accent mb-4"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<?php
$hasRefused = false;
foreach ($paiements as $p) {
    if (($p['statut'] ?? '') === 'refuse') {
        $hasRefused = true;
        break;
    }
}
function authorRegionLabel(?string $region, $montant = null): string {
    $map = ['afrique' => 'Afrique', 'europe' => 'Europe', 'amerique' => 'Amérique'];
    if ($region !== null && $region !== '') {
        return $map[strtolower(trim($region))] ?? ucfirst($region);
    }
    if ($montant !== null && $montant !== '') {
        $m = (float) $montant;
        if ($m == 25) return 'Afrique';
        if ($m == 30) return 'Europe';
        if ($m == 35) return 'Amérique';
    }
    return '—';
}
?>
<?php if ($successSubscribe): ?>
<p class="mb-4" style="color: var(--primary);"><?= htmlspecialchars(__('author.subscribe_success')) ?></p>
<?php elseif ($successSubscribePending): ?>
<p class="mb-4" style="color: var(--primary);"><?= htmlspecialchars(__('author.subscribe_pending_success')) ?></p>
<?php elseif ($successCancel): ?>
<p class="mb-4" style="color: var(--primary);"><?= htmlspecialchars(__('author.cancel_subscription_success')) ?></p>
<?php elseif ($successPaymentCancelled): ?>
<p class="mb-4" style="color: var(--primary);"><?= htmlspecialchars(function_exists('__') ? __('author.cancel_payment_success') : 'Paiement annulé.') ?></p>
<?php elseif ($hasRefused && !$abonnementActif): ?>
<p class="mb-4 alert" style="background: #fef2f2; color: #991b1b; padding: 0.75rem 1rem; border-radius: 6px;">
  <?= htmlspecialchars(function_exists('__') ? __('author.subscription_refused_message') : 'Votre demande d\'abonnement a été refusée. Vous pouvez soumettre une nouvelle demande en cliquant sur S\'abonner.') ?>
  <a href="<?= $base ?>/author/s-abonner" class="btn btn-sm btn-outline" style="margin-left: 0.5rem;"><?= htmlspecialchars(__('author.subscribe_btn')) ?></a>
</p>
<?php endif; ?>
<div class="dashboard-card">
  <h2><?= htmlspecialchars(__('author.current_status')) ?></h2>
  <?php if ($abonnementActif): ?>
    <p><?= __('author.subscription_active_until') ?> <?= authorFormatDate($abonnementActif['date_fin'] ?? null) ?>.</p>
    <form method="post" action="<?= $base ?>/author/abonnement/cancel" class="mt-4 mb-0" onsubmit="return confirm(<?= json_encode(__('author.cancel_subscription_confirm')) ?>);">
      <?= csrf_field() ?>
      <input type="hidden" name="abonnement_id" value="<?= (int)($abonnementActif['id'] ?? 0) ?>">
      <button type="submit" class="btn btn-outline btn-accent"><?= htmlspecialchars(__('author.cancel_subscription')) ?></button>
    </form>
  <?php else: ?>
    <p><?= htmlspecialchars(__('author.no_subscription')) ?></p>
    <p class="mt-3"><a href="<?= $base ?>/author/s-abonner" class="btn btn-primary"><?= htmlspecialchars(__('author.subscribe_btn')) ?></a></p>
  <?php endif; ?>
  <p class="mt-4 mb-0"><a href="<?= $base ?>/author" class="btn btn-outline-primary"><?= htmlspecialchars(__('author.back_dashboard')) ?></a></p>
</div>
<div class="dashboard-card">
  <h2><?= htmlspecialchars(__('author.payment_history')) ?></h2>
  <table class="dashboard-table">
    <thead>
      <tr><th><?= htmlspecialchars(__('author.th_date')) ?></th><th><?= htmlspecialchars(__('author.th_amount')) ?></th><th><?= htmlspecialchars(function_exists('__') ? __('author.th_region') : 'Région') ?></th><th><?= htmlspecialchars(__('author.th_method')) ?></th><th><?= htmlspecialchars(__('author.th_status')) ?></th><th><?= htmlspecialchars(__('author.th_actions')) ?></th></tr>
    </thead>
    <tbody>
      <?php if (empty($paiements)): ?>
        <tr><td colspan="6" class="text-muted"><?= htmlspecialchars(__('author.no_payments')) ?></td></tr>
      <?php else: ?>
        <?php foreach ($paiements as $p): ?>
        <?php $pStatut = $p['statut'] ?? 'en_attente'; $badgeClass = $pStatut === 'valide' ? 'badge green' : ($pStatut === 'refuse' ? 'badge-accent' : 'badge-primary'); $statutLabel = $pStatut === 'en_attente' ? (function_exists('__') ? __('admin.status_pending') : 'En attente') : ($pStatut === 'refuse' ? (function_exists('__') ? __('admin.status_refused') : 'Refusé') : (function_exists('__') ? __('admin.status_valid') : 'Valide')); ?>
        <tr>
          <td><?= authorFormatDate($p['date_paiement'] ?? $p['created_at'] ?? null) ?></td>
          <td><?= htmlspecialchars($p['montant'] ?? '—') ?></td>
          <td><?= htmlspecialchars(authorRegionLabel($p['region'] ?? null, $p['montant'] ?? null)) ?></td>
          <td><?= htmlspecialchars($p['moyen'] ?? '—') ?></td>
          <td><span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($statutLabel) ?></span></td>
          <td>
            <?php if (($pStatut ?? '') === 'valide'): ?>
              <a href="<?= $base ?>/author/paiement/receipt/<?= (int)($p['id'] ?? 0) ?>" class="btn btn-sm btn-outline" target="_blank" rel="noopener"><?= htmlspecialchars(function_exists('__') ? __('author.receipt') : 'Reçu') ?></a>
            <?php elseif (($pStatut ?? '') === 'en_attente'): ?>
              <form method="post" action="<?= $base ?>/author/paiement/<?= (int)($p['id'] ?? 0) ?>/cancel" class="inline" onsubmit="return confirm(<?= json_encode(function_exists('__') ? __('author.cancel_payment_confirm') : 'Annuler ce paiement ?') ?>);">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-sm btn-outline btn-accent"><?= htmlspecialchars(function_exists('__') ? __('author.cancel_payment') : 'Annuler') ?></button>
              </form>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
