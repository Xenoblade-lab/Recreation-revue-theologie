<?php
$evaluations = $evaluations ?? [];
$base = $base ?? '';

function reviewerStatutBadge(string $statut): string {
    $map = [
        'en_attente' => ['label' => 'En attente', 'class' => 'badge-primary'],
        'en_cours'   => ['label' => 'En cours', 'class' => 'badge-gold'],
        'termine'    => ['label' => 'Terminé', 'class' => 'badge green'],
        'annule'     => ['label' => 'Annulé', 'class' => 'badge accent'],
    ];
    $c = $map[$statut] ?? ['label' => $statut, 'class' => 'badge'];
    return '<span class="badge ' . $c['class'] . '">' . htmlspecialchars($c['label']) . '</span>';
}
function reviewerFormatDate(?string $d): string {
    if (!$d) return '—';
    $t = strtotime($d);
    return $t ? date('j M. Y', $t) : $d;
}
function reviewerRecommendationLabel(?string $r): string {
    if ($r === null || $r === '') return '—';
    $map = [
        'accepte'                    => 'Accepté',
        'accepte_avec_modifications' => 'Accepté avec modifications',
        'revision_majeure'           => 'Révisions majeures',
        'rejete'                    => 'Rejeté',
    ];
    return $map[$r] ?? $r;
}
?>
<div class="dashboard-header">
  <h1>Historique des évaluations</h1>
  <p>Toutes vos évaluations (en cours et terminées).</p>
</div>
<div class="dashboard-card">
  <table class="dashboard-table">
    <thead>
      <tr>
        <th>Titre</th>
        <th>Date assignation</th>
        <th>Statut</th>
        <th>Recommandation</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($evaluations)): ?>
        <tr><td colspan="5" class="text-muted">Aucune évaluation.</td></tr>
      <?php else: ?>
        <?php foreach ($evaluations as $e): ?>
        <?php $statut = $e['statut'] ?? 'en_attente'; ?>
        <tr>
          <td><?= htmlspecialchars($e['article_titre'] ?? '') ?></td>
          <td><?= reviewerFormatDate($e['date_assignation'] ?? null) ?></td>
          <td><?= reviewerStatutBadge($statut) ?></td>
          <td><?= htmlspecialchars(reviewerRecommendationLabel($e['recommendation'] ?? null)) ?></td>
          <td>
            <?php if (in_array($statut, ['en_attente', 'en_cours'], true)): ?>
              <a href="<?= $base ?>/reviewer/evaluation/<?= (int) $e['id'] ?>" class="btn btn-sm btn-outline">Évaluer</a>
            <?php else: ?>
              <a href="<?= $base ?>/article/<?= (int) ($e['article_id'] ?? 0) ?>" class="btn btn-sm btn-outline">Voir l'article</a>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
