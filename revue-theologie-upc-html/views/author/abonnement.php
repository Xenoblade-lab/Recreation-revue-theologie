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
  <h1>Mon abonnement</h1>
  <p>Gérer votre abonnement auteur et consulter l'historique des paiements.</p>
</div>
<div class="dashboard-card">
  <h2>Statut actuel</h2>
  <?php if ($abonnementActif): ?>
    <p><strong>Abonnement actif</strong> jusqu'au <?= authorFormatDate($abonnementActif['date_fin'] ?? null) ?>.</p>
  <?php else: ?>
    <p>Vous n'avez pas d'abonnement actif.</p>
  <?php endif; ?>
  <p class="mt-4 mb-0"><a href="<?= $base ?>/author" class="btn btn-outline-primary">Retour au tableau de bord</a></p>
</div>
<div class="dashboard-card">
  <h2>Historique des paiements</h2>
  <table class="dashboard-table">
    <thead>
      <tr><th>Date</th><th>Montant</th><th>Moyen</th><th>Statut</th></tr>
    </thead>
    <tbody>
      <?php if (empty($paiements)): ?>
        <tr><td colspan="4" class="text-muted">Aucun paiement enregistré.</td></tr>
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
