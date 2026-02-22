<?php
$assignations = $assignations ?? [];
$enAttente = (int) ($enAttente ?? 0);
$enCours = (int) ($enCours ?? 0);
$terminees = (int) ($terminees ?? 0);
$tauxCompletion = (int) ($tauxCompletion ?? 0);
$base = $base ?? '';

function reviewerStatutBadge(string $statut): string {
    $map = [
        'en_attente' => ['label' => 'En attente', 'class' => 'badge-primary'],
        'en_cours'   => ['label' => 'En cours', 'class' => 'badge-gold'],
        'termine'    => ['label' => 'Terminé', 'class' => 'badge green'],
        'annule'     => ['label' => 'Annulé', 'class' => 'badge accent'],
    ];
    $c = $map[$statut] ?? ['label' => $statut, 'class' => 'badge'];
    return '<span class="badge ' . htmlspecialchars($c['class']) . '">' . htmlspecialchars($c['label']) . '</span>';
}
function reviewerFormatDate(?string $d): string {
    if (!$d) return '—';
    $t = strtotime($d);
    return $t ? date('j M. Y', $t) : $d;
}
function reviewerJoursRestants(?string $dateEcheance): ?int {
    if (!$dateEcheance) return null;
    $end = strtotime($dateEcheance . ' 23:59:59');
    $now = time();
    if ($end <= $now) return 0;
    return (int) ceil(($end - $now) / 86400);
}
?>
<div class="dashboard-header">
  <h1>Tableau de bord évaluateur</h1>
  <p>Articles assignés et évaluations en cours.</p>
</div>
<div class="dashboard-stats">
  <div class="stat-card">
    <div class="stat-icon primary"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#file-text"/></svg></div>
    <div>
      <div class="stat-value"><?= $enAttente ?></div>
      <div class="stat-label">En attente</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon gold"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#clock"/></svg></div>
    <div>
      <div class="stat-value"><?= $enCours ?></div>
      <div class="stat-label">En cours</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon green"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#check"/></svg></div>
    <div>
      <div class="stat-value"><?= $terminees ?></div>
      <div class="stat-label">Terminées</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon accent"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#clipboard-check"/></svg></div>
    <div>
      <div class="stat-value"><?= $tauxCompletion ?> %</div>
      <div class="stat-label">Taux de complétion</div>
    </div>
  </div>
</div>
<div class="dashboard-card">
  <h2>Articles assignés à évaluer</h2>
  <p class="text-muted text-sm mb-4">Cliquez sur « Évaluer » pour accéder au formulaire d'évaluation.</p>
  <div class="overflow-auto">
    <table class="dashboard-table">
      <thead>
        <tr>
          <th>Titre</th>
          <th>Date d'assignation</th>
          <th>Délai restant</th>
          <th>Statut</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($assignations)): ?>
          <tr><td colspan="5" class="text-muted">Aucune évaluation assignée.</td></tr>
        <?php else: ?>
          <?php foreach ($assignations as $e): ?>
          <?php
            $statut = $e['statut'] ?? 'en_attente';
            $peutEvaluer = in_array($statut, ['en_attente', 'en_cours'], true);
            $jours = reviewerJoursRestants($e['date_echeance'] ?? null);
          ?>
          <tr>
            <td><?= htmlspecialchars($e['article_titre'] ?? '') ?></td>
            <td><?= reviewerFormatDate($e['date_assignation'] ?? null) ?></td>
            <td><?= $jours !== null ? $jours . ' jour(s)' : '—' ?></td>
            <td><?= reviewerStatutBadge($statut) ?></td>
            <td>
              <?php if ($peutEvaluer): ?>
                <a href="<?= $base ?>/reviewer/evaluation/<?= (int) $e['id'] ?>" class="btn btn-sm btn-accent"><?= $statut === 'en_cours' ? 'Reprendre' : 'Évaluer' ?></a>
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
  <p class="text-sm text-muted mt-4 mb-0"><a href="<?= $base ?>/reviewer/terminees" class="text-primary">Évaluations terminées</a> · <a href="<?= $base ?>/reviewer/historique" class="text-primary">Historique complet</a></p>
</div>
<div class="dashboard-card">
  <h2>Rappel du processus d'évaluation</h2>
  <ul class="text-sm text-muted" style="margin: 0; padding-left: 1.25rem;">
    <li>Téléchargez et lisez le manuscrit.</li>
    <li>Remplissez le formulaire : recommandation, commentaires pour l'auteur, commentaires pour l'éditeur, notes.</li>
    <li>Vous pouvez sauvegarder un brouillon et reprendre plus tard.</li>
    <li>Une fois soumise, l'évaluation est définitive.</li>
  </ul>
</div>
