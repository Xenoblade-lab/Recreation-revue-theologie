<?php
$evaluations = $evaluations ?? [];
$base = $base ?? '';

function reviewerFormatDate(?string $d): string {
    if (!$d) return '—';
    $t = strtotime($d);
    return $t ? date('j M. Y', $t) : $d;
}
function reviewerRecommendationLabel(?string $r): string {
    $map = [
        'accepte'                    => 'Accepté',
        'accepte_avec_modifications'  => 'Accepté avec modifications',
        'revision_majeure'           => 'Révisions majeures',
        'rejete'                     => 'Rejeté',
    ];
    return $map[$r] ?? $r ?? '—';
}
?>
<div class="dashboard-header">
  <h1>Évaluations terminées</h1>
  <p>Liste des évaluations que vous avez soumises.</p>
</div>
<div class="dashboard-card">
  <table class="dashboard-table">
    <thead>
      <tr>
        <th>Titre de l'article</th>
        <th>Date de soumission</th>
        <th>Recommandation</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($evaluations)): ?>
        <tr><td colspan="4" class="text-muted">Aucune évaluation terminée.</td></tr>
      <?php else: ?>
        <?php foreach ($evaluations as $e): ?>
        <tr>
          <td><?= htmlspecialchars($e['article_titre'] ?? '') ?></td>
          <td><?= reviewerFormatDate($e['date_soumission'] ?? null) ?></td>
          <td><span class="badge"><?= htmlspecialchars(reviewerRecommendationLabel($e['recommendation'] ?? null)) ?></span></td>
          <td><a href="<?= $base ?>/article/<?= (int) ($e['article_id'] ?? 0) ?>" class="btn btn-sm btn-outline">Voir l'article</a></td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
