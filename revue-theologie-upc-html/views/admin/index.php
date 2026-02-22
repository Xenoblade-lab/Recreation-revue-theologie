<?php
$totalArticles = (int) ($totalArticles ?? 0);
$publishedArticles = (int) ($publishedArticles ?? 0);
$reviewersCount = (int) ($reviewersCount ?? 0);
$monthlyRevenue = (float) ($monthlyRevenue ?? 0);
$lastSubmissions = $lastSubmissions ?? [];
$base = $base ?? '';

function adminStatutBadge(string $statut): string {
    $map = [
        'soumis' => ['label' => 'Soumis', 'class' => 'badge-primary'],
        'en_lecture' => ['label' => 'En évaluation', 'class' => 'badge'],
        'accepte' => ['label' => 'Accepté', 'class' => 'badge green'],
        'rejete' => ['label' => 'Rejeté', 'class' => 'badge-accent'],
        'revision_requise' => ['label' => 'Révision requise', 'class' => 'badge-accent'],
        'valide' => ['label' => 'Publié', 'class' => 'badge green'],
        'publie' => ['label' => 'Publié', 'class' => 'badge green'],
    ];
    $c = $map[$statut] ?? ['label' => $statut, 'class' => 'badge'];
    return '<span class="badge ' . $c['class'] . '">' . htmlspecialchars($c['label']) . '</span>';
}

function adminFormatDate(?string $d): string {
    if (!$d) return '—';
    $t = strtotime($d);
    return $t ? date('j M. Y', $t) : $d;
}
?>
<div class="dashboard-header">
  <h1>Tableau de bord</h1>
  <p>Vue d'ensemble de la revue et des dernières activités.</p>
</div>
<div class="dashboard-stats">
  <div class="stat-card">
    <div class="stat-icon primary"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#file-text"/></svg></div>
    <div>
      <div class="stat-value"><?= $totalArticles ?></div>
      <div class="stat-label">Articles au total</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon green"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#check"/></svg></div>
    <div>
      <div class="stat-value"><?= $publishedArticles ?></div>
      <div class="stat-label">Articles publiés</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon gold"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#user"/></svg></div>
    <div>
      <div class="stat-value"><?= $reviewersCount ?></div>
      <div class="stat-label">Évaluateurs actifs</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon accent"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#award"/></svg></div>
    <div>
      <div class="stat-value"><?= number_format($monthlyRevenue, 0, ',', ' ') ?> $</div>
      <div class="stat-label">Revenus du mois</div>
    </div>
  </div>
</div>
<div class="dashboard-card">
  <h2>Dernières soumissions</h2>
  <div class="overflow-auto">
    <table class="dashboard-table">
      <thead>
        <tr>
          <th>Titre</th>
          <th>Date</th>
          <th>Statut</th>
          <th>Auteur</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($lastSubmissions)): ?>
          <tr><td colspan="5" class="text-muted">Aucun article.</td></tr>
        <?php else: ?>
          <?php foreach ($lastSubmissions as $a): ?>
          <tr>
            <td><?= htmlspecialchars($a['titre'] ?? '') ?></td>
            <td><?= adminFormatDate($a['date_soumission'] ?? null) ?></td>
            <td><?= adminStatutBadge($a['statut'] ?? 'soumis') ?></td>
            <td><?= htmlspecialchars(trim(($a['auteur_prenom'] ?? '') . ' ' . ($a['auteur_nom'] ?? ''))) ?></td>
            <td><a href="<?= $base ?>/admin/article/<?= (int) ($a['id'] ?? 0) ?>" class="btn btn-sm btn-outline">Voir</a></td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <p class="text-sm text-muted mt-4 mb-0"><a href="<?= $base ?>/admin/articles" class="text-primary">Voir tous les articles</a></p>
</div>
<div class="dashboard-card">
  <h2>Actions rapides</h2>
  <div class="flex flex-wrap gap-2">
    <a href="<?= $base ?>/admin/users/create" class="btn btn-outline-primary">Créer un utilisateur</a>
    <a href="<?= $base ?>/admin/articles" class="btn btn-outline-primary">Gérer les articles</a>
    <a href="<?= $base ?>/admin/paiements" class="btn btn-outline-primary">Paiements</a>
    <a href="<?= $base ?>/admin/parametres" class="btn btn-outline-primary">Paramètres revue</a>
  </div>
</div>
