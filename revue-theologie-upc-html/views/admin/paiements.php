<?php
$paiements = $paiements ?? [];
$base = $base ?? '';

$statutLabels = ['en_attente' => 'En attente', 'valide' => 'Validé', 'refuse' => 'Refusé'];

function adminFormatDate(?string $d): string {
    if (!$d) return '—';
    $t = strtotime($d);
    return $t ? date('d/m/Y H:i', $t) : $d;
}
?>
<div class="dashboard-header">
  <h1>Paiements</h1>
  <p>Liste des paiements et validation.</p>
</div>
<div class="dashboard-card">
  <div class="overflow-auto">
    <table class="dashboard-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Utilisateur</th>
          <th>Montant</th>
          <th>Moyen</th>
          <th>Statut</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($paiements)): ?>
          <tr><td colspan="6" class="text-muted">Aucun paiement.</td></tr>
        <?php else: ?>
          <?php foreach ($paiements as $p): ?>
            <tr>
              <td><?= (int) ($p['id'] ?? 0) ?></td>
              <td><?= htmlspecialchars(trim(($p['user_prenom'] ?? '') . ' ' . ($p['user_nom'] ?? ''))) ?><br><span class="text-sm text-muted"><?= htmlspecialchars($p['user_email'] ?? '') ?></span></td>
              <td><?= number_format((float) ($p['montant'] ?? 0), 2, ',', ' ') ?> $</td>
              <td><?= htmlspecialchars($p['moyen'] ?? '') ?></td>
              <td><span class="badge <?= ($p['statut'] ?? '') === 'valide' ? 'badge green' : (($p['statut'] ?? '') === 'refuse' ? 'badge-accent' : 'badge-primary') ?>"><?= htmlspecialchars($statutLabels[$p['statut'] ?? ''] ?? $p['statut'] ?? '') ?></span></td>
              <td><?= adminFormatDate($p['date_paiement'] ?? $p['created_at'] ?? null) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
