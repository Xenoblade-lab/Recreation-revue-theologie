<?php
$articles = $articles ?? [];
$abonnement = $abonnement ?? null;
$stats = $stats ?? ['total' => 0, 'soumis' => 0, 'valide' => 0, 'rejete' => 0];
$base = $base ?? '';

function authorStatutBadge(string $statut): string {
    $map = [
        'soumis' => ['label' => 'Soumis', 'class' => 'badge-primary'],
        'valide'  => ['label' => 'Publié', 'class' => 'badge green'],
        'rejete'  => ['label' => 'Rejeté', 'class' => 'badge accent'],
    ];
    $c = $map[$statut] ?? ['label' => $statut, 'class' => 'badge'];
    return '<span class="badge ' . $c['class'] . '">' . htmlspecialchars($c['label']) . '</span>';
}
function authorFormatDate(?string $d): string {
    if (!$d) return '—';
    $t = strtotime($d);
    return $t ? date('j M. Y', $t) : $d;
}
?>
<div class="dashboard-header">
  <h1>Mon tableau de bord</h1>
  <p>Gérer vos soumissions et suivre l'avancement de vos articles.</p>
</div>
<div class="dashboard-stats">
  <div class="stat-card">
    <div class="stat-icon primary"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#file-text"/></svg></div>
    <div>
      <div class="stat-value"><?= (int) $stats['soumis'] ?></div>
      <div class="stat-label">Articles soumis</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon gold"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#clock"/></svg></div>
    <div>
      <div class="stat-value"><?= (int) ($stats['soumis'] ?? 0) ?></div>
      <div class="stat-label">En évaluation</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon green"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#check"/></svg></div>
    <div>
      <div class="stat-value"><?= (int) $stats['valide'] ?></div>
      <div class="stat-label">Publiés</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon accent"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#clipboard-check"/></svg></div>
    <div>
      <div class="stat-value"><?= (int) $stats['rejete'] ?></div>
      <div class="stat-label">Rejetés</div>
    </div>
  </div>
</div>
<div class="dashboard-card">
  <h2>Soumettre un nouvel article</h2>
  <p class="text-muted text-sm mb-4">Remplissez le formulaire pour soumettre un article.</p>
  <p><a href="<?= $base ?>/author/soumettre" class="btn btn-accent">Soumettre un article</a> <a href="<?= $base ?>/instructions-auteurs" class="btn btn-outline">Instructions aux auteurs</a></p>
</div>
<div class="dashboard-card" id="articles">
  <h2>Mes articles</h2>
  <div class="overflow-auto">
    <table class="dashboard-table">
      <thead>
        <tr>
          <th>Titre</th>
          <th>Date</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($articles)): ?>
          <tr><td colspan="4" class="text-muted">Aucun article pour l'instant.</td></tr>
        <?php else: ?>
          <?php foreach ($articles as $a): ?>
          <tr>
            <td><?= htmlspecialchars($a['titre']) ?></td>
            <td><?= authorFormatDate($a['date_soumission'] ?? $a['created_at'] ?? null) ?></td>
            <td><?= authorStatutBadge($a['statut'] ?? 'soumis') ?></td>
            <td class="wrap-row">
              <a href="<?= $base ?>/author/article/<?= (int) $a['id'] ?>" class="btn btn-sm btn-outline">Voir</a>
              <?php if (($a['statut'] ?? '') === 'soumis'): ?>
                <a href="<?= $base ?>/author/article/<?= (int) $a['id'] ?>/edit" class="btn btn-sm btn-outline">Modifier</a>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<div class="dashboard-card">
  <h2>Abonnement</h2>
  <?php if ($abonnement): ?>
    <p class="text-muted text-sm mb-2">Votre abonnement auteur est <strong>actif</strong> jusqu'au <?= authorFormatDate($abonnement['date_fin'] ?? null) ?>.</p>
  <?php else: ?>
    <p class="text-muted text-sm mb-2">Vous n'avez pas d'abonnement actif.</p>
  <?php endif; ?>
  <p class="text-sm mb-0"><a href="<?= $base ?>/author/abonnement" class="btn btn-sm btn-outline-primary">Gérer mon abonnement</a></p>
</div>
