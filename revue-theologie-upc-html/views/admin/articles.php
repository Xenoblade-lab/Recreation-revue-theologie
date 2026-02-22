<?php
$articles = $articles ?? [];
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
  <h1>Articles</h1>
  <p>Liste des articles soumis à la revue.</p>
</div>
<div class="dashboard-card">
  <div class="overflow-auto">
    <table class="dashboard-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Titre</th>
          <th>Date</th>
          <th>Statut</th>
          <th>Auteur</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($articles)): ?>
          <tr><td colspan="6" class="text-muted">Aucun article.</td></tr>
        <?php else: ?>
          <?php foreach ($articles as $a): ?>
            <tr>
              <td><?= (int) ($a['id'] ?? 0) ?></td>
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
</div>
